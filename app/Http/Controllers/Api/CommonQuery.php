<?php

namespace App\Http\Controllers\Api;
use App\User;
use Illuminate\Http\Request;
class CommonQuery
{

public static function getMainQuery()
{
    # code...
    // $lang = null;
    // if(@$_REQUEST['lang'] ==='hi') $lang = '_hi';

    return User::join('heights as h', 'users.height', 'h.id')
                       ->join('income as i', 'users.income', 'i.id')
                       ->join('occupation as o', 'users.occupation', 'o.id')
                       ->join('blood_group as bg', 'users.blood_group', 'bg.id')
                       ->join('manglik as m', 'users.manglik', 'm.id')
                       ->join('marital_status as ms', 'users.marital_status', 'ms.id')
                       ->join('states as s', 'users.state', 's.id')
                       ->join('districts as d', 'users.district', 'd.id')
                       ->join('caste as c', 'users.caste', 'c.id')
                       ->select('users.*',
                       'h.height as height',
                       'i.income as income',
                       'bg.blood_group as blood_group',
                       'm.manglik_type as manglik',
                       'm.manglik_type_hi as manglik_hi',
                       'ms.marital_status as marital_status',
                       'ms.marital_status_hi as marital_status_hi',
                       'o.occupation as occupation',
                       'o.occupation_hi as occupation_hi',
                       "s.name as state",
                       "s.name_hi as state_hi",
                       "d.name as district" ,
                       "c.name as caste" ,
                       "d.name_hi as district_hi" 
                    );
}


public static function addParameter($user_data = null, $sid = [], $interest = [])
{
   
  
     foreach ($user_data as $key => $value) {
            if(in_array($value->id,$sid)) $value->shortlisted = 1;
            if($value->lang ==="hi"){ 
                $value->state = $value->state_hi;
                $value->district = $value->district_hi; 
                $value->occupation = $value->occupation_hi; 
                $value->marital_status = $value->marital_status_hi;
                $value->manglik = $value->manglik_hi;
                $value->caste = $value->caste_hi;

            }

             $value->request = 0;
            $value ->status = 0;
            
             if(!isset($interest['sent'])) $interest['sent']=[];
       if(!isset($interest['recieved'])) $interest['recieved']=[];
       
       /* 1 for request send */
            if(in_array($value->id,$interest['sent'])){ 
        $value->request = "1";
        $value ->status = $interest['status'][$value->id];
       }

       /* 2 for request recieved */
       if(in_array($value->id,$interest['recieved'])){ 
        $value->request = "2";
        $value ->status = $interest['status'][$value->id];
       }
        }

    return $user_data;
}

}