<?php

namespace App\Http\Controllers;

use App\Models\ChapterFiles;
use App\Models\Chapters;
use App\Models\Utility;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChaptersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id,$header_id)
    {
        $chapter_type = Utility::chapter_type();
        return view('chapters.create',compact('header_id','chapter_type','course_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$course_id,$header_id)
    {
        $chapters = new Chapters();
        $chapters->header_id = $header_id   ;
        $chapters->name = $request->name;
        $chapters->duration = $request->duration;
        $chapters->course_id = Crypt::decrypt($course_id);

//        if($request->is_preview == null){
//            $chapters->is_preview = 'off';
//        }else{
//            $chapters->is_preview = $request->is_preview;
//        }
        $chapters->chapter_description = $request->chapter_description;
        $chapters->type = $request->type;

        if(!empty($request->video_url)){
            $chapters->video_url = $request->video_url;
        }
        if(!empty($request->video_file))
        {
            $filenameWithExt  = $request->File('video_file')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('video_file')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/chapters/';
            }
            else{
                $dir  = 'uploads/chapters/';
            }
            $chapters->video_file = $fileNameToStores;
            $path = Utility::upload_file($request,'video_file',$fileNameToStores,$dir,[]);
            // $dir             = storage_path('uploads/chapters/');
            // $image_path      = $dir . $chapters->thumbnail;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $chapters->video_file = $fileNameToStores;
            // $path = $request->file('video_file')->storeAs('uploads/chapters/', $fileNameToStores);
        }
        if(!empty($request->iframe)){
            $chapters->iframe = $request->iframe;
        }
        if(!empty($request->text_content)){
            $chapters->text_content = $request->text_content;
        }
        $file_name = [];
        $error_msg=[];

        if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
        {
            // $validator = \Validator::make($request->all(), [
            //     'multiple_files' => 'max:40000',
            // ]);
            // if($validator->fails())
            // {
            //     $msg = $validator->getMessageBag()->first();
            //     return $msg;
            // }
            foreach($request->multiple_files as $key => $file)
            {
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                // $file_name[]     = $fileNameToStore;
                // $dir             = storage_path('uploads/chapters/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $file->storeAs('uploads/chapters/', $fileNameToStore);

                $settings = Utility::getStorageSetting();
                $dir  = 'uploads/chapters/';
                $path = Utility::keyWiseUpload_file($request,'multiple_files',$fileNameToStore,$dir,$key,[]);

                if($path['flag'] == 1)
                {
                    $url = $path['url'];
                    $file_name[] = $fileNameToStore;
                  
                }else{
                    $error_msg[] = $path['msg'];
                }
            }



        }
      
        $chapters->save();
        foreach($file_name as $file)
        {
            $objStore = ChapterFiles::create(
                [
                    'chapter_id' => $chapters->id,
                    'chapter_files' => $file,
                ]
            );
        }

        if($error_msg ){
            $error_msg = count($error_msg). $error_msg[0];
        }else{
            $error_msg = '';
        }

        if(!empty($chapters))
        {
            $msg['flag'] = 'success';
            $msg['msg']  = __('Content Created Successfully').$error_msg;
        }
        else
        {
            $msg['flag'] = 'error';
            $msg['msg']  = __('Content Failed to Create');
        }

        return $msg;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chapters  $chapters
     * @return \Illuminate\Http\Response
     */
    public function show(Chapters $chapters)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chapters  $chapters
     * @return \Illuminate\Http\Response
     */
    public function edit($course_id,$id,$header_id)
    {
        $chapters = Chapters::find($id);
        $chapter_type = Utility::chapter_type();
        $file = ChapterFiles::where('chapter_id',$id)->get();
        return view('chapters.edit',compact('chapters','chapter_type','header_id','file','course_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chapters  $chapters
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chapters $chapters,$header_id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chapters  $chapters 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$course_id,$header_id)
    {
        $chapters = Chapters::find($id);
        if(!empty($chapters->video_file))
        {
            asset(Storage::delete('uploads/chapters/' . $chapters->video_file));
        }
        $contents = ChapterFiles::where('chapter_id',$id)->get();
        foreach($contents as $content){
            $dir = storage_path('uploads/chapters/'.$content->chapter_files);
            if(file_exists($dir)){
                unlink($dir);
            }
        }
        ChapterFiles::where('chapter_id',$id)->delete();
        $chapters->delete();
        return redirect()->back()->with('success', __('Chapter deleted successfully.'));
    }

    public function fileDelete($id)
    {
        $img_id = ChapterFiles::find($id);
        $dir = storage_path('uploads/chapters/');
        if(!empty($img_id->chapter_files))
        {
            if(!file_exists($dir . $img_id->chapter_files))
            {
                $content = DB::table('chapter_files')->where('id ', '=', $id)->delete();
                return response()->json(
                    [
                        'error' => __('File not exists in folder!'),
                        'id' => $id,
                    ]
                );
            }
            else
            {
                unlink($dir.$img_id->chapter_files);
                DB::table('chapter_files')->where('id', '=', $id)->delete();
                return response()->json(
                    [
                        'success' => __('Record deleted successfully!'),
                        'id' => $id,
                    ]
                );
            }
        }
    }

    public function ContentsUpdate(Request $request, $id)
    {
        $chapters            = Chapters::find($id);
        $chapters->name      = $request->name;
        $chapters->duration  = $request->duration;
        //   if($request->is_preview == null)
        //   {
        //       $chapters->is_preview = 'off';
        //   }
        //   else
        //   {
        //       $chapters->is_preview = $request->is_preview;
        //   }
        $chapters->chapter_description = $request->chapter_description;
        $chapters->type                = $request->type;
        if(!empty($request->video_url))
        {
            $chapters->video_url = $request->video_url;
        }
        $file_name = [];
        if(!empty($request->video_file))
        {
            if(asset(Storage::exists('uploads/chapters/' . $chapters->video_file)))
            {
                asset(Storage::delete('uploads/chapters/' . $chapters->video_file));
            }
            $filenameWithExt  = $request->File('video_file')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('video_file')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;
            // $dir              = storage_path('uploads/chapters/');
            // $image_path       = $dir . $chapters->thumbnail;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $chapters->video_file = $fileNameToStores;
            // $path                 = $request->file('video_file')->storeAs('uploads/chapters/', $fileNameToStores);

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/chapters/';
            }
            else{
                $dir  = 'uploads/chapters/';
            }
            $chapters->video_file = $fileNameToStores;
            $path = Utility::upload_file($request,'video_file',$fileNameToStores,$dir,[]); 
            
            if($path['flag'] == 1)
            {
                $url = $path['url'];
                $file_name[] = $fileNameToStores;
            }

        }
        if(!empty($request->iframe))
        {
            $chapters->iframe = $request->iframe;
        }
        if(!empty($request->text_content))
        {
            $chapters->text_content = $request->text_content;
        }
        $file_name = [];
        if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
        {
            // $validator = \Validator::make(
            //     $request->all(), [
            //     'multiple_files' => 'max:40000',
            // ]
            // );
            foreach($request->multiple_files as $key => $file)
            {
                // dd($request->all());
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                // $file_name[]     = $fileNameToStore;
                // $dir             = storage_path('uploads/chapters/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $file->storeAs('uploads/chapters/', $fileNameToStore);

                $settings = Utility::getStorageSetting();
                $dir  = 'uploads/chapters/';
                // $path = Utility::upload_file($request,'file',$fileNameToStore,$dir,[]);
                $path = Utility::keyWiseUpload_file($request,'multiple_files',$fileNameToStore,$dir,$key,[]);
                // dd($path);

                if($path['flag'] == 1)
                {
                    // $url = $path['url'];
                    // $file_name[] = $;
                    $objStore = ChapterFiles::create(
                        [
                            'chapter_id' => $chapters->id,
                            'chapter_files' => $fileNameToStore,
                        ]
                    );
                }else{
                    $file_name[] = $path['msg'];
                }

            }
            // dd($file_name);
            if($file_name ){
               $error_msg= count($file_name). $file_name[0];
            }else{
                $error_msg= '';
            }
        }
        // dd( $file_name);
        $chapters->update();
        

        if(!empty($chapters))
        {
            $msg['flag'] = 'success';
            $msg['msg']  = __('Content Updated Successfully').$error_msg;
        }
        else
        {
            $msg['flag'] = 'error';
            $msg['msg']  = __('Content Failed to Update');
        }

        return $msg;
    }

    public function ContentCreate($header_id,$course_id)
    {
        $chapter_type = Utility::chapter_type();
        return view('chapters.create',compact('header_id','chapter_type','course_id'));
    }

}
