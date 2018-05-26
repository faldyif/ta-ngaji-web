<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TeacherFreeTimeResource;
use App\Http\Resources\TeacherFreeTimesResource;
use App\TeacherFreeTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherFreeTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TeacherFreeTimesResource
     */
    public function index()
    {
        $teacherFreeTimes = TeacherFreeTime::with('teacher')->get();
        return new TeacherFreeTimesResource($teacherFreeTimes);
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
