<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapters extends Model
{
    protected $fillable = [
        'header_id',
        'course_id',
        'name',
        'order_by',
        'type',
        'duration',
        'video_url',
        'video_file',
        'iframe',
        'text_content',
        'chapter_description',
    ];

    public function header_data()
    {
        return $this->hasMany('App\Models\Header', 'id', 'header_id');
    }

    public function chapters_status()
    {
        return $this->hasOne('App\Models\ChapterStatus', 'chapter_id', 'id');
    }

    public static function chapterstatus($id)
    {
        $student_id = \Auth::guard('students')->user()->id;
        $chepterstatus = ChapterStatus::where('chapter_id',$id)->where('student_id',$student_id)->first();

        return $chepterstatus;
    }

}
