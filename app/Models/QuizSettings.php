<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSettings extends Model
{
    protected $fillable = [
        'title',
        'quiz_group',
        'min_percentage',
        'category',
        'sub_category',
        'instructions',
        'time',
        'total_time',
        'per_question_time',
        'review',
        'result_after_submit',
        'random_questions',
        'created_by',
    ];

    public function category_id()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }

    public function subcategory_id()
    {
        return $this->hasOne('App\Models\Subcategory', 'id', 'category');
    }
}
