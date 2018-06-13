<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'credits_amount', 'loyalty_points', 'verified', 'verification_token', 'experience_points'
    ];

    public function teacherRegistery() {
        return $this->hasOne('App\TeacherRegistery');
    }

    public function firebaseTokens() {
        return $this->hasMany('App\UserFirebaseToken');
    }

    // Show gender
    public function gender() {
        if($this->gender == 'M') return 'male';
        else if($this->gender == 'F') return 'female';
        else return null;
    }

    // Show role
    public function role() {
        if($this->role_id == 1) return 'user';
        else if($this->role_id == 2) return 'teacher';
        else if($this->role_id == 3) return 'superadmin';
        else return null;
    }

    public function scopeStudent($query) {
        return $query->where('role_id', 1);
    }

    public function scopeTeacher($query) {
        return $query->where('role_id', 2);
    }
}
