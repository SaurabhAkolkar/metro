<?php

namespace App\Http\Controllers\UserAccountActivation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
class ActivateAccount extends Controller
{
public function activate(Request $request){

        $email = $request->email;
        $token = $request->token;
        if($token===null || $email === null || $token==="" || $email === "") dd("Invalid url"); 
        $user = \App\User::where('email',$email)->first();
       if ($user === null) {
   dd("User not found");
}
        if($user->verify_token === $token) {    
            $user->is_active=1;
            $user->verify_token=null;
            if($user->save()) dd("Activation Successfull");
        }
        dd("Link Expired");
    }
}
