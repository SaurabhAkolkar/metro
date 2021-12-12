<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterValidation;
use App\Mail\RegistrationSuccessfull;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class AuthController extends Controller
{
    public $msg;
    protected $images = [];
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, int $x = 0)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => "required|numeric|digits:10",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $credentials = request(['mobile', 'password']);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return Response::fail('Invalid User or Password');
        }

        return $this->respondWithToken($token, $x, $request->firebase_token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(int $t = 0, $user_id = 0)
    {
        //DB::enableQueryLog();
        $user = auth('api')->user();

        if ($t === 1) {
            $me = CommonQuery::getMainQuery()
                ->where('users.id', $user_id)
                ->first();
        } else {
            $me = CommonQuery::getMainQuery()
                ->where('users.id', $user->id)
                ->first();
        }

        if (collect($me)->isEmpty()) {
            return Response::fail('User not found');
        }

        if ($t === 0 && $me->is_active == "0") {
            return Response::fail("Activate Your Account from Email");
        }

        if ($t === 0 && $me->is_active == "3") {
            return Response::fail("Your Account has been disabled");
        }

        $x = $me->toArray();
        $remaining_fields = $total_fields = 0;
        // wasRecentlyCreated
        foreach ($x as $key => $value) {
            if ($value === "" || is_null($value)) {
                // dd($key);
                $remaining_fields++;
            }
            $total_fields++;
        }

        $me->profile_completion = (string) (100 - round(($remaining_fields / $total_fields) * 100));

        if ($me->lang === "hi") {
            $me->state = $me->state_hi;
            $me->district = $me->district_hi;
            $me->occupation = $me->occupation_hi;
            $me->marital_status = $me->marital_status_hi;
            $me->manglik = $me->manglik_hi;
        }

        if ($t === 1) {
            return $me;
        }

        return Response::pass('User found', $me);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return Response::pass('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, int $x = 0, $firebase_token = null)
    {
        $user = auth('api')->user();
        if ($x === 0 && $user->is_active == "0") {
            return Response::fail("Activate Your Account from Email");
        }

        if ($x === 0 && $user->is_active == "3") {
            return Response::fail("Your Account has been disabled");
        }

        /* set firebase token */
        if (!is_null($firebase_token)) {
            $fb = DB::table('firebase')->where('user_id', $user->id)->first();
            if (is_null($fb)) {
                DB::table('firebase')->insert(['user_id' => $user->id, 'firebase_token' => $firebase_token]);
            } else {
                DB::table('firebase')->where(['user_id' => $user->id])->update(['firebase_token' => $firebase_token]);
            }

        }

        $me = $this->me(1, $user->id);

        return response()->json([
            "success" => true,
            "message" => "Logged in Successfully",
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => $me,
        ]);
    }

    /* Register User */
    public function register(UserRegisterValidation $request)
    {
        $data = $request->all();

        if ($data['working'] == 0) {
            $data['income'] = 0;
            $data['occupation'] = 0;
        }

        //file_put_contents("test.txt",json_encode($request->all())."\n",FILE_APPEND);
        //die;
        $otp = "Use " . $data['otp'] . " as the code to verify your phone number on RKSP Matrimony";
        /* Your message api will be here */
        //$this->send_opt_mobile($request->mobile,$msg);

        unset($data['otp']);
        $data['verify_token'] = Str::random(60);
        $user = User::create($data)->count();
        if ($user > 0) {
            return $this->login($request, 1);
        }

        return Response::fail("Registration Failed");
    }

/* Reset Password */
    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => "required",
            "new_password" => "required",
        ]);
        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $user = auth('api')->user();
        // dd($request->old_password ." - ".$user->password);
        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                //Uncomment for change Password
                //'password' => $request->new_password
                'password' => "123456",
            ])->save();

            return Response::pass("Password reset Successfull");
        }
        return Response::fail("Password reset failed");
    }

/* forget password */

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => "required|numeric|digits:10",
        ]);
        if ($validator->fails()) {
            return Response::fail($validator->errors()->first());
        }

        $user = User::where('mobile', '=', $request->mobile)->first();
        if (is_null($user)) {
            return Response::fail('User does\'nt exist');
        }

        $password = rand(100000, 999999);
        //Uncomment for change password
        //$user->password = $password;
        $user->password = "123456";
        if ($user->save()) {
            $msg = "Your new login password is $password please change after login";

            /* Your message api will be here */
            //$this->send_opt_mobile($request->mobile,$msg);

            return Response::pass("Password Sent");
        }
        return Response::fail("Something went wrong");
    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $data['verify_token'] = Str::random(60);
        # code...
        Mail::send(new RegistrationSuccessfull($data));
    }

    public function send_opt_mobile($mobile_number, $msg)
    {
        $authKey = "API_KEY";

        //Multiple mobiles numbers separated by comma
        $mobileNumber = $mobile_number;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "MATAPP";

        //Your message to send, Add URL encoding here.
        $message = urlencode($msg);

        //Define route
        $route = "4";
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route,
        );

        //API URL
        $url = "https://api.msg91.com/api/sendhttp.php?authkey='$authKey'&mobiles='$mobileNumber'&message='$message'&sender='$senderId'&route=4&country=91";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);
    }
}
