<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;

class LoginController extends Controller
{

    use IssueTokenTrait;

    private $client;

    public function __construct()
    {
        $this->client = Client::find(2);
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $userAttempt = Auth::attempt(['email' => $request->username, 'password' => $request->password]);

        if ($userAttempt) {
            $user = User::where('email', $request->username)->first();

            if ($user->verified) {
                return $this->issueToken($request, 'password');
            } else {
                return array(
                    'message' => 'Email anda belum dikonfirmasi',
                    'errors' => [
                        'unvalidated' => ['Anda harus mengkonfirmasi email anda terlebih dahulu sebelum menggunakan aplikasi']
                    ]
                );
            }
        }


    }

    public function refresh(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        return $this->issueToken($request, 'refresh_token');

    }

    public function logout(Request $request)
    {

        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json([], 204);

    }
}