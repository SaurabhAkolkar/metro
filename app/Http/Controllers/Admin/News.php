<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Event;
use DB;
class News extends Controller
{
    //
   var $msg;

    public function __construct()
    {
        # code...
        $this->middleware('auth:admin');
       
    }
    public function index()
    {
       $news = Event::orderBy('id','desc')->get();
        
       return view('admin.news',compact('news'));
    }

    /*Firebase for notification*/
    public function firebase_notification($device_token,$msg,$title)
    {          
        $API_ACCESS_KEY= 'AAAA0JIdHpc:APA91bGmvp_fMIu81v3ivfaL2bRAmKGYKDs3eIAmQLB632JsfyQPjUS0kvUucSRtH6xBI6OuSupjf_x-56mpeEDeEdcO_g8zMGaJSH91lfuARc7M53ZCgozWiSBp1kS96voD7AES_RFy';

        $registrationIds =$device_token;

        $msg = array
          (
            'body'    => $msg,
            'title'   => $title,
            'icon'    => 'myicon',/*Default Icon*/
            'sound'   =>  'mySound'/*Default sound*/
        );
        $fields = array
        (
          'to' => $registrationIds,
          'notification'    => $msg
        );
        $headers = array
        (
          'Authorization: key=' . $API_ACCESS_KEY,
          'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );     
    }

    public function addNews(Request $request)
    {

     $validator = Validator::make($request->all(), [
                   'title'=>'required|min:3',
                   'description'=>'required|min:30',
                   "image" => 'image|mimes:jpg,jpeg,png|max:2048'
     ]);
          
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors()), 422);
        }
       $fullname = "";
     if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        
        $destinationPath = 'assets/images/news/';
        if($image->move($destinationPath, $name))
        $fullname = "/assets/images/news/$name";
    }

     $data = [
         'image' => $fullname,
         'heading' => $request->title,
         'description' => $request->description
     ];

        if(Event::create($data)){
             
            $userkey = DB::table('firebase')->get();
             if($userkey)
             {
                foreach ($userkey as $userkey) 
                {
                    $msg=$request->title;
                    $title="Latest News";
                    if($userkey->firebase_token)
                    {
                        $this->firebase_notification($userkey->firebase_token,$msg,$title);
                    }
                }
             }

            return ["success"=>true, "message"=>"News Added Successfully" ];
        }
       
       return ["success"=>false, "message"=>"Unable to add news" ];
        
    }

public function deleteNews($id = 0)
{
    $event = Event::find($id);
    if(Event::destroy($id)){
        $image = ltrim($event->image,"/");
        if(file_exists($image)) unlink($image);
        return redirect('admin/news')->with('news-msg',"News Successfully Deleted");

       }
     
     else {

        return redirect('admin/news')->with('news-msg',"Unable to Deleted News");
     }
}

}
