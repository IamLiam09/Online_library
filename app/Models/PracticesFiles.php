<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticesFiles extends Model
{
    protected $fillable = [
        'course_id',
        'file_name',
        'files',
    ];
}
