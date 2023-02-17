<?php

namespace App\Http\Controllers;

use App\Models\QuizBank;
use App\Models\QuizSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use MongoDB\Driver\Session;

class QuizBankController extends Controller
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
        $quiz = QuizSettings::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        return view('quizbank.create', compact('quiz'));
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
        $quiz             = new QuizBank();
        $quiz->quiz       = $request->quiz;
        $quiz->question   = $request->question;
        $quiz->options    = implode(',',$request->options);
        $quiz->answer     = implode(',',array_keys($request->answer));
        $quiz->created_by = \Auth:: user()->creatorId();
        $quiz->save();

        return redirect()->back()->with('success', __('Quiz created successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\QuizBank $quizBank
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quiz = QuizBank::find($id);
        return view('quizbank.view',compact('quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\QuizBank $quizBank
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz   = QuizBank::find($id);
        $quizof = QuizSettings::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        return view('quizbank.edit', compact('quiz', 'quizof'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\QuizBank $quizBank
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quiz = QuizBank::find($id);
        $quiz->quiz       = $request->quiz;
        $quiz->question   = $request->question;
        $quiz->options    = implode(',', $request->options);
        $quiz->answer     = implode(',',array_keys($request->answer));
        $quiz->created_by = \Auth:: user()->creatorId();
        $quiz->update();

        return redirect()->back()->with('success', __('Quiz updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\QuizBank $quizBank
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = QuizBank::find($id);
        $quiz->delete();
        return redirect()->back()->with('success', __('Quiz deleted successfully.'));
    }

    public function viewcontent($id)
    {
        $id        = Crypt::decrypt($id);
        $quiz_name = QuizSettings::where('id', $id)->first();
        $quiz      = QuizBank::where('quiz', $id)->get();

        return view('quizbank.index', compact('quiz', 'quiz_name'));
    }
}
