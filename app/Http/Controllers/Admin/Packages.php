<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Package;
use DB;
class Packages extends Controller
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
       $packages= DB::table('packages')->get();
        
       return view('admin.packages',compact('packages'));
    }

    public function addPackages(Request $request)
    {

     $validator = Validator::make($request->all(), [
                   'title'=>'required|min:3',
                   'description'=>'required|min:30',
                   "price" => 'required',
                   "type" => 'required'
     ]);
          
        if ($validator->fails()) 
        {
         return response()->json(array('errors' => $validator->errors()), 422);
        }

     $data = [
         'price' => $request->price,
         'title' => $request->title,
         'subscription_type' => $request->type,
         'description' => $request->description
     ];

        DB::table('packages')->insert($data);
        return ["success"=>true, "message"=>"Package Added Successfully" ];
    }

    public function deletePackages($id = 0)
    {
        DB::table('packages')->where('id', $id)->delete();
    return redirect('admin/packages')->with('packages-msg',"Package Successfully Deleted");
    }

}
