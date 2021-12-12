<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Interest;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
       // $this->middleware('admin_role');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
$maleCount = User::where('gender', 'M')->count();
$femaleCount = User::where('gender', 'F')->count();
$total = User::select(\DB::raw("count(case when is_active = 1 then id end) as total_active,
count(case when (is_active = 0 or is_active = 3) then id end) as total_inactive"))->first();

$interest = Interest::where('status','0')->count();
$accepted = Interest::where('status','>','0')->count();
$graph = User::select(\DB::raw("count(case when gender = 'M' then id end) as male,
count(case when gender = 'F' then id end) as female,
DATE(created_at) as date"))->limit(31)->groupBy("date")->get();
// dd($graph->toArray());
        return view('admin.dashboard',compact('maleCount','femaleCount','interest','accepted','graph','total'));
    }
}
