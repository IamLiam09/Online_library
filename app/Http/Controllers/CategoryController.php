<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use File;
use App\Models\Utility;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user()->current_store;
        $categorise    = Category::where('store_id',$user)->where('created_by', \Auth::user()->creatorId())->get();
        return view('category.index',compact('categorise'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
                               'name' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
        }

        $category = new Category();
        if(!empty($request->category_image))
        {
            $filenameWithExt  = $request->file('category_image')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('category_image')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/category_image/';
            }
            else{
                $dir  = 'uploads/category_image/';
            }
            $category->category_image = $fileNameToStores;
            $path = Utility::upload_file($request,'category_image',$fileNameToStores,$dir,[]);
            // dd($path);
            
            // dd($path);
            // $dir             = storage_path('uploads/category/');
            // $image_path      = $dir . $category->category_image;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $category->category_image = $fileNameToStores;
            // $path = $request->file('category_image')->storeAs('uploads/category_image/', $fileNameToStores);

            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }

        }
        $category->name = $request->name;
        $category->store_id = \Auth::user()->current_store;
        $category->created_by = \Auth:: user()->creatorId();
        $category->save();

        return redirect()->back()->with('success', __('Category created successfully!'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
        }
        if(!empty($request->category_image))
        {
            $filenameWithExt  = $request->file('category_image')->getClientOriginalName();
            $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension        = $request->file('category_image')->getClientOriginalExtension();
            $fileNameToStores = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if($settings['storage_setting']=='local'){
                $dir  = 'uploads/category_image/';
            }
            else{
                $dir  = 'uploads/category_image/';
            }
            $category->category_image = $fileNameToStores;
            $path = Utility::upload_file($request,'category_image',$fileNameToStores,$dir,[]);
            // $dir             = storage_path('uploads/category_image/');
            // $image_path      = $dir . $category->category_image;
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path);
            // }
            // if(!file_exists($dir))
            // {
            //     mkdir($dir, 0777, true);
            // }
            // $category->category_image = $fileNameToStores;
            // $path = $request->file('category_image')->storeAs('uploads/category_image/', $fileNameToStores);
            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        $category->name = $request->name;
        $category->update();

        return redirect()->back()->with('success', __('Category updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', __('Category deleted successfully.'));
    }
}
