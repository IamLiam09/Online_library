<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChapterFiles;
use App\Models\Course;
use App\Models\Faq;
use App\Models\PracticesFiles;
use App\Models\QuizSettings;
use App\Models\Subcategory;
use App\Models\Utility;
use App\Exports\CoursesExport;
use App\Models\Header;
use App\Models\Store;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user()->current_store;
        $courses    = Course::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get();
        $category    = Category::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name','id');
        return view('course.index',compact('courses','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user()->current_store;
        $category = Category::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get();
        //  $quiz = QuizSettings::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get()->pluck('title','id');
        $preview_type = [
            'Video File' => 'Video File',
            'Image'=> 'Image',
            'iFrame' => 'iFrame'
        ];
        $level = Utility::course_level();
        return view('course.create',compact('level','category','preview_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
       $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required|max:120',
                            ]
        );  

        $has_discount                = isset($request->has_discount) ? 'on' : 'off';
        $is_free                     = isset($request->is_free) ? 'on' : 'off';
        $is_preview                  = isset($request->is_preview) ? 'on' : null;
        $has_certificate             = isset($request->has_certificate) ? 'on' : null;
        $course                      = new Course();
        $course->title               = $request->title;
        $course->course_requirements = $request->course_requirements;
        $course->course_description	 = $request->course_description	;
        $course->level               = $request->level;
        $course->lang                = $request->lang;
        $course->duration            = $request->duration;
        $course->host                = $request->host;
        if($has_certificate == 'on'){
            $course->has_certificate = 'on';
        }else{
            $course->has_certificate = 'off';
        }        
        // old code
        // if($request->type == 'Course'){
        //     $course->category = $request->category;
        //     $validator = \Validator::make($request->all(), [
        //         'subcategory' => 'required',
        //     ]);
        //     $course->sub_category = implode(',',$request->subcategory);
        // }

        if(isset($request->category) && isset($request->subcategory))
        {
            $validator = \Validator::make($request->all(), [
                'subcategory' => 'required',
            ]);
            $course->type = "Course";            
            $course->category = $request->category;
            $course->sub_category = implode(',',$request->subcategory);
        }

        if($request->type == 'Quiz'){
            if(!empty($request->quiz))
            {
                $course['quiz'] = implode(',', $request->quiz);
            }
            else
            {
                $course['quiz'] = $request->quiz;
            }
        }

        if($is_free == 'off'){
            $course->price = $request->price;

            if($has_discount == 'on'){
                $validator = \Validator::make($request->all(), ['discount' => 'required',]);
                $course->has_discount = 'on';
                $course->discount = $request->discount;
            }else{
                $course->has_discount = 'off';
                $course->discount = null;
            }
        }else{
            $course->is_free = 'on';
            $course->price = null;
            $course->discount = null;
            $course->has_discount = 'off';
        }
       
        if($is_preview == 'on')
        {
            $course->is_preview = $request->is_preview;
            $course->preview_type = $request->preview_type;

            if(!empty($request->preview_image))
            {
                $filenameWithExt  = $request->File('preview_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('preview_image')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir  = 'uploads/preview_image/';
                }
                else{
                    $dir  = 'uploads/preview_image/';
                }
                $course->preview_content = $fileNameToStores;
                $path = Utility::upload_file($request,'preview_image',$fileNameToStores,$dir,[]);
                // $dir             = storage_path('uploads/preview_image/');
                // $image_path      = $dir . $course->preview_content;
                // if(File::exists($image_path))
                // {
                //     File::delete($image_path);
                // }
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $course->preview_content = $fileNameToStores;
                // $path = $request->file('preview_image')->storeAs('uploads/preview_image/', $fileNameToStores);

                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

            }
            if(!empty($request->preview_video))
            {
                $filenameWithExt  = $request->File('preview_video')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('preview_video')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir  = 'uploads/preview_video/';
                }
                else{
                    $dir  = 'uploads/preview_video/';
                }
                $course->preview_content = $fileNameToStores;
                $path = Utility::upload_file($request,'preview_video',$fileNameToStores,$dir,[]);
                // $dir             = storage_path('uploads/preview_video/');
                // $image_path      = $dir . $course->preview_content;
                // if(File::exists($image_path))
                // {
                //     File::delete($image_path);
                // }
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $course->preview_content = $fileNameToStores;
                // $path = $request->file('preview_video')->storeAs('uploads/preview_video/', $fileNameToStores);
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }else{
            $course->is_preview = 'off';
        }

        if(!empty($request->thumbnail))
        {
            $filenameWithExt  = $request->File('thumbnail')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('thumbnail')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/thumbnail/';
            }
            else{
                $dir  = 'uploads/thumbnail/';
            }
            $course->thumbnail = $fileNameToStores;
            $path = Utility::upload_file($request,'thumbnail',$fileNameToStores,$dir,[]);
            // $dir             = storage_path('uploads/thumbnail/');
            // $image_path      = $dir . $course->thumbnail;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $course->thumbnail = $fileNameToStores;
            // $path = $request->file('thumbnail')->storeAs('uploads/thumbnail/', $fileNameToStores);
            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }

        }
        $course->featured_course = $request->featured_course;
        // $course->type = $request->type;
        $course->type = "Course"; 
        $course->status = 'Active';
        $course->meta_keywords = $request->meta_keywords;
        $course->meta_description = $request->meta_description;
        $course->store_id =  \Auth::user()->current_store;;
        $course->created_by = \Auth:: user()->creatorId();

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages );
        }

        $course->save();
        $course_id = Crypt::encrypt($course->id);

        // slack //                        
        $user = \Auth::user()->current_store;
        $creator   = Store::where('id',$user)->get();
        $settings  = Utility::notifications(Auth::user()->current_store );
        if(isset($settings['course_notification']) && $settings['course_notification'] ==1){
            $msg = 'New Course '.$request->input('title').' is created by '.$creator[0]->name.'.';
            Utility::send_slack_msg($msg); 
        }

        // telegram //                        
        $user = \Auth::user()->current_store;
        $creator   = Store::where('id',$user)->get();
        $settings  = Utility::notifications(Auth::user()->current_store );
        if(isset($settings['telegram_course_notification']) && $settings['telegram_course_notification'] ==1){
            $msg = 'New Course '.$request->input('title').' is created by '.$creator[0]->name.'.';
            Utility::send_telegram_msg($msg);    
        }

        return redirect()->route('course.edit',$course_id)->with('success', __('Course created successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $quiz_id = explode(',',$course->quiz);
        $sub_id = explode(',',$course->sub_category);
        $quiz = QuizSettings::whereIn('id',$quiz_id)->get()->pluck('title')->toArray();
        $sub = Subcategory::whereIn('id',$sub_id)->get()->pluck('name')->toArray();
        return view('course.view',compact('course','quiz','sub'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \Auth::user()->current_store;
        $course          = Course::find(Crypt::decrypt($id));
        $category        = Category::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name','id');
        $sub_category    = Subcategory::where('category',$course->category)->get()->pluck('name','id');
        $level           = Utility::course_level();
        $status          = Utility::status();
        $quiz            = QuizSettings::where('created_by', \Auth::user()->creatorId())->get()->pluck('title','id');
        $course_id       = $id;
        $headers         = Header::where('course',Crypt::decrypt($id))->get();
        $practices_files = PracticesFiles::where('course_id',Crypt::decrypt($id))->get();
        $faqs = Faq::where('course_id',Crypt::decrypt($id))->get();
        $preview_type = [
            'Video File' => 'Video File',
            'Image'=> 'Image',
        ];

        return view('course.edit',compact('practices_files','faqs','sub_category','course','category','level','status','quiz','course_id','headers','preview_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required|max:120',
                           ]
        );

        $has_discount        = isset($request->has_discount) ? 'on' : 'off';
        $is_free        = isset($request->is_free) ? 'on' : 'off';
        $is_preview        = isset($request->is_preview) ? 'on' : null;
        $has_certificate        = isset($request->has_certificate) ? 'on' : null;
        $course->title = $request->title;
        $course->course_requirements = $request->course_requirements;
        $course->course_description	 = $request->course_description	;
        $course->level = $request->level;
        $course->lang = $request->lang;
        $course->duration = $request->duration;
        $course->host = $request->host;
        if($has_certificate == 'on'){
            $course->has_certificate = 'on';
        }else{
            $course->has_certificate = 'off';
        }
        if($is_free == 'off'){
            $validator = \Validator::make($request->all(), ['price' => 'required',]);
            $course->is_free = 'off';
            $course->price = $request->price;

            if($has_discount == 'on'){
                $validator = \Validator::make($request->all(), ['discount' => 'required',]);
                $course->has_discount = 'on';
                $course->discount = $request->discount;
            }else{
                $course->has_discount = 'off';
                $course->discount = null;
            }
        }else{
            $course->is_free = 'on';
            $course->price = null;
            $course->discount = null;
            $course->has_discount = 'off';
        }
        if($request->type == 'Course'){
            $course->category = $request->category;
            $course->sub_category = implode(',',$request->subcategory);
        }
        if($request->type == 'Quiz'){
            if(!empty($request->quiz))
            {
                $course['quiz'] = implode(',', $request->quiz);
            }
            else
            {
                $course['quiz'] = $request->quiz;
            }
        }else{
            $course->quiz = null;
        }
        if($is_preview == 'on')
        {
            $course->is_preview = $request->is_preview;
            $course->preview_type = $request->preview_type;
            if(!empty($request->preview_image))
            {
                $filenameWithExt  = $request->File('preview_image')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('preview_image')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir  = 'uploads/preview_image/';
                }
                else{
                    $dir  = 'uploads/preview_image/';
                }
                $course->preview_content = $fileNameToStores;
                $path = Utility::upload_file($request,'preview_image',$fileNameToStores,$dir,[]);
                // $dir             = storage_path('uploads/preview_image/');
                // $image_path      = $dir . $course->preview_content;
                // if(File::exists($image_path))
                // {
                //     File::delete($image_path);
                // }
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $course->preview_content = $fileNameToStores;
                // $path = $request->file('preview_image')->storeAs('uploads/preview_image/', $fileNameToStores);

                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

            }
            if(!empty($request->preview_video))
            {
                $filenameWithExt  = $request->File('preview_video')->getClientOriginalName();
                $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension        = $request->file('preview_video')->getClientOriginalExtension();
                $fileNameToStores = $filename . '_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir  = 'uploads/preview_video/';
                }
                else{
                    $dir  = 'uploads/preview_video/';
                }
                $course->preview_content = $fileNameToStores;
                $path = Utility::upload_file($request,'preview_video',$fileNameToStores,$dir,[]);
                // $dir             = storage_path('uploads/preview_video/');
                // $image_path      = $dir . $course->preview_content;
                // if(File::exists($image_path))
                // {
                //     File::delete($image_path);
                // }
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $course->preview_content = $fileNameToStores;
                // $path = $request->file('preview_video')->storeAs('uploads/preview_video/', $fileNameToStores);

                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }else{
            $course->is_preview = 'off';
        }
        if(!empty($request->thumbnail))
        {
            $filenameWithExt  = $request->File('thumbnail')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('thumbnail')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/thumbnail/';
            }
            else{
                $dir  = 'uploads/thumbnail/';
            }
            $course->thumbnail = $fileNameToStores;
            $path = Utility::upload_file($request,'thumbnail',$fileNameToStores,$dir,[]);
            // $dir             = storage_path('uploads/thumbnail/');
            // $image_path      = $dir . $course->thumbnail;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $course->thumbnail = $fileNameToStores;
            // $path = $request->file('thumbnail')->storeAs('uploads/thumbnail/', $fileNameToStores);

            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }

        }
        $course->featured_course = $request->featured_course;
        // $course->type = $request->type;
        $course->type = "Course";  
        $course->status = $request->status;
        $course->meta_keywords = $request->meta_keywords;
        $course->meta_description = $request->meta_description;
        $course->created_by = \Auth:: user()->creatorId();
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
        }
        $course->update();
        return redirect()->back()->with('success', __('Course updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->back()->with('success', __('Course deleted successfully.'));
    }

    public function getsubcategory(Request $request)
    {
        $user = \Auth::user()->current_store;
        $subcategory = Subcategory::where('store_id',$user)->where('created_by', '=', \Auth::user()->creatorId())->where('category', $request->category_id)->get()->pluck('name', 'id')->toArray();
        return response()->json($subcategory);
    }

    public function practicesFiles(Request $request,$id)
    {
        $course_id = Crypt::decrypt($id);
        $file_name = [];
        // dd($request->all());

        if(!empty($request->file) && count($request->file) > 0)
        {
            // $validator = \Validator::make($request->all(), [
            //     'multiple_files' => 'max:40000',
            // ]);
            foreach($request->file as $key => $file)
            {
                // dd($request->all());
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $file_name[]     = $fileNameToStore;
                // $dir             = storage_path('uploads/practices/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $file->storeAs('uploads/practices/', $fileNameToStore);

                $settings = Utility::getStorageSetting();
                if($settings['storage_setting']=='local'){
                    $dir  = 'uploads/practices/';
                }
                else{
                    $dir  = 'uploads/practices/';
                }
                // $category->category_image = $fileNameToStore;
                // dd($request->all());
                // $path = Utility::upload_file($request,'file',$fileNameToStore,$dir,[]);
                $path = Utility::keyWiseUpload_file($request,'file',$fileNameToStore,$dir,$key,[]);
                // dd($path);
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{

                    return response()->json([
                        'status' => 'error',
                        'error' =>  __($path['msg']),
                    ]
                );                
                  
                } 
            }
        }
        foreach($file_name as $file)
        {
            $uploded_files =
                PracticesFiles::create(
                [
                    'course_id' => $course_id,
                    'file_name' => $filename,
                    'files' => $file,
                ]
            );
        }
        // if($validator->fails())
        // {
        //     $msg = $validator->getMessageBag()->first();
        //     return $msg;
        // }
        return response()->json([
                'status' => 'success',
                'success' =>  __('Successfully added!'),
            ]
        );

    }

    public function fileDelete($id)
    {
        $img_id = PracticesFiles::find($id);
        $dir = storage_path('uploads/practices/');
        if(!empty($img_id->files))
        {
            if(!file_exists($dir . $img_id->files))
            {
                $content = DB::table('practices_files')->where('id ', '=', $id)->delete();
                return response()->json(
                    [
                        'error' => __('File not exists in folder!'),
                        'id' => $id,
                    ]
                );
            }
            else
            {
                unlink($dir.$img_id->files);
                DB::table('practices_files')->where('id', '=', $id)->delete();
                return response()->json(
                    [
                        'success' => __('Record deleted successfully!'),
                        'id' => $id,
                    ]
                );
            }
        }
    }

    public function editFileName($id)
    {
        $file_name = PracticesFiles::find($id);
        return view('course.editFileName',compact('file_name'));
    }

    public function updateFileName(Request $request,$id)
    {
        $file = PracticesFiles::find($id);
        $file->file_name = $request->file_name;
        $file->update();
        return redirect()->back()->with('success', __('Filename updated successfully') );
    }

    public function export()
    {
        $name = 'course_' . date('Y-m-d i:h:s');
        $data = Excel::download(new CoursesExport(), $name . '.xlsx');ob_end_clean();

        return $data;
    }
    
}
