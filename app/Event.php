<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teacher() {
        return $this->belongsTo('App\TeacherRegistery', 'teacher_id');
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

    public function user() {
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
        return $query->whereDate('start_time', '<=', $startTime)->whereDate('end_time', '>=', $endTime);
    }
}
