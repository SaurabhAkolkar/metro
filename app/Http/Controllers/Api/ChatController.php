<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\MessageHead;
use App\Message;
use Validator;
use App\Http\Controllers\Controller;

class ChatController extends Controller {

    public function __construct() {
    }
    
    public function setMessage(Request $request) {
        $input = $request->all();
        $user_data = auth('api')->user();
        $input['from_user_id'] = $user_data->id;
        
        $messageObj = new Message();
        $isValid = $messageObj->isValidMessage($input);
        if(!$isValid['success']){
            return Response::fail($isValid['message']);
        }
        $messageHeadObj = new MessageHead();
        $messageHeadObjData = $messageHeadObj->isAlreadyExists($input['from_user_id'],$input['to_user_id']);
        if(!empty($messageHeadObjData)){
            $messageHeadObjData->touch();
            $input['message_head_id'] = $messageHeadObjData->message_head_id;
        } else {
            $respMessageHeadObjData = $messageHeadObj->add($input);
            if(!$respMessageHeadObjData['success']){
                return Response::fail($respMessageHeadObjData['message']);
            }
            $input['message_head_id'] = $respMessageHeadObjData['data']->message_head_id;
        }
        if ($request->hasFile('image') && $input['message_type'] == 2) {
            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = 'assets/images/message/';
            if($image->move($destinationPath, $image_name))
            $fullname = $image_name;
            $input['image']=$fullname;
        }
        $messageObj = new Message();
        $messageObjData = $messageObj->add($input);
        if(!$messageObjData['success']){
            return Response::fail($messageObjData['message']);
        }
        /* Notification */
        $userkey = \DB::table('firebase')->where('user_id', $input['to_user_id'])->first();
        if($userkey){
            $msg = ($messageObjData['data']->message_type == 1) ?  $messageObjData['data']->message : 'Image';
            $title = $user_data->name;
            if ($userkey->firebase_token) {
                $this->firebase_notification($userkey->firebase_token, $msg, $title);
            }
        }
        
        return response()->json(["success" => true,"message" => "Message send successfully"]);
    }
    
    public function firebase_notification($device_token, $msg, $title, $type = "10001")
    {
        $firebaseArr = config('services.firebase');
        $registrationIds = $device_token;
        $msg = array(
            'body'    => $msg,
            'title'   => $title,
            'type'   => $type,
            'icon'    => 'myicon',/*Default Icon*/
            'sound'   =>  'mySound'/*Default sound*/
        );
        $fields = array(
            'to' => $registrationIds,
            'data'    => $msg
        );
        $headers = array(
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
    
    function getMessageHistory(Request $request){
        $input = $request->all();
        $user_data = auth('api')->user();
        $user_id = $user_data->id;
        $messageHeadObj = new MessageHead();
        $messageHeadObjData = $messageHeadObj->getChatHistory($user_id);
        return response()->json(["success" => true,"message" => "successfully load", "data" => $messageHeadObjData]);
    }
    
    function getMessage(Request $request){
        $input = $request->all();
        if(!isset($input['to_user_id']) || empty($input['to_user_id'])){
            return Response::fail('To user id required');
        }
        $user_data = auth('api')->user();
        $messageObj = new Message();
        $messageObjData = $messageObj->getMessage($user_data->id,$input['to_user_id']);
        if(!$messageObjData){
            return Response::fail('Server Error');
        }
        return response()->json(["success" => true,"message" => "successfully load", "data" => $messageObjData]);
    }
    
}
