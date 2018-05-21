<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return EventsResource
     */
    public function index()
    {
        return new EventsResource(Event::with(['teacher.user'])->paginate());
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
        //
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
