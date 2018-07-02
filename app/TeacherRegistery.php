<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherRegistery extends Model
{
    protected $fillable = [
        'user_id', 'teacher_level_id', 'registered_from', 'teacher_competence',
    ];

    public function user() {
        return $this->hasOne('App\User', 'linked_id');
    }

    public function events() {
        return $this->hasMany('App\Event', 'teacher_id');
    }

    public function teacherFreeTimes() {
        return $this->hasMany('App\TeacherFreeTime', 'teacher_id');
    }

    public function level() {
        return $this->hasOne('App\TeacherLevel', 'id', 'teacher_level_id');
    }

}
