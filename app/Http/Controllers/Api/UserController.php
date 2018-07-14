<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SelfUserResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        //
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'whatsapp_number' => 'required',
        ]);

        $user = Auth::user();
        if(User::where('whatsapp_number', $request->whatsapp_number)->whereNotIn('id', [$user->id])->count() > 0) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'whatsapp_number' => ['Nomor whatsapp yang anda masukkan sudah dipakai.'],
            ]);
            throw $error;
        }

        if($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $destinationPath = 'public/temp';
            $extension = $request->photo->extension();
            $fileName = date('YmdHms').'_'.Auth::user()->id.'.'.$extension;
            $request->photo->storeAs($destinationPath, $fileName);
            $user->profile_pic_path = $fileName;
        }
        $user->name = $request->name;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->save();

        return response()->json([],204);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        UserResource::withoutWrapping();

        return new UserResource($user);
    }

    public function showOwnProfile() {
        SelfUserResource::withoutWrapping();

        $user = Auth::user();

        return new SelfUserResource(Auth::user());
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
