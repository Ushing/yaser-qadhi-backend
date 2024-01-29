<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEvent()
    {
        $event = Event::where('status',1)->get();
        return response()->json($event, 200);
    }
}
