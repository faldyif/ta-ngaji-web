<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Controllers\Controller;
use App\User;
use App\UserFirebaseToken;
use Carbon\Carbon;
use Fcm\Exception\FcmClientException;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public function test() {
        $serverKey = "AAAAN9Oe-fA:APA91bGycXlRov00S8WQ2jy-p7atbrz7C8-v5mTP1rrf7lh5fz7-jwaz0PhoXlfcoY6rv9o6zooSGKCNPrsOUY_hE6mCBTzTrUwYI9BOkRmkLKN0xyN4Z_FBsbiPl85DY9AbdHI9GtES";
        $senderId = "239773612528";

        $client = new \Fcm\FcmClient($serverKey, $senderId);
        $responses = [];

        $firebaseTokens = UserFirebaseToken::all();
        foreach ($firebaseTokens as $key) {
            $user = User::find($key->user_id);
            $notification = new \Fcm\Push\Notification();
            $notification
                ->setTitle('Testing notification')
                ->setBody('Hello '.$user->name.', this message sent from php-fcm!')
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
//            return $response->results;
//            if($response->results->error == "NotRegistered" || $response->results->error == "InvalidRegistration") {
//                $key->delete();
//            }

            $responses[] = $response;
        }
        return $responses;
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
