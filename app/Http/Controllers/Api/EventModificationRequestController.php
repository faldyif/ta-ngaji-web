<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\EventModificationRequest;
use App\User;
use App\UserFirebaseToken;
use Carbon\Carbon;
use Fcm\Exception\FcmClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventModificationRequestController extends Controller
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
        $this->validate($request, [
            'event_id' => 'required',
            'time_start' => 'date_format:Y-m-d H:i:s',
            'time_end' => 'date_format:Y-m-d H:i:s',
            'request_reason' => 'required',
        ]);

        $eventModificationRequest = new EventModificationRequest;
        $eventModificationRequest->event_id = $request->event_id;
        $eventModificationRequest->start_time = $request->time_start;
        $eventModificationRequest->end_time = $request->time_end;
        $eventModificationRequest->request_by_teacher = false;
        $eventModificationRequest->approved = -1;
        $eventModificationRequest->request_reason = $request->request_reason;
        $eventModificationRequest->save();

        // Send firebase notification
        $serverKey = "AAAAN9Oe-fA:APA91bGycXlRov00S8WQ2jy-p7atbrz7C8-v5mTP1rrf7lh5fz7-jwaz0PhoXlfcoY6rv9o6zooSGKCNPrsOUY_hE6mCBTzTrUwYI9BOkRmkLKN0xyN4Z_FBsbiPl85DY9AbdHI9GtES";
        $senderId = "239773612528";

        $client = new \Fcm\FcmClient($serverKey, $senderId);
        $responses = [];

        $user = User::find(Event::find($request->event_id)->teacher->id);
        $firebaseTokens = UserFirebaseToken::where('user_id', $user->id)->get();
        foreach ($firebaseTokens as $key) {
            $notification = new \Fcm\Push\Notification();
            $notification
                ->setTitle('Permintaan Modifikasi Jadwal')
                ->setBody('Halo '.$user->name.', '.Event::find($request->event_id)->student->name.' telah mengajukan permintaan perubahan jadwal ngaji anda!')
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

    public function respondRequest(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'status' => 'required'
        ]);

        $event = Event::find($request->event_id);
        $eventModificationRequest = $event->eventModificationRequests->where('approved', -1)->first();
        $eventModificationRequest->approved = $request->status;
        $eventModificationRequest->approval_datetime = Carbon::now();
        $eventModificationRequest->approval_reason = $request->reason;
        $eventModificationRequest->save();

        $agenda = "";
        $agenda2 = "";
        if($request->status == 1) {
            $agenda = "Diterima";
            $agenda2 = "menerima";

            $event->start_time = $eventModificationRequest->start_time;
            $event->end_time = $eventModificationRequest->end_time;
            $event->save();
        } else {
            $agenda = "Ditolak";
            $agenda2 = "menolak";
        }

        // Send firebase notification
        $serverKey = "AAAAN9Oe-fA:APA91bGycXlRov00S8WQ2jy-p7atbrz7C8-v5mTP1rrf7lh5fz7-jwaz0PhoXlfcoY6rv9o6zooSGKCNPrsOUY_hE6mCBTzTrUwYI9BOkRmkLKN0xyN4Z_FBsbiPl85DY9AbdHI9GtES";
        $senderId = "239773612528";

        $client = new \Fcm\FcmClient($serverKey, $senderId);
        $responses = [];

        $user = User::find(Event::find($request->event_id)->teacher->id);
        $firebaseTokens = UserFirebaseToken::where('user_id', $user->id)->get();
        foreach ($firebaseTokens as $key) {
            $notification = new \Fcm\Push\Notification();
            $notification
                ->setTitle('Permintaan Modifikasi Jadwal '.$agenda)
                ->setBody('Halo '.$user->name.', '.$event->student->name.' telah '. $agenda2 .' pengajuan jadwal ngaji anda!')
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
