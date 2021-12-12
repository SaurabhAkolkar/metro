<?php

namespace App;

use App\User;
use App\Message;
use Validator;
use Illuminate\Database\Eloquent\Model;

class MessageHead extends Model {
    
    protected $fillable = ['from_user_id', 'to_user_id'];
    protected $table = 'message_head';
    public $primaryKey = "message_head_id";

    public static function tableName() {
        return with(new static)->getTable();
    }
    
    public function getById($id) {
        return $this->where('message_head_id',$id)->first();
    }
    
    public function to_user() {
        return $this->belongsTo('App\User','to_user_id','id');
    }
    
    public function from_user() {
        return $this->belongsTo('App\User','from_user_id','id');
    }
    
    public function getLastMessage(){
        $messageObj = new Message();
        return $messageObj->getLastMessage($this->message_head_id,1);
    }
    
    public function isAlreadyExists($from_user_id,$to_user_id) {
        return $this->where(function ($query) use ($from_user_id,$to_user_id) {
                $query->where('from_user_id', $from_user_id)
                      ->where('to_user_id', $to_user_id);
            })->orWhere(function ($query) use ($from_user_id,$to_user_id) {
                $query->where('from_user_id', $to_user_id)
                      ->where('to_user_id', $from_user_id);
            })->first();
    }
    
    public function getChatHistory($user_id) {
        $data = $this->where('from_user_id', $user_id)->orWhere('to_user_id', $user_id)->orderBy('updated_at','DESC')->get();
        $msgData = array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $temp = array();
                $temp['message_head_id'] = $value->message_head_id;
                if($value->from_user_id == $user_id){
                    $other_user = $value->to_user;
                } else {
                    $other_user = $value->from_user;
                }
                $temp['to_user_id'] = $other_user->id;
                $temp['user_name'] = $other_user->name;
                $messageObj = $value->getLastMessage();
                $temp['message_type'] = $messageObj->message_type;
                $temp['message'] = $messageObj->message;
                $temp['image'] = $messageObj->image;
                $temp['created_at'] = $messageObj->created_at->timestamp;
                $msgData[] = $temp;
            }
        }
        return $msgData;
    }
    
    public function add($input) {
        $messageHeadObj = new MessageHead;
        $messageHeadObj->from_user_id = $input['from_user_id'];
        $messageHeadObj->to_user_id = $input['to_user_id'];
        if ($messageHeadObj->save()) {
            return ["success" => true, "message" => "", "data" => $messageHeadObj];
        }
        return ["success" => false, "message" => "Unable to send message"];
    }
    
}
