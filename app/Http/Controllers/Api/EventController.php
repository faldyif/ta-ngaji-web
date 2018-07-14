<?php

namespace App\Http\Controllers\Api;

use App\AttendeeLog;
use App\Event;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventsResource;
use App\TeacherFreeTime;
use App\User;
use App\UserFirebaseToken;
use Carbon\Carbon;
use Fcm\Exception\FcmClientException;
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
            'short_place_name' => 'required',
            'location_details' => '',
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
        }
        $event = new Event;
        $event->event_type = $event_type;
        $event->teacher_free_time_id = $request->teacher_free_time_id;
        $event->teacher_id = $teacher_free_time->teacher->id;

        $event->student_id = Auth::user()->id;
        $event->short_place_name = $request->short_place_name;
        $event->latitude = $request->latitude;
        $event->longitude = $request->longitude;
        $event->location_details = $request->location_details;
        $event->start_time = $request->time_start;
        $event->end_time = $request->time_end;
        $event->save();

        if($event->teacher->level->points < 0) {
            $attendeeLog = new AttendeeLog;
            $attendeeLog->event_id = $event->id;
            $attendeeLog->student_user_id = $event->student->id;
            $attendeeLog->unique_code = str_random(32);
            $attendeeLog->points_earned = $event->teacher->level->points;
            $attendeeLog->bonus_points = 0;
            $attendeeLog->save();

            $student = Auth::user();
            $student->loyalty_points += $attendeeLog->points_earned;
            $student->save();
        }

        // Send firebase notification
        $serverKey = "AAAAN9Oe-fA:APA91bGycXlRov00S8WQ2jy-p7atbrz7C8-v5mTP1rrf7lh5fz7-jwaz0PhoXlfcoY6rv9o6zooSGKCNPrsOUY_hE6mCBTzTrUwYI9BOkRmkLKN0xyN4Z_FBsbiPl85DY9AbdHI9GtES";
        $senderId = "239773612528";

        $client = new \Fcm\FcmClient($serverKey, $senderId);
        $responses = [];

        $user = User::find($event->teacher->user->id);
        $firebaseTokens = UserFirebaseToken::where('user_id', $user->id)->get();
        foreach ($firebaseTokens as $key) {
            $notification = new \Fcm\Push\Notification();
            $notification
                ->setTitle('Pengajuan Ngaji Baru')
                ->setBody('Halo '.$user->name.', '.$event->student->name.' melakukan pengajuan jadwal ngaji baru!')
                ->addRecipient($key->firebase_token);

            try {
                $response = $client->send($notification);
            } catch (FcmClientException $e) {
            }

            if(isset($response['results'][0]['error'])) {
                $error = $response['results'][0]['error'];
                if($error == "NotRegistered" || $error == "InvalidRegistration") {
                    try {
                        $key->delete();
                    } catch (\Exception $e) {
                    }
                }

            }

            $responses[] = $response;
        }

        return response()->json([], 204);
    }

    public function showMinus2Hours()
    {
        // TODO: Ini bahaya bahaya
        $user = Auth::user();
        $event = Event::with(['teacher.user'])
            ->where(function ($qu) use ($user) {
                $qu->where(function ($q) use ($user) {
                    $q->where('accepted', 1)
                        ->where('student_id', $user->id);
                })->orWhere(function ($q) use ($user) {
                    $q->where('accepted', 1)
                        ->where('teacher_id', $user->linked_id);
                });
            })
            ->orderBy('start_time')
            ->get();

        return new EventsResource($event);
    }

    public function attend(Request $request)
    {
        $event = Event::find($request->event_id);
        $attendeeLog = AttendeeLog::where('unique_code', $request->unique_code)->first();
        $attendeeLog->check_in_time = Carbon::now();
        $attendeeLog->points_earned = $event->teacher->level->points;
        $attendeeLog->save();

        if($attendeeLog->points_earned > 0) {
            $student = $event->student;
            $student->loyalty_points += $attendeeLog->points_earned;
            $student->save();
        }

        return response()->json([],204);
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
