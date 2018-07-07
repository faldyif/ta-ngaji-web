<?php

namespace App\Http\Controllers\Api\Teacher;

use App\AttendeeLog;
use App\Event;
use App\Http\Resources\EventsResource;
use App\User;
use App\UserFirebaseToken;
use Carbon\Carbon;
use Fcm\Exception\FcmClientException;
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

        $agenda = "";
        $agenda2 = "";
        if($request->status == 1) {
            $agenda = "Diterima";
            $agenda2 = "menerima";

            $attendeeLog = new AttendeeLog;
            $attendeeLog->event_id = $event->id;
            $attendeeLog->student_user_id = $event->student->id;
            $attendeeLog->unique_code = str_random(32);
            if($event->teacher->level->points < 0) {
                $attendeeLog->points_earned = $event->teacher->level->points;
            } else {
                $attendeeLog->points_earned = 0;
            }
            $attendeeLog->bonus_points = 0;
            $attendeeLog->save();
        } else {
            $agenda = "Ditolak";
            $agenda2 = "menolak";
        }

        // Send firebase notification
        $serverKey = "AAAAN9Oe-fA:APA91bGycXlRov00S8WQ2jy-p7atbrz7C8-v5mTP1rrf7lh5fz7-jwaz0PhoXlfcoY6rv9o6zooSGKCNPrsOUY_hE6mCBTzTrUwYI9BOkRmkLKN0xyN4Z_FBsbiPl85DY9AbdHI9GtES";
        $senderId = "239773612528";

        $client = new \Fcm\FcmClient($serverKey, $senderId);
        $responses = [];

        $user = $event->student;
        $firebaseTokens = UserFirebaseToken::where('user_id', $user->id)->get();
        foreach ($firebaseTokens as $key) {
            $notification = new \Fcm\Push\Notification();
            $notification
                ->setTitle('Agenda Ngaji ' . $agenda)
                ->setBody('Halo '.$user->name.', '.$event->teacher->name.' telah '. $agenda2 .' pengajuan jadwal ngaji anda!')
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
