<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Interest;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommonQuery;
class UserController extends Controller
{

    public function __construct()
    {
        # code...
        $this->middleware('auth:admin');
       // $this->middleware('super_admin_role');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

         $query = CommonQuery::getMainQuery();

         session()->forget('string');
         if(session()->has('gender'))
                   $query->where('gender','=', session()->get('gender'));
       if($request->input('user_search')){
             $string=$request->input('user_search');
             session()->put('string',$string);
             
          $users =  $query->where('email','LIKE','%'.$string.'%')
                          ->orWhere('users.name', 'LIKE','%'.$string.'%')
                          ->orWhere('mobile','=',$string)
                          ->orderBy('users.id','desc')
                          ->simplePaginate();
         }

        else {
            $users=$query->orderBy('users.id','desc')->simplePaginate();
        }
       return view('admin.users',compact('users'));
    }


    public function setGender(Request $request)
    {
        $gender = $request->gender;
        if(!is_null($gender) && $gender!="")
             session()->put('gender',$gender);
        else session()->forget('gender');

        return redirect('admin/users_search_result');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                   'name'=>'required|min:3',
                   'mobile'=>'required|numeric',
                   'email'=>'required|email|unique:Users,email'

    ]);

        $user= new User;

        $user->name= $request->name;
        $user->mobile= $request->mobile;
        $user->email= $request->email;
        $user->company= $request->company;
        
        if($user->save()){

            return ["success"=>true, "message"=>"User Added Successfully" ];
        }
       
       return ["success"=>false, "message"=>"Unable to add user" ];

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    $user = CommonQuery::getMainQuery()
                     ->where(['users.id' => $id])
                     ->first();
    //$user = User::find($request->user_id);
$id_proof = $user->getMedia('identity');
//dd($id_proof->toArray());
        return view('admin.response.userdetails',compact('user','id_proof'));              

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
         $user=User::find($request->id);
                 session()->put('edit_user_id', $user->id);
     return view('admin.response.edituser',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $id = session('edit_user_id');
         $this->validate($request,[
                   'name'=>'required|min:3',
                   'mobile'=>'required|numeric',
                   'email'=>'required|email|unique:users,email,'.$id

    ]);

        
        $user= User::find($id);

        $user->name= $request->name;
        $user->mobile= $request->mobile;
        $user->email= $request->email;
        $user->company= $request->company;
        
        if($user->save()){

            return ["success"=>true, "message"=>"User Updated Successfully" ];
        }
       
       return ["success"=>false, "message"=>"Unable to Update user" ];

        //
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
       if(User::destroy($id)){

        return back()->with('dealer_msg',"User Successfully Deleted");

       }
     
     else {

        return back()->with('dealer_msg',"Unable to Deleted Dealer");
     }
       

    }

    public function user_search(Request $request)
    {
        
      $string=$request->term;
        $user=User::where('name','LIKE',"%".$string."%")
                 ->orWhere('email','LIKE',"%".$string."%")
                 ->limit(15)
                 ->get();
          
        foreach ($user as $key => $user) {
            # code...
            $data[]=$user->name;
        }

        return $data;

    }

    public function setStatus(Request $request)
    {
        $id = $request->id;
       $user = User::find($id); 
        # code...
        $active_status =$user->is_active;
        if($active_status == "1")
        {
            $user->is_active = 3;   
        }
        if($active_status == "3")
        {
            $user->is_active = 1;   
        }
        if($user->save()){

            return ["success"=>true, "message"=>"Status changed", "type" =>$user->is_active  ]; 
        } 
            return ["success"=>false, "message"=>"Status unchange", "type" =>$active_status  ];
    }


    public function setRequestStatus(Request $request)
    {
        $id = $request->id;
       $Interest = Interest::find($id); 
        # code...
      if($Interest->status == "0")
        {
            $Interest->status = 2;   
        }
       else if($Interest->status == "2")
        {
            $Interest->status = 0;   
        }
       
        if($Interest->save()){

            return ["success"=>true, "message"=>"Status changed", "status" =>$Interest->status  ]; 
        } 
            return ["success"=>false, "message"=>"Status unchange", "status" =>$Interest->status  ];
    }



    public function userInterests()
    {
   
        # code...
          $requests=Interest::where('status','0')
                             ->orderBy('id','desc')   
                             ->simplePaginate();
                             $interest_id = $user =[];

        foreach ($requests as $key => $value) {
            $user[] = CommonQuery::getMainQuery()->whereIn('users.id',[$value->user_id, $value->requested_id])->orderByRaw(\DB::raw("FIELD(users.id, $value->user_id, $value->requested_id )"))->get();
            $interest_id[] = $value->id;
            
        }
                return view('admin.requests',compact('user','requests','interest_id'));

    }

/* change password */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                   'id'=>'required',
                   "password" => 'required|min:6'
     ]);
          
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors()), 422);
        }

       $user = User::find($request->id);
       $user->password = $request->password;
      if($user->save()){

            return ["success"=>true, "message"=>"Password Updated" ];
        }
       
       return ["success"=>false, "message"=>"Update Failed" ];
    }



}