<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teacher() {
        return $this->belongsTo('App\TeacherRegistery', 'teacher_id');
    }

    public function student() {
        return $this->belongsTo('App\User', 'student_id');
    }

    public function eventType() {
        switch ($this->event_type) {
            case 1:
                return 'tahsin';
                break;
            case 2:
                return 'tahfidz';
                break;
            case 3:
                return 'tadabbur';
                break;
            default:
                return null;
                break;
        }
    }

    public function getUserAttribute() {
        return $this->teacher->user;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
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

    // To include distance in eloquent query results (ordered by the shortest distance from current location)
    public function scopeWithDistance($query, $latitude, $longitude) {
        $haversine = "(6371 * acos(cos(radians(" . $latitude . ")) 
                    * cos(radians(`latitude`)) 
                    * cos(radians(`longitude`) 
                    - radians(" . $longitude . ")) 
                    + sin(radians(" . $latitude . ")) 
                    * sin(radians(`latitude`))))";

        return $query->selectRaw("*, {$haversine} AS distance")
            ->orderBy('distance');
    }

    // Filter to show only events with amount of points of the current user have
    public function scopeMaxPoints($query, $points) {
        return $query->orderBy('points_offered')->where('points_offered', '>', -$points);
    }

    //-- START EVENT TYPE FILTERING --//

    public function scopeTahsin($query) {
        return $query->where('event_type', 1);
    }
    public function scopeTahfidz($query) {
        return $query->where('event_type', 2);
    }
    public function scopeTadabbur($query) {
        return $query->where('event_type', 3);
    }

    //-- END EVENT TYPE FILTERING --//
}
