<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;

class EventsController extends Controller
{
    //
    public function getEvents()
    {
       $events = Event::orderBy('id','desc')->get();
       if($events->count() > 0)
       return Response::pass('Data Available',$events);
       return Response::fail('Data not Available');
    }
}
