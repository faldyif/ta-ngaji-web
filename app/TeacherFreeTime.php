<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherFreeTime extends Model
{
    public function getUserAttribute() {
        return $this->teacher->user;
    }

    public function teacher() {
        return $this->belongsTo('App\TeacherRegistery', 'teacher_id');
    }

    public function scopeTimeBetween($query, $startTime, $endTime)
    {
        // cari yang mana waktu satu event ngaji yang dibuat oleh guru kurang dari sama dengan waktu mulai
        // dan waktu selesai yang dibuat oleh guru harus lebih dari waktu selesai yang dicari oleh siswa
        return $query->whereDate('start_time', '<=', $startTime)->whereDate('end_time', '<=', $endTime);
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

    public function events($start, $end) {
        return Event::timeBetween($start, $end)->where('teacher_id', $this->teacher->id)->get();
    }
}
