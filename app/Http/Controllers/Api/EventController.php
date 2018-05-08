<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    // Show all events regardless of place
    public function index() {
        $events = Event::with(['teacher' => function ($q) {
            $q->select('id', 'user_id', 'minimum_points');
        }, 'teacher.user'])->get();
        return response()->json($events);
    }
}