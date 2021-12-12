<?php

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;
use App\MessageHead;

class Message extends Model {
    
    protected $fillable = ['message_head_id', 'message', 'from_user_id', 'to_user_id', 'message_type', 'is_read', 'image'];
    protected $table = 'message';
    public $primaryKey = "message_id";

    public static function tableName() {
        return with(new static)->getTable();
    }

    public function getById($id) {
        return $this->where('message_id',$id)->first();
    }
    
    public function isValidMessage($input) {
        $validator = Validator::make($input, [
            'from_user_id' => 'required',
            'to_user_id' => 'required',
            'message_type' => 'required',
        ]);
        if ($validator->fails()) {
            return ["success" => false, "message" => $validator->errors()->first()];
        }
        if($input['message_type'] == 2 && !isset($input['image']) && empty($input['image'])){
            return ["success" => false, "message" => "Please add image"];
        }
        if($input['message_type'] == 1 && !isset($input['message']) && empty($input['message'])){
            return ["success" => false, "message" => "The message field is required"];
        }
        return ["success" => true, "message" => "OK"];
    }
    
    public function add($input) {
        $messageObj = new Message;
        $messageObj->message_head_id = $input['message_head_id'];
        $messageObj->message = (isset($input['message'])) ? $input['message'] : '';
        $messageObj->from_user_id = $input['from_user_id'];
        $messageObj->to_user_id = $input['to_user_id'];
        $messageObj->message_type = $input['message_type'];
        $messageObj->is_read = 0;
        $messageObj->image = (isset($input['image'])) ? $input['image'] : '';
        if ($messageObj->save()) {
            return ["success" => true, "message" => "", "data" => $messageObj];
        }
        return ["success" => false, "message" => "Unable to send message"];
    }
    
    public function getLastMessage($message_head_id,$limit){
        $messageObj = new Message;
        return $messageObj->where('message_head_id',$message_head_id)->orderBy('message_id', 'desc')->first();
    }
    
    public function getMessage($from_user_id,$to_user_id) {
        $messageHeadObj = new MessageHead();
        $messageHeadObjData = $messageHeadObj->isAlreadyExists($from_user_id,$to_user_id);
        if(empty($messageHeadObjData)){
           return false; 
        }
        $messageObj = new Message();
        return $messageObj->select('*',\DB::raw('IF(image = "","",CONCAT("/assets/images/message/", image)) AS image'),\DB::raw('unix_timestamp(created_at) as _created_at'))->where('message_head_id',$messageHeadObjData->message_head_id)->orderBy('message_id', 'ASC')->get();
    }
    
    /*
    public function getAllList($search = '') {
        $q = $this->orderBy('id', 'desc');
        if (!empty($search)) {
            $q = $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('name_hi', 'LIKE', '%' . $search . '%');
        }
        return $q->simplePaginate();
    }
    
    public function getDropDownData() {
        return $this->get()->pluck('name', 'id')->toArray();
    }

    public function changeStatus($input) {
        $religionObj = new Religion();
        $religionObjData = $religionObj->getById($input['id']);
        $active_status = $religionObjData->status;
        if ($active_status == 1) {
            $religionObjData->status = 0; $type = 3;
        } else {
            $religionObjData->status = 1; $type = 1;
        }
        if ($religionObjData->update()) {
            return ["success" => true, "message" => "Status changed", "type" => $type];
        }
        return ["success" => false, "message" => "Status unchange", "type" => $active_status];
    }
    
    public function add($input) {
        $validator = Validator::make($input, [
            'name' => 'required',
            'name_hindi' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors()), 422);
        }
        $religionObj = new Religion;
        $religionObj->name = $input['name'];
        $religionObj->name_hi = $input['name_hindi'];
        if ($religionObj->save()) {
            return ["success" => true, "message" => "Religion Added Successfully", "reset" => true];
        }
        return ["success" => false, "message" => "Unable to add Religion"];
    }
    
    public function edit($input,$id) {
        $validator = Validator::make($input, [
            'name' => 'required',
            'name_hindi' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors()), 422);
        }
        $religionObj = new Religion;
        $religionObjData = $religionObj->getById($id);
        $religionObjData->name = $input['name'];
        $religionObjData->name_hi = $input['name_hindi'];
        if ($religionObjData->save()) {
            return ["success" => true, "message" => "Religion Updated Successfully"];
        }
        return ["success" => false, "message" => "Unable to Update Religion"];
    }*/
}
