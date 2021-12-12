<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StateDistrict extends Controller
{
    //
    public function __construct()
    {
     # code...
        //$this->middleware('CORS');
    }

    public function get_states()
    {
        return DB::table('states')->get();
    }

    public function get_district(Request $request)
    {
        $request->validate([
            'state_id' => 'required|integer'
        ]);
        $state_id = $request->state_id;
        return DB::table('districts')->where(['state_id' => $state_id])->get();
    }


}
