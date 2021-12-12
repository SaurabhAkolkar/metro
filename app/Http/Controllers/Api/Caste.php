<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class Caste extends Controller
{
    //
    public function __construct()
    {
     # code...
        //$this->middleware('CORS');
    }

    public function get_caste()
    {
        $caste= DB::table('caste')->get();
        return Response::pass('Get all castes.', $caste);
    }
}