<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherRegistery extends Model
{
    public function user() {
        return $this->hasOne('App\User', 'linked_id');
    }

    public function events() {
        return $this->hasMany('App\Event', 'teacher_id');
    }
}
