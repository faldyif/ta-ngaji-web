<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRelationshipController extends Controller
{
    public function teacher(User $user) {
        TeacherRes::withoutWrapping();
        return new UserResource($user);
    }
}
