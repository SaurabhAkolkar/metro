<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class MaritalStatus extends Controller
{
    //
    public function __construct()
    {
     # code...
        //$this->middleware('CORS');
    }

    public function get_marital_status()
    {
        $marital_status= DB::table('marital_status')->get();
        return Response::pass('Get all marital status.', $marital_status);
    }
}