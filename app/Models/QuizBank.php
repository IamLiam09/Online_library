<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizBank extends Model
{
    protected $fillable = [
        'store_id',
        'quiz',
        'question',
        'options',
        'answer',
        'created_by',
    ];

    public function quiz_id()
    {
        return $this->hasOne('App\Models\QuizSettings', 'id', 'quiz');
    }
}
