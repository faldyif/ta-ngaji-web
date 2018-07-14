<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherLevel extends Model
{
    public function scopePointEnough($query, $point) {
        return $query->where('points', '>=', -$point);
    }
}
