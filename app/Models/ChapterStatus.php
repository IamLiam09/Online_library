<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChapterStatus extends Model
{
    protected $table = 'chapter_statuses';
    protected $fillable = [
        'chapter_id',
        'student_id',
        'course_id',
        'status',
    ];
}
