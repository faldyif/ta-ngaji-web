<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventsResource;
use App\TeacherFreeTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return EventsResource
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'active' => 'required|boolean'
        ]);

        if($request->active) {
            $user = Auth::user();
            $event = Event::with(['teacher.user'])
                ->where('student_id', $user->id)
                ->whereDate('end_time', '>', Carbon::now())
                ->get();
            return new EventsResource($event);
        } else {
            $user = Auth::user();
            $event = Event::with(['teacher.user'])
                ->where('student_id', $user->id)
                ->whereDate('end_time', '<', Carbon::now())
                ->get();
            return new EventsResource($event);
        }
    }

    public function indexHistory(Request $request)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $user = Auth::user();
        $event = Event::query();
        $event->with(['teacher.user']);
        $event->where('student_id', $user->id);
        $event->whereDate('end_time', '>', Carbon::now());

        if($request->status === "pending") {
            $event->where('accepted', null);
        } else if($request->status === "rejected") {
            $event->where('accepted', 0);
        } else if($request->status === "accepted") {
            $event->where('accepted', 1);
        }

        return new EventsResource($event->get());
    }

    public function indexFiltered(Request $request)
    {
        $this->validate($request, [
            'time_start' => 'required|date_format:Y-m-d H:i:s',
            'time_end' => 'required|date_format:Y-m-d H:i:s',
            'latitude' => 'required', // TODO: ini validasi
            'longitude' => 'required', // TODO: ini validasi
            'max_points' => 'required|integer',
            'event_type' => 'required'
        ]);

        $eventFiltered = Event::with(['teacher.user'])
            ->timeBetween($request->time_start, $request->time_end)
            ->isWithinMaxDistance($request->latitude, $request->longitude)
            ->maxPoints($request->max_points);

        switch ($request->event_type) {
            case 'tahsin':
                $eventFiltered = $eventFiltered->tahsin();
                break;
            case 'tahfidz':
                $eventFiltered = $eventFiltered->tahfidz();
                break;
            case 'tadabbur':
                $eventFiltered = $eventFiltered->tadabbur();
                break;
            default:
                break;
        }

        return new EventsResource($eventFiltered->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event_type' => 'required',
            'teacher_free_time_id' => 'required',
            'short_place_name' => '',
            'location_details' => 'required',
            'latitude' => 'required', // TODO: ini validasi
            'longitude' => 'required', // TODO: ini validasi
            'time_start' => 'required|date_format:Y-m-d H:i:s',
            'time_end' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $teacher_free_time = TeacherFreeTime::find($request->teacher_free_time_id);

        $event_type = null;
        if($request->event_type == "tahsin") {
            $event_type = 1;
        } else if($request->event_type == "tahfidz") {
            $event_type = 2;
        } else if($request->event_type == "tadabbur") {
            $event_type = 3;
        }
        $event = new Event;
        $event->event_type = $event_type;
        $event->teacher_id = $teacher_free_time->teacher->id;
        $event->student_id = Auth::user()->id;
        $event->short_place_name = $request->short_place_name;
        $event->latitude = $request->latitude;
        $event->longitude = $request->longitude;
        $event->location_details = $request->location_details;
        $event->start_time = $request->time_start;
        $event->end_time = $request->time_end;
        $event->save();

        return response()->json([], 204);
    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     * @return EventResource
     */
    public function show(Event $event)
    {
        EventResource::withoutWrapping();

        return new EventResource($event);
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
