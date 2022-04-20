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

    public function getScoreBoard($id)
    {

        $classroom = Classroom::join('users', 'users.id', 'classrooms.user_id')
            ->where('course_id', '=', $id)
            ->where('student', '=', 1)
            ->select('classrooms.*', 'users.id as user_id', 'users.name', 'users.username')
            ->get();
        $schedules = Schedule::where('course_id', '=', $id)->get();
        $submission = Submission::join('schedules', 'schedules.id', 'submissions.schedule_id')
            ->where('course_id', '=', $id)->get();

        $course_name = Course::where('id', '=', $id)->first();

        $scoreboard = [
            'student_count' => $classroom->count(),
            'schedules_count' => $schedules->count(),
            'classroom_name' => $course_name->course_name,
            'classroom' => $classroom,
            'submission' => $submission,
            'schedules' => $schedules
        ];

        return response()->json(['success' => true, 'payload' => $scoreboard]);

        // $course_id = $request->course_id;

        // $schedule = Schedule::where('course_id', '=',  $course_id)
        //     ->join("problems", "problems.id", "=", "schedules.problem_id")
        //     ->join("languages", "languages.id", "=", "problems.language_id")
        //     ->select(
        //         "schedules.id",
        //         "problems.title",
        //         "problems.question",
        //         "problems.level",
        //         "problems.score",
        //         "languages.id as languageId",
        //         "languages.lang as language",
        //         "languages.type",
        //         "problems.file",
        //         "schedules.start_date",
        //         "schedules.end_date",
        //         "problems.id as problemsId",
        //     )
        //     ->get();

        // $scoreboard = Submission::join("schedules", "schedules.id", "=", "submissions.schedule_id")
        //     ->join("problems", "problems.id", "=", "submissions.problem_id")
        //     ->join("users", "users.id", "=", "submissions.user_id")
        //     ->where('course_id', '=', $course_id)
        //     ->select(
        //         "problems.id as problem_id",
        //         "problems.title",
        //         "schedules.start_date",
        //         "schedules.end_date",
        //         "submissions.*",
        //         "users.name",
        //         "users.username"
        //     )
        //     ->get();

        // return response()->json(
        //     [
        //         'success' => true,
        //         'payload' =>  $scoreboard,
        //         'payload2' =>  $schedule,
        //     ]
        // );
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
        if (Submission::where('problem_id', '=',  $request->problem_id)
            ->where('user_id', '=',  $request->user_id)
            ->where('schedule_id', '=',  $request->schedule_id)
        ) {
            $submission = Submission::where('problem_id', '=',  $request->problem_id)
                ->where('user_id', '=',  $request->user_id)
                ->where('schedule_id', '=',  $request->schedule_id)
                ->join("users", "users.id", "=", "submissions.user_id")
                ->join('problems', 'submissions.problem_id', '=', 'problems.id')
                ->select(
                    "submissions.*",
                    "users.name",
                    "users.username",
                    "problems.title",
                    "problems.question",
                    "problems.score",
                )
                ->first();
            return response()->json(['success' => true, 'payload' => $submission]);
        }
        return response()->json(['message' => 'Not Found!!']);
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
