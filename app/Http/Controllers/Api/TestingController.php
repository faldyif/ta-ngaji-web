<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function test() {

    }


    public function testTime() {
        $startTime = Carbon::now();
        $endTime = Carbon::now();

        $startTime->subMinutes(5);
        $endTime->addMinutes(5);

        $event = Event::timeBetween($startTime, $endTime)->get();

        return $event;
    }
}
