<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Event;
use App\Http\Resources\EventsResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexUnconfirmed()
    {
        $user = Auth::user();
        $event = Event::where('teacher_id', $user->teacherRegistery->id)->whereDate('end_time', '>=', Carbon::now())->where('accepted', null)->get();

        return new EventsResource($event);
    }

    public function countUnconfirmed()
    {
        $user = Auth::user();
        $event = Event::where('teacher_id', $user->teacherRegistery->id)->whereDate('end_time', '>=', Carbon::now())->where('accepted', null)->count();

        return $event;
    }

    public function indexUpcoming()
    {
        $user = Auth::user();
        $event = Event::where('teacher_id', $user->teacherRegistery->id)->whereDate('end_time', '>=', Carbon::now())->where('accepted', 1)->get();

        return new EventsResource($event);
    }

    public function changeStatus(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->accepted = $request->status;
        $event->save();

        return response()->json([],204);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }
}
