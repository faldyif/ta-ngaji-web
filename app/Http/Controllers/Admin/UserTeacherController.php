<?php

namespace App\Http\Controllers\Admin;

use App\TeacherRegistery;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userTeacher = User::where('role_id', 2)->get();
        return view('admin.teacher.index', compact('userTeacher'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teacher.create');
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
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'whatsapp_number' => 'required',
            'gender' => 'required',
            'teacher_level' => 'required',
            'teacher_competence' => 'required',
            'profile_pic' => 'required|file',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // secret
            'whatsapp_number' => '+62‬'.$request->whatsapp_number,
            'gender' => $request->gender,
            'role_id' => 2,
            'verified' => true,
        ]);
        $teacherRegistery = TeacherRegistery::create([
            'user_id' => $user->id,
            'teacher_level_id' => $request->teacher_level,
            'registered_from' => Carbon::now(),
            'teacher_competence' => $request->teacher_competence,
        ]);

        $image = $request->file('profile_pic');
        $filename = 'image_'.time().'_'.$image->hashName();
        $image->move(public_path('storage/temp'), $filename);

        $user->profile_pic_path = $filename;
        $user->linked_id = $teacherRegistery->id;
        $user->role_id = 2;
        $user->save();

        Session::flash('message', 'Guru berhasil dibuat!');
        return redirect(route('admin.teacher.index'));
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
