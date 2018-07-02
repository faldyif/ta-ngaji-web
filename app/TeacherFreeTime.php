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

    public function scopeTimeInside($query, $startTime, $endTime)
    {
        return $query->whereDate('start_time', '>=', $startTime)->whereDate('end_time', '<=', $endTime);
    }

    public function scopeTimeBetween($query, $startTime, $endTime)
    {
        return $query->whereDate('start_time', '<=', $startTime)->whereDate('end_time', '>=', $endTime);
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
        return $query->whereDate('end_time', '>=', 'NOW()');
    }

    public function scopeExcludeUser($query, $user_id) {
        return $query->whereNotIn('teacher_id', [$user_id]);
    }

    public function getEventsAttribute() {
        return Event::timeInside($this->start_time, $this->end_time)->get();
    }
}
