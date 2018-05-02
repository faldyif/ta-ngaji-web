<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function teacher() {
        return $this->belongsTo('App\TeacherRegistery', 'teacher_id');
    }
}
