<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventsResource;
use App\Http\Resources\TeacherFreeTimesResource;
use App\TeacherFreeTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherFreeTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TeacherFreeTimesResource
     */
    public function index()
    {
        $teacherFreeTimes = TeacherFreeTime::with('teacher')->get();
        return new TeacherFreeTimesResource($teacherFreeTimes);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return TeacherFreeTimesResource
     */
    public function indexFiltered(Request $request)
    {
        $this->validate($request, [
            'time_start' => 'required|date_format:Y-m-d H:i:s',
            'time_end' => 'required|date_format:Y-m-d H:i:s',
            'latitude' => 'required', // TODO: ini validasi
            'longitude' => 'required', // TODO: ini validasi
            'event_type' => 'required'
        ]);

        $eventType = $request->event_type;
        $competence = [];
        if($eventType == 'tahsin') {
            array_push($competence, 1);
            array_push($competence, 3);
        } else if($eventType == 'tahfidz') {
            array_push($competence, 2);
            array_push($competence, 3);
        }

        $user = Auth::user();
        $teacherFreeTimes = null;
        if(Auth::user()->role_id == 2) {
            $teacherFreeTimes = TeacherFreeTime::with(['teacher.level', 'events'])
                ->whereHas('teacher.level', function ($query) use ($user) {
                    $query->pointEnough($user->loyalty_points);
                })
                ->whereHas('teacher.user', function ($query) use ($user) {
                    $query->where('gender', $user->gender);
                })
                ->whereDoesntHave('events', function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->time_start, $request->time_end]);
                    $query->orWhereBetween('end_time', [$request->time_start, $request->time_end]);
                    $query->whereIn('accepted', [1, null]);
                })
                ->timeBetween($request->time_start, $request->time_end)
                ->isWithinMaxDistance($request->latitude, $request->longitude)
                ->excludeUser(Auth::user()->teacherRegistery->id)
                ->get();
        } else {
            $teacherFreeTimes = TeacherFreeTime::with(['teacher.level', 'events'])
                ->whereHas('teacher.level', function ($query) use ($user) {
                    $query->pointEnough($user->loyalty_points);
                })
                ->whereHas('teacher.user', function ($query) use ($user) {
                    $query->where('gender', $user->gender);
                })
                ->whereDoesntHave('events', function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->time_start, $request->time_end]);
                    $query->orWhereBetween('end_time', [$request->time_start, $request->time_end]);
                    $query->whereIn('accepted', [1, null]);
                })
                ->timeBetween($request->time_start, $request->time_end)
                ->isWithinMaxDistance($request->latitude, $request->longitude)
                ->get();
        }

        return new TeacherFreeTimesResource($teacherFreeTimes);
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
