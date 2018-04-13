<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Jrean\UserVerification\Facades\UserVerification;
use Laravel\Passport\Client;

class RegisterController extends Controller
{
    use IssueTokenTrait;

    private $client;

    public function __construct()
    {
        $this->client = Client::find(2);
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);


        UserVerification::generate($user);

        UserVerification::send($user, 'Verifikasi Email NgajiTA');

        $response = array(
            'success' => true,
            'message' => 'Silahkan cek kotak masuk email anda dan klik pada link yang terdapat didalam email tersebut untuk memverifikasi kepemilikan email anda'
        );

        return $response;

    }
}