<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\Waitinglist;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function submission(Request $request)
    {
        // add date check $request->input('schedule_id');
        $now = Carbon::now()->toDateTimeString();
        $passdue = Schedule::where('id', '=', $request->input('schedule_id'))->where('end_time', '<=', $now)->count();
        if ($passdue > 0) {
            return redirect('/submission/' . $request->input('problem_id') . '/' . $request->input('schedule_id'))->withErrors(array('message' => 'The submission of this task is no longer allowed! +_+'));
        }

        if ($request->hasFile('sourcefile')) {

            $submission = new Submission;
            $submission->problem_id = $request->input('problem_id');
            $submission->schedule_id = $request->input('schedule_id');
            $submission->Lang = $request->input('Lang');
            $submission->user_id = auth()->user()->id;
            $submission->IP = Request::ip();
            $submission->score = 0.0;
            $submission->message = "waiting";
            $submission->compiler_message = "waiting";
            $submission->fname = $request->file('sourcefile')->getClientOriginalName();
            $submission->code = mb_convert_encoding(File::get($request->file('sourcefile')->getRealPath()), 'US-ASCII', 'UTF-8');
            $submission->save();

            $waitinglist = new Waitinglist;
            $waitinglist->submission_id = $submission->id;

            $user = User::find(auth()->user()->id);
            $user->lang = $request->input('Lang');
            $user->save();
            $waitinglist->save();


            return  redirect('/submission/' . $submission->id . '/edit')->with('success', 'Scccessfully uploaded!');
        } else {

            return redirect('/submission/' . $request->input('problem_id') . '/' . $request->input('schedule_id'))->withErrors(array('message' => 'File not found!!'));
        }
    }
}