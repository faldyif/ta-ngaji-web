<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\TeacherFreeTimesResource;
use App\TeacherFreeTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherFreeTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TeacherFreeTimesResource
     */
    public function index()
    {
        $user = Auth::user();
        $teacherFreeTime = $user->teacherRegistery->teacherFreeTimes;
        $teacherFreeTime = $teacherFreeTime->where('end_time', '>=', Carbon::now())->sortBy('start_time');

        return new TeacherFreeTimesResource($teacherFreeTime);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'short_place_name' => 'required',
            'latitude' => 'required', // TODO: ini validasi
            'longitude' => 'required', // TODO: ini validasi
            'time_start' => 'required|date_format:Y-m-d H:i:s',
            'time_end' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $teacherFreeTimes = TeacherFreeTime::onlyUser(Auth::user()->teacherRegistery->id)
            ->dateTimeUnion($request->time_start, $request->time_end)
            ->orDateTimeSubset($request->time_start, $request->time_end)
            ->get();

//        return response()->json($teacherFreeTimes);

        if($teacherFreeTimes->count() > 0) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'time_start' => ['Waktu yang anda tentukan bertabrakan dengan waktu luang yang sebelumnya sudah anda buat di database.'],
                'time_end' => ['Waktu yang anda tentukan bertabrakan dengan waktu luang yang sebelumnya sudah anda buat di database.'],
            ]);
            throw $error;
        }

        $teacher_free_time = new TeacherFreeTime;
        $teacher_free_time->teacher_id = Auth::user()->teacherRegistery->id;
        $teacher_free_time->short_place_name = $request->short_place_name;
        $teacher_free_time->latitude = $request->latitude;

        $teacher_free_time->longitude = $request->longitude;
        $teacher_free_time->start_time = $request->time_start;
        $teacher_free_time->end_time = $request->time_end;
        $teacher_free_time->save();

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
