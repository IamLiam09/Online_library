<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FaqController extends Controller
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
    public function create($id)
    {
        return view('faqs.create',compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $faqs = new Faq();
        $faqs->course_id = $id;
        $faqs->question = $request->question;
        $faqs->answer = $request->answer;
        $faqs->save();
        return redirect()->back()->with('success', __('Created successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit($faq,$course_id)
    {
        $faq = Faq::find($faq);
        return view('faqs.edit',compact('faq','course_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $faq_id,$course_id)
    {

        $faqs = Faq::find($faq_id);
        $faqs->question = $request->question;
        $faqs->answer = $request->answer;
        $faqs->update();
        return redirect()->back()->with('success', __('Updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy($faq_id,$course_id)
    {
        $faq = Faq::find($faq_id);
        $faq->delete();
        return redirect()->back()->with('success', __('Faq deleted successfully!'));
    }
}
