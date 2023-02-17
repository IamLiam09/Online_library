<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedCourse extends Model
{
    protected $table = 'purchased_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'order_id',
    ];
}
