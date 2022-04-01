<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Analyses;
use App\Models\Comment;

class AnalysesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getScore(Request $request)
    {

        $course_id = $request->course_id;

        $submissions = Submission::join("schedules", "schedules.id", "=", "submissions.schedule_id")
            ->join("problems", "problems.id", "=", "submissions.problem_id")
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', $request->user_id ? $request->user_id : auth()->user()->id)
            ->select(
                "problems.id as problem_id",
                "problems.title",
                "schedules.start_date",
                "schedules.end_date",
                "submissions.*"
            )
            ->get();

        $analyses = Analyses::join("submissions", "submissions.id", "=", "analyses.submission_id")
            ->join("testcases", "testcases.id", "=", "analyses.testcase_id")
            ->leftJoin("schedules", "schedules.id", "=", "submissions.schedule_id")
            ->where('course_id', '=', $course_id)
            ->select(
                "testcases.input as testcase_input",
                "testcases.output as testcase_output",
                "analyses.*"
            )
            ->get("analyses.*");

        $comment = Comment::join("submissions", "submissions.id", "=", "comments.submission_id")
            ->leftJoin("schedules", "schedules.id", "=", "submissions.schedule_id")
            ->where('course_id', '=', $course_id)
            ->get();

        return response()->json(
            [
                'success' => true,
                'payload' =>  $submissions,
                'payload2' =>  $analyses,
                'comment' => $comment
            ]
        );
    }
}
