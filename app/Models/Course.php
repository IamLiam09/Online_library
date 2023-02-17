<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    protected $fillable = [
        'store_id',
        'title',
        'type',
        'course_requirements',
        'course_description',
        'has_certificate',
        'status',
        'category',
        'quiz',
        'sub_category',
        'level',
        'lang',
        'duration',
        'is_free',
        'price',
        'has_discount',
        'discount',
        'featured_course',
        'is_preview',
        'preview_type',
        'preview_content',
        'host',
        'thumbnail',
        'meta_keywords',
        'meta_description',
        'created_by',
    ];

    public function category_id()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }

    public function subcategory_id()
    {
        return $this->hasOne('App\Models\Subcategory', 'id', 'sub_category');
    }

    public function quiz_id()
    {
        return $this->hasOne('App\Models\QuizSettings', 'id', 'quiz');
    }

    public function header_id()
    {
        return $this->hasMany('App\Models\Header', 'course', 'id');
    }

    public function tutor_id()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function chapter_count()
    {
        return $this->hasMany('App\Models\Chapters', 'course_id', 'id');
    }

    public function course_rating()
    {
        $ratting    = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->sum('ratting');
        $user_count = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->count();
        if($user_count > 0)
        {
            $avg_rating = number_format($ratting / $user_count, 1);
        }
        else
        {
            $avg_rating = number_format($ratting / 1, 1);

        }

        return $avg_rating;
    }

    public function star_rating($course_id)
    {
        $user_count = Ratting::where('course_id', $this->id)->where('rating_view', 'on')->count();
        $ratting=[];
        $rating_count5 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',5)->count();
        $rating_count4 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',4)->count();
        $rating_count3 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',3)->count();
        $rating_count2 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',2)->count();
        $rating_count1 = Ratting::where('course_id',$course_id)->where('rating_view','on')->where('ratting',1)->count();
        if($user_count != 0){
            $ratting['ratting5'] = $rating_count5 * 100 / $user_count;
            $ratting['ratting4'] = $rating_count4 * 100 / $user_count;
            $ratting['ratting3'] = $rating_count3 * 100 / $user_count;
            $ratting['ratting2'] = $rating_count2 * 100 / $user_count;
            $ratting['ratting1'] = $rating_count1 * 100 / $user_count;
        }else{
            $ratting['ratting5'] = 0;
            $ratting['ratting4'] = 0;
            $ratting['ratting3'] = 0;
            $ratting['ratting2'] = 0;
            $ratting['ratting1'] = 0;
        }
        

        return $ratting;
    }

    public function student_wl()
    {
        return $this->belongsToMany(
                'App\Models\Student', 'wishlists', 'course_id', 'student_id'
            )->where('student_id','=',\Auth::guard('students')->user()->id);
  
    }

    public function purchased_course()
    {
        return $this->belongsToMany(
            'App\Models\Student', 'purchased_courses', 'course_id', 'student_id'
        );
    }

    public function student_count()
    {
        return $this->hasMany('App\Models\PurchasedCourse', 'course_id', 'id');
    }

    public static function stores($store)
    {
        $categoryArr  = explode(',', $store);
        $unitRate = 0;
        foreach($categoryArr as $store)
        {
            if($store == 0){
                $unitRate = '';
            }
            else{
                $store     = Store::find($store);
                $unitRate  = $store->name;
            }
        }
        return $unitRate;
    }

    public static function courseCount()
    {
        return PurchasedCourse::where('student_id', Auth::guard('students')->id())->count();
    }
    
}
