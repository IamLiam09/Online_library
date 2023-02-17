<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'store_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function course_wl()
    {
        return $this->belongsToMany(
            'App\Models\Course', 'wishlists', 'student_id', 'course_id'
        );
    }

    public function course_purchased()
    {
        return $this->belongsToMany(
            'App\Models\Course', 'purchased_courses', 'student_id', 'course_id'
        );
    }

    public function purchasedCourse()
    {
        return $this->hasMany('App\Models\PurchasedCourse', 'student_id', 'id')->get()->pluck('course_id')->toArray();
    }

    public function Coursename()
    {
        return $this->hasOne('App\Models\Course', 'id', 'title');
    }

}
