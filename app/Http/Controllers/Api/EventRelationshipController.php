<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;

class EventRelationshipController extends Controller
{
    public function teacher(Event $event) {
        $user = $event->user();
        UserResource::withoutWrapping();
        return new UserResource($user);
    }
}
