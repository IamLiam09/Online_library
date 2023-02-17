<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
    ];

    public static function wishCount()
    {
        return Wishlist::where('student_id', Auth::guard('students')->id())->count();
    }
   
}

