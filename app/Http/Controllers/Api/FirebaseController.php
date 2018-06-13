<?php

namespace App\Http\Controllers\Api;

use App\UserFirebaseToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FirebaseController extends Controller
{
    public function updateFirebaseToken(Request $request)
    {
        $this->validate($request, [
            'firebase_token' => 'required'
        ]);

        $user = Auth::user();
        UserFirebaseToken::updateOrCreate(
            ['firebase_token' => $request->firebase_token],
            ['user_id' => $user->id]
        );

        return response()->json([], 204);
    }
}
