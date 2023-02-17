<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\Student;
use Closure;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $slug    = \Request::segment(1);
        $auth_student = Auth::guard('students')->user();
        
        if (!empty($auth_student)) {
            $store   = Store::where('slug', $slug)->pluck('id');
            $student = Student::where('store_id',$store)->where('email',$auth_student->email)->count();

            if($student>0){
                return $next($request);
            }else{
                return redirect($slug.'/student-login');
            }
        }
        return redirect($slug.'/student-login');
    }
}
