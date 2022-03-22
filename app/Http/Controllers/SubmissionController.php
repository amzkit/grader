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

    public function getSubmission(Request $request)
    {
        $schedule = Submission::where('user_id', '=',  auth()->user()->id)
            ->join('schedules', 'submissions.schedule_id', '=', 'schedules.id')
            ->join('problems', 'submissions.problem_id', '=', 'problems.id')
            ->where('course_id', '=', $request->course_id)
            ->get();
        return response()->json(['success' => true, 'payload' =>  $schedule]);
    }


    public function submission(Request $request)
    {
        // add date check $request->input('schedule_id');


        $now = Carbon::now()->toDateTimeString();
        $passdue = Schedule::where('problem_id', '=', $request->input('problem_id'))
            ->where('course_id', '=', $request->input('course_id'))
            ->first();


        // if ($passdue->end_date <= $now) {
        //     return response()->json([
        //         "message" => "The submission of this task is no longer allowed! +_+"
        //     ], 200);
        // }



        if ($request->hasFile('sourcefile')) {
            $submission = new Submission;
            $submission->problem_id = $request->input('problem_id');
            $submission->schedule_id = $passdue->id;
            $submission->Lang = $request->input('Lang');
            $submission->user_id = auth()->user()->id;
            $submission->IP = \Request::ip();
            $submission->score = 0.0;
            $submission->message = "waiting";
            $submission->compiler_message = "waiting";
            $submission->fname = $request->file('sourcefile')->getClientOriginalName();
            $submission->code = mb_convert_encoding(File::get($request->file('sourcefile')->getRealPath()), 'US-ASCII', 'UTF-8');
            $submission->save();

            $waitinglist = new Waitinglist;
            $waitinglist->submission_id = $submission->id;

            $waitinglist->save();

            return response()->json([
                'success', 'Scccessfully uploaded!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'File not found!!'
            ], 200);
        }
    }
}
