<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Ratting;
use App\Models\Store;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;

class RattingController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Ratting $ratting
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Ratting $ratting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Ratting $ratting
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Ratting $ratting, $id)
    {
        $rating = Ratting::where('id', $id)->first();        

        return view('storefront.rating.edit', compact('rating'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Ratting $ratting
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ratting $ratting, $id)
    {
        $rating_view        = isset($request->rating_view) ? 'on' : null;
        $ratting   = Ratting::where('id', $id)->first();
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                               'title' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if($rating_view == 'on'){
            $ratting->rating_view = 'on';
        }else{
            $ratting->rating_view = 'off';
        }
        $ratting->name        = $request->name;
        $ratting->title       = $request->title;
        $ratting->ratting     = $request->rate;
        $ratting->description = $request->description;
        $ratting->update();

        return redirect()->back()->with('success', __('Rating Updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Ratting $ratting
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ratting $ratting, $id)
    {
        $ratting = Ratting::where('id', $id)->first();
        $ratting->delete();

        return redirect()->back()->with(
            'success', __('Rating Deleted!')
        );
    }

    public function rating($slug, $course_id)
    {
        $tutor_id = Course::where('id',$course_id)->pluck('created_by')->first();        
        $store    = Store::where('slug', $slug)->first(); 
        
        return view('storefront.' . $store->theme_dir . '.rating.create', compact('slug', 'course_id','tutor_id'));
    }

    public function store_rating(Request $request, $slug, $course_id,$tutor_id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required|max:120',
                               'title' => 'required|max:120',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $ratting              = new Ratting();
        $ratting->slug        = $slug;
        $ratting->course_id   = $course_id;
        $ratting->tutor_id   = $tutor_id;
        $ratting->rating_view = 'on';
        $ratting->name        = $request->name;
        $ratting->title       = $request->title;
        $ratting->ratting     = $request->rate;
        $ratting->description = $request->description;
        $ratting->save();

        return redirect()->back()->with('success', __('Rating Created!'));
    }

    public function rating_view(Request $request)
    {
        $id = $request->id;

        $ratting              = Ratting::find($id);
        $ratting->rating_view = $request->status;
        $ratting->save();

        return response()->json(
            [
                'success' => __('Successfully!'),
            ]
        );
    }

}
