<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Seshac\Otp\Otp;

class ApiController extends Controller
{
    
    public function sendOtp(Request $req)
    {
        return Otp::generate($req->mobile_number);
    }
}
