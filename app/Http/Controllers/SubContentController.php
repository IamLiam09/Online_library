<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Subcategory;
use App\Models\SubContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SubContentController extends Controller
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
    public function create()
    {
        $subcategory = Subcategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        return view('subcontent.create',compact('subcategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subcontent = new SubContent();
        $file_name = [];
        if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
        {
            $validator = Validator::make($request->all(), [
                'multiple_files' => 'max:40000',
            ]);
            foreach($request->multiple_files as $file)
            {
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $file_name[]     = $fileNameToStore;
                $dir             = storage_path('uploads/contents/');
                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $file->storeAs('uploads/contents/', $fileNameToStore);
            }
        }
        $subcontent->subcategory = $request->subcategory;
        $subcontent->title = $request->title;
        $subcontent->description = $request->description;
        $subcontent->url = $request->url;
        $subcontent->created_by = \Auth:: user()->creatorId();;
        $subcontent->save();
        foreach($file_name as $file)
        {
            $objStore = Content::create(
                [
                    'subcontent' => $subcontent->id,
                    'content' => $file,

                ]
            );
        }

        if(!empty($subcontent))
        {
            $msg['flag'] = 'success';
            $msg['msg']  = __('Content Created Successfully');
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
     * @param  \App\SubContent  $subContent
     * @return \Illuminate\Http\Response
     */
    public function show(SubContent $subContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubContent  $subContent
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subcontent = SubContent::find($id);
        $subcategory = Subcategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $file = Content::where('subcontent',$id)->get();
        return view('subcontent.edit',compact('subcategory','subcontent','file'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubContent  $subContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubContent $subContent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubContent  $subContent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subContent = SubContent::find($id);
        $content = DB::table('contents')->where('subcontent', '=', $id)->delete();
        $subContent->delete();
        return redirect()->back()->with('success', __('Content deleted successfully.'));
    }

    public function viewcontent($id)
    {
        $id        = Crypt::decrypt($id);
        $sub_name  = Subcategory::where('id', $id)->first();
        $content   = SubContent::where('subcategory', $id)->get();

        return view('subcontent.index', compact('content', 'sub_name'));
    }

    public function fileDelete($id)
    {
        $img_id = Content::find($id);

        $dir = storage_path('uploads/contents/');
        if(!empty($img_id->content))
        {
            if(!file_exists($dir . $img_id->content))
            {
                $content = DB::table('contents')->where('id ', '=', $id)->delete();
                return response()->json(
                    [
                        'error' => __('File not exists in folder!'),
                        'id' => $id,
                    ]
                );


            }
            else
            {
                unlink($dir . $img_id->content);
                DB::table('contents')->where('id', '=', $id)->delete();
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
        $validator = \Validator::make($request->all(), [
            'multiple_files' => 'required',
        ]);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            $msg['flag'] = 'error';
            $msg['msg']  = $messages->first();

            return $msg;
        }
        $subcontent = SubContent::find($id);
        $file_name = [];
        if(!empty($request->multiple_files) && count($request->multiple_files) > 0)
        {


            foreach($request->multiple_files as $file)
            {
                $filenameWithExt = $file->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $file_name[]     = $fileNameToStore;
                $dir             = storage_path('uploads/contents/');
                if(!file_exists($dir))
                {
                    mkdir($dir, 0777, true);
                }
                $path = $file->storeAs('uploads/contents/', $fileNameToStore);
            }
        }
        $subcontent->subcategory = $request->subcategory;
        $subcontent->title = $request->title;
        $subcontent->description = $request->description;
        $subcontent->url = $request->url;
        $subcontent->created_by = \Auth:: user()->creatorId();;
        $subcontent->update();
        foreach($file_name as $file)
        {
            $objStore = Content::create(
                [
                    'subcontent' => $subcontent->id,
                    'content' => $file,

                ]
            );
        }

        if(!empty($subcontent))
        {
            $msg['flag'] = 'success';
            $msg['msg']  = __('Content Updated Successfully');
        }
        else
        {
            $msg['flag'] = 'error';
            $msg['msg']  = __('Content Failed to Update');
        }
        return $msg;
    }
}
