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
use App\Models\Scoreboard;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getScoreBoard($id)
    {


        $classroom = Classroom::join('users', 'users.id', 'classrooms.user_id')
            ->where('course_id', '=', $id)
            ->where('teacher', '!=', 1)
            ->select('users.id as user_id', 'users.name', 'users.username')
            ->get();

        $schedule = Schedule::where('course_id', '=', $id)
            ->join('problems', 'problems.id', 'schedules.problem_id')
            ->select('schedules.*', 'problems.title')
            ->get();

        $course_name = Course::where('id', '=', $id)->first();

        $scoreboard = Scoreboard::where('course_id', '=', $id)
            ->get();

        $res = [
            'student_count' => $classroom->count(),
            'schedule' => $schedule,
            'classroom_name' => $course_name->course_name,
            'classroom' => $classroom,
            'scoreboard' => $scoreboard
        ];

        return response()->json(['success' => true, 'payload' => $res]);
    }

    public function getScoreBoardGuest($id)
    {


        $items = Submission::join('users', 'users.id', 'submissions.user_id')
            ->join('problems', 'problems.id', 'submissions.problem_id')
            ->where('schedule_id', '=', $id)
            ->where('role', '=', "Guest")
            ->select(
                'users.name',
                'problems.score as problem_score',
                'submissions.created_at as date_send',
                'submissions.score as submission_score',
                'submissions.Lang',
            )
            ->orderBy('submissions.score', 'DESC')
            ->get();

        $scoreboard = [
            'count' => $items->count(),
            'scoreboard' => $items,
        ];

        return response()->json(['success' => true, 'payload' => $scoreboard]);
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

    public function getSubmissionById(Request $request)
    {
        $submission = Submission::where('id', '=',  $request->submission_id)
            ->first();

        $problems = Problem::where('id', '=',  $request->problem_id)
            ->first();

        $res = [
            'submission' => $submission,
            'problems' => $problems,
        ];

        return response()->json(['success' => true, 'payload' => $res]);
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
            $submission->output = "";
            $submission->message = "waiting";
            $submission->compiler_message = "waiting";
            $submission->fname = $request->file('sourcefile')->getClientOriginalName();
            $submission->code = mb_convert_encoding(File::get($request->file('sourcefile')->getRealPath()), 'US-ASCII', 'UTF-8');
            $submission->save();

            $waitinglist = new Waitinglist;
            $waitinglist->submission_id = $submission->id;

            $waitinglist->save();

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => 'File not found!!'
            ], 200);
        }
    }
}
