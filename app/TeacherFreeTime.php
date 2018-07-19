<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TeacherFreeTime extends Model
{
    public function getUserAttribute() {
        return $this->teacher->user;
    }

    public function teacher() {
        return $this->belongsTo('App\TeacherRegistery', 'teacher_id');
    }

    public function events() {
        return $this->hasMany('App\Event');
    }

    public function scopeTimeInside($query, $startTime, $endTime)
    {
        return $query->where('start_time', '>=', $startTime)->where('end_time', '<=', $endTime);
    }

    public function scopeTimeBetween($query, $startTime, $endTime)
    {
        return $query->where('start_time', '<=', $startTime)->where('end_time', '>=', $endTime);
    }

    // To count max distance (in kilometre), ordered by the shortest distance from current location
    public function scopeIsWithinMaxDistance($query, $latitude, $longitude, $radius = 5) {
        $haversine = "(6371 * acos(cos(radians(" . $latitude . ")) 
                    * cos(radians(`latitude`)) 
                    * cos(radians(`longitude`) 
                    - radians(" . $longitude . ")) 
                    + sin(radians(" . $latitude . ")) 
                    * sin(radians(`latitude`))))";

        return $query->selectRaw("*, {$haversine} AS distance")
            ->orderBy('distance')
            ->whereRaw("{$haversine} < ?", [$radius]);
    }

    // To count max distance (in kilometre), ordered by the shortest distance from current location
    public function scopeUpcoming($query) {
        return $query->where('end_time', '>=', 'NOW()');
    }

    public function scopeExcludeUser($query, $user_id) {
        return $query->whereNotIn('teacher_id', [$user_id]);
    }

    public function scopeOnlyUser($query, $user_id) {
        return $query->where('teacher_id', $user_id);
    }

    public function scopeDateTimeUnion($query, $time_start, $time_end) {
        $q = $query->where(function ($query) use ($time_start, $time_end) {
                $query->where('end_time', '>=', $time_start)
                    ->where('end_time', '<=', $time_end);
            })
            ->orWhere(function ($query) use ($time_start, $time_end) {
                $query->where('start_time', '>=', $time_start)
                    ->where('start_time', '<=', $time_end);
            });
        return $q;
    }

    public function scopeOrDateTimeSubset($query, $time_start, $time_end) {
        $q = $query->orWhere(function ($qw) use ($time_start, $time_end) {
                $qw->where(function ($query) use ($time_start, $time_end) {
                        $query->where('start_time', '<', $time_start)
                            ->where('end_time', '>', $time_start);
                    })
                    ->where(function ($query) use ($time_start, $time_end) {
                        $query->where('start_time', '<', $time_end)
                            ->where('end_time', '>', $time_end);
                    });
        });
        return $q;
    }



    public function getEventsAttribute() {
        return Event::timeInside($this->start_time, $this->end_time)->get();
    }
}
