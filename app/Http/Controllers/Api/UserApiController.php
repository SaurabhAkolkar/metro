<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Shortlist;
use App\Visitor;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\ImageValidationRequest;
use Seshac\Otp\Otp;
use Illuminate\Support\Facades\Http;

class UserApiController extends Controller {

    protected $images = [];

    public function __construct() {
        
    }

    public function sendOtp(Request $req)
    {
        $mobile = $req->mobile_number;
        $obj = Otp::generate($mobile);

        $message_content = urlencode("Dear Sir, WELCOME TO IGTRADE YOUR LOGIN Id is : ".$obj->token." bridgedental");
        return  file_get_contents('https://www.hellotext.live/vb/apikey.php?apikey='.env('MSG_API_KEY').'&senderid='.env('SENDER_ID').'&number='.$mobile.'&message='.$message_content);
       

    }

    public function verifyOtp(Request $req)
    {
       return  json_encode(Otp::validate($req->mobile_number, $req->otp));
    }

    /* search Users */

    public function search(Request $request) {

        $validator = Validator::make($request->all(), [
                    'gender' => "required|alpha|max:1",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }
        $data = [];

        if ($request->state)
            $data['users.state'] = $request->state;
        if ($request->district)
            $data['users.district'] = $request->district;
        if ($request->manglik)
            $data['users.manglik'] = $request->manglik;
        if ($request->marital_status)
            $data['users.marital_status'] = $request->marital_status;
        if ($request->caste)
            $data['users.caste'] = $request->caste;

        $users = CommonQuery::getMainQuery()
                ->where(['gender' => $request->gender, 'is_active' => 1])
                ->where($data)
                ->simplePaginate();

        if (count($users) === 0) {
            return Response::fail("No candidates available");
        }

        $user_data = CommonQuery::addParameter($users->items());
        return response()->json([
                    "success" => true,
                    "message" => "Candidates available",
                    "data" => $user_data,
                    "next_page_url" => (is_null($users->nextPageUrl())) ? "" : $users->nextPageUrl(),
                    "per_page" => $users->perPage(),
                    "prev_page_url" => (is_null($users->previousPageUrl())) ? "" : $users->previousPageUrl(),
                    "has_more_pages" => $users->hasMorePages(),
                    "current_url" => $users->url($users->currentPage()),
                    "current_page" => $users->currentPage(),
        ]);
    }

    /* Get all Users */

    public function users(Request $request) {

        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
                    'gender' => "required|alpha|max:1",
        ]);
        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }
        $current_user = auth('api')->user();
        $sid = $this->shortListed($request, 1);
        $interest = (new InterestsController)->allInterest($request->user_id);

        $gender = (strtoupper($request->gender) == 'M') ? 'F' : 'M';

        if ($request->state)
            $data['users.state'] = $request->state;
        if ($request->district)
            $data['users.district'] = $request->district;
        if ($request->manglik)
            $data['users.manglik'] = $request->manglik;
        if ($request->marital_status)
            $data['users.marital_status'] = $request->marital_status;
        if ($request->caste)
            $data['users.caste'] = $request->caste;

        $data['users.gender'] = $gender;

        $users = CommonQuery::getMainQuery()
                ->whereNotIn('users.id', $interest['sent'])
                ->whereNotIn('users.id', $interest['recieved'])
                ->where(['is_active' => 1])
                ->where($data)
                ->orderByRaw("FIELD(state, $current_user->state) desc,FIELD(district, $current_user->district) desc, users.id desc")
                ->simplePaginate();

        if (count($users) === 0) {
            return Response::fail("No candidates available");
        }

        $user_data = CommonQuery::addParameter($users->items(), $sid, $interest);

        return response()->json([
                    "success" => true,
                    "message" => "Candidates available",
                    "data" => $user_data,
                    "next_page_url" => (is_null($users->nextPageUrl())) ? "" : $users->nextPageUrl(),
                    "per_page" => $users->perPage(),
                    "prev_page_url" => (is_null($users->previousPageUrl())) ? "" : $users->previousPageUrl(),
                    "has_more_pages" => $users->hasMorePages(),
                    "current_url" => $users->url($users->currentPage()),
                    "current_page" => $users->currentPage(),
        ]);
    }

    /* Update user data */

    public function update(UpdateRequest $request) {
        $data = $request->all();

        $user_id = $data['user_id'];
        unset($data['user_id']);
        unset($data['token']);

        if (sizeof($data) === 0)
            return Response::fail("Data required to Update");
        $update = User::where('id', $user_id)->update($data);
        //dd($update);
        if ($update === 0)
            return Response::fail("Not Updated");

        return Response::pass("Updated Successfully");
    }

    /* Recently Joined */

    public function justJoin(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
                    'gender' => "required|alpha|max:1",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }
        $gender = (strtoupper($request->gender) == 'M') ? 'F' : 'M';
        $users = CommonQuery::getMainQuery()
                ->where(['gender' => $gender, 'is_active' => 1])
                ->limit(15)
                ->orderBy('id', 'desc')
                ->get();

        if (count($users) === 0) {
            return Response::fail("No candidates available");
        }

        $sid = $this->shortListed($request, 1);
        $interest = (new InterestsController)->allInterest($request->user_id);
        $user_data = CommonQuery::addParameter($users, $sid, $interest);


        return Response::pass("Candidates available", $user_data);
    }

    /* Upload Gallary */

    public function userImages(Request $request) {
        //dd($request->images0->getSize());
        $user = auth('api')->user();

        /* Image Validations */
        $mime_types = ['image/png', 'image/jpeg', 'image/gif', 'image/bmp'];
        foreach ($request->file() as $key => $file):
            $mime = $request->{$key}->getMimeType();

            if (!in_array($mime, $mime_types))
                return Response::fail("The images must be one of the following types jpg, jpeg, png, gif, bmp");
            $size = $request->{$key}->getSize();
            if ($size > 10485760)
                return Response::fail("Image size must be less than 10mb");
        endforeach;

        /* Image Upload */
        foreach ($request->file() as $key => $file):
            $ext = $request->{$key}->getClientOriginalExtension();
            $user->addMedia($request->{$key})->usingFileName(time() . ".$ext")->toMediaCollection('gallary');
        endforeach;


        if (is_null($user->avatar_thumb) || strchr($user->avatar_thumb, '/users/default-user.png')) {
            $img = $user->getMedia('gallary')->first();
            $user->avatar_big = $img->getUrl('big');
            $user->avatar_medium = $img->getUrl('medium');
            $user->avatar_thumb = $img->getUrl('thumb');
            $user->save();
        }

        return Response::pass("Upload done");
    }

    /* Set profile picture  */

    public function setAvatar(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    "media_id" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $user = auth('api')->user();

        $img = $user->getMedia('gallary')
                ->keyBy('id')
                ->get($request->media_id);

        if (collect($img)->isEmpty())
            return Response::fail("Media not found");

        $user->avatar_big = $img->getUrl('big');
        $user->avatar_medium = $img->getUrl('medium');
        $user->avatar_thumb = $img->getUrl('thumb');

        if ($user->save())
            return Response::pass("Avatar Updated Successfully");

        return Response::fail("Unable to set Avatar");
    }

    /* Set shortlisted Candidate */

    public function setShortlisted(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    "user_id" => "required|numeric",
                    "short_listed_id" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $set = Shortlist::firstOrCreate([
                    "user_id" => $request->user_id,
                    "short_listed_id" => $request->short_listed_id
        ]);

        if ($set)
            return Response::pass("Shortlisted");

        return Response::fail("Something went wrong. Try again..");
    }

    /* Remove shortlisted Candidate */

    public function removeShortlisted(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    "user_id" => "required|numeric",
                    "short_listed_id" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $remove = Shortlist::where([
                    "user_id" => $request->user_id,
                    "short_listed_id" => $request->short_listed_id
                ])->delete();
        if ($remove)
            return Response::pass("Shortlisted removed");
        return Response::fail("Something went wrong. Try again..");
    }

    /* Delete gallary Images */

    public function deleteImage(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    "media_id" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $user = auth('api')->user();
        $user->deleteMedia($request->media_id);

        return Response::pass("Deleted Successfully");
    }

    /* Get all shortlisted Candidates */

    public function shortListed(Request $request, int $x = 0) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
                    'gender' => "required|alpha|max:1",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $shortlisted = Shortlist::where(['user_id' => $request->user_id])
                        ->select('short_listed_id')->get();
        $sid = [];
        foreach ($shortlisted as $key => $value) {
            $sid[] = $value->short_listed_id;
        }
        if ($x === 1)
            return $sid;

        if (count($sid) === 0)
            return Response::fail('No shortlisted candidates available');
        $gender = (strtoupper($request->gender) == 'M') ? 'F' : 'M';
        $shortlisted_data = CommonQuery::getMainQuery()
                ->whereIn('users.id', $sid)
                ->where(['gender' => $gender, 'is_active' => 1])
                ->get();
        if ($shortlisted_data->count() === 0)
            return Response::fail('No shortlisted candidate available');

        $interest = (new InterestsController)->allInterest($request->user_id);
        /* set shortlisted true */
        $user_data = CommonQuery::addParameter($shortlisted_data, $sid, $interest);

        return Response::pass('Shortlisted Candidates', $user_data);
    }

    /* Get Gallary Images */

    public function getGallary(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $user = User::find($request->user_id);
        if (is_null($user))
            return Response::fail('User not available');
        $user->getMedia('gallary')->each(function ($fileAdder) {
            $this->images[] = (object) ['media_id' => $fileAdder->id,
                        'thumb_url' => $fileAdder->getUrl('thumb'),
                        'medium_url' => $fileAdder->getUrl('medium'),
                        'big_url' => $fileAdder->getUrl('big'),
                        'uploaded' => $fileAdder->created_at->format("Y-m-d H:i:s A")];
        });
        if (count($this->images) === 0)
            return Response::fail('Images not available');
        return Response::pass('Images found', $this->images);
    }

    /* Set Visitor Candidate */

    public function setVisitor(Request $request) {
        # code...
        $validator = Validator::make($request->all(), [
                    "user_id" => "required|numeric",
                    "visitor_id" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }
        //file_put_contents("test.txt",json_encode($request->visitor_id)."\n",FILE_APPEND);
        $data = [
            "user_id" => $request->user_id,
            "visitor_id" => $request->visitor_id
        ];

        $set = Visitor::firstOrCreate($data);

        if ($set) {
            $count = Visitor::where(['user_id' => $request->user_id])->count();
            if ($count > 25) {
                Visitor::where(['user_id' => $request->user_id])
                        ->orderBy('id', 'asc')
                        ->limit(1)
                        ->delete();
            }
            return Response::pass("Visited");
        }
        return Response::fail("Something went wrong. Try again..");
    }

    /* Get all Visited Candidates */

    public function getVisitors(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
                    'gender' => "required|alpha|max:1",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $visited = Visitor::where(['visitor_id' => $request->user_id])
                        ->select('user_id')->get();
        $vid = [];
        foreach ($visited as $key => $value) {
            $vid[] = $value->user_id;
        }

        if (count($vid) === 0)
            return Response::fail('No Visited candidates available');
        $gender = (strtoupper($request->gender) == 'M') ? 'F' : 'M';
        $visitor_data = CommonQuery::getMainQuery()
                ->whereIn('users.id', $vid)
                ->where(['gender' => $gender, 'is_active' => 1])
                ->get();
        if ($visitor_data->count() === 0)
            return Response::fail('No Visited candidate available');

        $sid = $this->shortListed($request, 1);

        $interest = (new InterestsController)->allInterest($request->user_id);
        /* set shortlisted true */
        $user_data = CommonQuery::addParameter($visitor_data, $sid, $interest);

        return Response::pass('Visited Candidates', $user_data);
    }

    public function getAllPackages(Request $request) {
        $packages = DB::table('packages')->where(['status' => 1])->get();
        if ($packages->count() > 0) {
            $package = array();
            foreach ($packages as $packages) {
                if ($packages->subscription_type == 0) {
                    $packages->subscription_name = "Free";
                } elseif ($packages->subscription_type == 1) {
                    $packages->subscription_name = "Monthly";
                } elseif ($packages->subscription_type == 2) {
                    $packages->subscription_name = "Quarterly";
                } elseif ($packages->subscription_type == 3) {
                    $packages->subscription_name = "Half Yearly";
                } elseif ($packages->subscription_type == 4) {
                    $packages->subscription_name = "Yearly";
                }
                array_push($package, $packages);
            }
            return Response::pass('Get all packages.', $package);
        } else {
            return Response::fail("Not any package.");
        }
    }

    public function subscription(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
                    'order_id' => "required",
                    'txn_id' => "required",
                    'package_id' => "required",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $get_package = DB::table('packages')->where('id', $request->package_id)->first();
        if (is_null($get_package)) {
            return Response::fail("Not any package.");
            exit();
        }
        $user_id = $request->user_id;
        $txn_id = $request->txn_id;
        $order_id = $request->order_id;
        $date = date('Y-m-d');
        $current_date = strtotime($date);
        $price = $get_package->price;
        $subscription_type = $get_package->subscription_type;

        $data['user_id'] = $user_id;
        $data['txn_id'] = $txn_id;
        $data['order_id'] = $order_id;
        $data['subscription_start_date'] = $current_date;
        $data['price'] = $price;
        $data['subscription_type'] = $subscription_type;

        $dataupdate = DB::table('user_subscription')->where('user_id', $request->user_id)->first();
        if (is_null($dataupdate)) {
            if ($subscription_type == 1) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 3) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 180, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 2) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 90, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 0) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 4) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 365, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            }
            DB::table('user_subscription')->insert($data);
            DB::table('subscription_history')->insert($data);

            $userkey = DB::table('firebase')->where('user_id', $user_id)->first();
            if ($userkey) {
                $msg = "Your package has been upgraded successfully.";
                $title = "Profile Upgrade";
                if ($userkey->firebase_token) {
                    $this->firebase_notification($userkey->firebase_token, $msg, $title, "70005");
                }
            }

            return Response::pass("Subscription successfully.");
            exit();
        } else {
            $check_end_date = $dataupdate->subscription_end_date;

            if ($current_date >= $check_end_date) {
                if ($subscription_type == 1) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 3) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 180, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 2) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 90, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 0) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 4) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 365, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                }
                DB::table('user_subscription')->where(array('user_id' => $user_id))->update($data);
                DB::table('subscription_history')->insert($data);
                $userkey = DB::table('firebase')->where('user_id', $user_id)->first();
                if ($userkey) {
                    $msg = "Your package has been upgraded successfully.";
                    $title = "Profile Upgrade";
                    if ($userkey->firebase_token) {
                        $this->firebase_notification($userkey->firebase_token, $msg, $title, "70005");
                    }
                }
                return Response::pass("Subscription successfully.");
                exit();
            } else {
                if ($subscription_type == 1) {
                    $no_of_days = 30;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 3) {
                    $no_of_days = 180;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 2) {
                    $no_of_days = 90;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 0) {
                    $no_of_days = 30;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 4) {
                    $no_of_days = 365;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                }
                DB::table('user_subscription')->where(array('user_id' => $user_id))->update($data);
                DB::table('subscription_history')->insert($data);
                $userkey = DB::table('firebase')->where('user_id', $user_id)->first();
                if ($userkey) {
                    $msg = "Your package has been upgraded successfully.";
                    $title = "Profile Upgrade";
                    if ($userkey->firebase_token) {
                        $this->firebase_notification($userkey->firebase_token, $msg, $title, "70005");
                    }
                }
                return Response::pass("Subscription successfully.");
                exit();
            }
        }
    }

    public function get_my_subscription_history(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $get_my_subscription = DB::table('subscription_history')->where(['user_id' => $request->user_id])->get();
        if (is_null($get_my_subscription)) {
            return Response::fail("Not history found.");
            exit();
        } else {
            $get_my_subscriptions = array();
            foreach ($get_my_subscription as $get_my_subscription) {
                if ($get_my_subscription->subscription_type == 0) {
                    $get_my_subscription->subscription_name = "Free";
                } elseif ($get_my_subscription->subscription_type == 1) {
                    $get_my_subscription->subscription_name = "Monthly";
                } elseif ($get_my_subscription->subscription_type == 2) {
                    $get_my_subscription->subscription_name = "Quarterly";
                } elseif ($get_my_subscription->subscription_type == 3) {
                    $get_my_subscription->subscription_name = "Half Yearly";
                } elseif ($get_my_subscription->subscription_type == 4) {
                    $get_my_subscription->subscription_name = "Yearly";
                }
                array_push($get_my_subscriptions, $get_my_subscription);
            }
            return Response::pass('Get my subscription history.', $get_my_subscriptions);
        }
    }

    public function get_my_subscription(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => "required|numeric",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $get_my_subscription = DB::table('user_subscription')->where('user_id', $request->user_id)->first();
        if (is_null($get_my_subscription)) {
            return Response::fail("Not history found.");
            exit();
        } else {
            $date = date('Y-m-d');
            $current_date = strtotime($date);
            $end_date = $get_my_subscription->subscription_end_date;
            $get_title = DB::table('packages')->where('subscription_type', $get_my_subscription->subscription_type)->first();

            $get_my_subscription->subscription_title = $get_title->title;
            $datediff = $end_date - time();
            $get_my_subscription->days = round($datediff / (60 * 60 * 24));
            if ($get_my_subscription->subscription_type == 0) {
                $get_my_subscription->subscription_name = "Free";
            } elseif ($get_my_subscription->subscription_type == 1) {
                $get_my_subscription->subscription_name = "Monthly";
            } elseif ($get_my_subscription->subscription_type == 2) {
                $get_my_subscription->subscription_name = "Quarterly";
            } elseif ($get_my_subscription->subscription_type == 3) {
                $get_my_subscription->subscription_name = "Half Yearly";
            } elseif ($get_my_subscription->subscription_type == 4) {
                $get_my_subscription->subscription_name = "Yearly";
            }

            if ($current_date > $end_date) {
                return Response::fail("Not history found.");
                exit();
            } else {
                return Response::pass('Get my subscription history.', $get_my_subscription);
            }
        }
    }

    /* Firebase for notification */

    public function firebase_notification($device_token, $msg, $title, $type) {
        $firebaseArr = config('services.firebase');
        $registrationIds = $device_token;
        $msg = array
            (
            'body' => $msg,
            'title' => $title,
            'type' => $type,
            'icon' => 'myicon', /* Default Icon */
            'sound' => 'mySound'/* Default sound */
        );
        $fields = array
            (
            'to' => $registrationIds,
            'data' => $msg
        );
        $headers = array
            (
            'Authorization: key=' . $firebaseArr['key'],
            'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $firebaseArr['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }

}
