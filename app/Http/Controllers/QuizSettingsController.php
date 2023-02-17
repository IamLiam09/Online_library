<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\QuizSettings;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quiz    = QuizSettings::where('created_by', \Auth::user()->creatorId())->get();
        return view('setquiz.index',compact('quiz'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::all();
        return view('setquiz.create',compact('category'));
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
                               'instructions' => 'required|max:255',
                               'min_percentage' => 'required',
                               'category' => 'required',
                               'subcategory' => 'required',
                           ]
        );
        $quiz = new QuizSettings();
        $time        = isset($request->time) ? 'on' : 'off';
        $q_review    = isset($request->question_review) ? 'on' : 'off';
        $result_submit    = isset($request->result_after_submit) ? 'on' : 'off';
        $random_questions = isset($request->random_questions) ? 'on' : 'off';
        $quiz->title = $request->title;
        $quiz->instructions = $request->instructions;
        $quiz->min_percentage = $request->min_percentage;
        $quiz->category = $request->category;
        $quiz->sub_category = $request->subcategory;
        $quiz->min_percentage = $request->min_percentage;
        if($time == 'on'){
            $validator = \Validator::make($request->all(), ['per_time_count' => 'required',]);
            $quiz->time = 'on';
            $quiz->per_question_time = $request->per_question_time;
        }else{
            $validator = \Validator::make($request->all(), ['total_time' => 'required',]);
            $quiz->total_time = $request->total_time;
            $quiz->time = 'off';
        }
        if($q_review == 'off'){
            $quiz->review = 'off';
        }else{
            $quiz->review = 'on';
        }
        if($result_submit == 'off'){
            $quiz->	result_after_submit = 'off';
        }else{
            $quiz->	result_after_submit = 'on';
        }
        if($random_questions == 'off'){
            $quiz->	random_questions = 'off';
        }else{
            $quiz->	random_questions = 'on';
        }
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
        }
        $quiz->created_by = Auth::user()->creatorId();
        $quiz->save();
        return redirect()->back()->with('success', __('Quiz set successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuizSettings  $quizSettings
     * @return \Illuminate\Http\Response
     */
    public function show(QuizSettings $quizSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuizSettings  $quizSettings
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz = QuizSettings::find($id);
        $category    = Category::where('created_by', \Auth::user()->creatorId())->get()->pluck('name','id');
        return view('setquiz.edit',compact('category','quiz'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuizSettings  $quizSettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quiz = QuizSettings::find($id);
        $validator = \Validator::make(
            $request->all(), [
                               'title' => 'required|max:120',
                               'instructions' => 'required|max:255',
                               'min_percentage' => 'required',
                               'category' => 'required',
                               'subcategory' => 'required',
                           ]
        );
        $time        = isset($request->time) ? 'on' : 'null';
        $q_review    = isset($request->question_review) ? 'on' : 'null';
        $result_submit    = isset($request->result_after_submit) ? 'on' : 'null';
        $random_questions = isset($request->random_questions) ? 'on' : 'null';
        $quiz->title = $request->title;
        $quiz->instructions = $request->instructions;
        $quiz->min_percentage = $request->min_percentage;
        $quiz->category = $request->category;
        $quiz->sub_category = $request->subcategory;
        $quiz->min_percentage = $request->min_percentage;
        if($time == 'on'){
            $validator = \Validator::make($request->all(), ['per_time_count' => 'required',]);
            $quiz->time = 'on';
            $quiz->per_question_time = $request->per_question_time;
        }else{
            $validator = \Validator::make($request->all(), ['total_time' => 'required',]);
            $quiz->total_time = $request->total_time;
            $quiz->time = 'null';
        }
        if($q_review == 'null'){
            $quiz->review = 'null';
        }else{
            $quiz->review = 'on';
        }
        if($result_submit == 'null'){
            $quiz->	result_after_submit = 'null';
        }else{
            $quiz->	result_after_submit = 'on';
        }
        if($random_questions == 'null'){
            $quiz->	random_questions = 'null';
        }else{
            $quiz->	random_questions = 'on';
        }
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
        }
        $quiz->created_by = Auth::user()->creatorId();
        $quiz->update();
        return redirect()->back()->with('success', __('Quiz updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuizSettings  $quizSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = QuizSettings::find($id);
        $quiz->delete();
        return redirect()->back()->with('success', __('Quiz deleted successfully.'));
    }

}
