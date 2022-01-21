<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Problem;

class ScheduleController extends Controller
{
    //
    public function getSchedule(Request $request)
    {
        $course_id = $request->course_id;

        $schedule = Schedule::where('course_id', '=',  $course_id)
            ->leftJoin("problems", "problems.id", "=", "schedules.problem_id")
            ->select(
                "problems.title",
                "problems.question",
                "problems.score",
                "problems.language",
                "problems.file",
                "schedules.start_date",
                "schedules.end_date",
                "problems.id as problemsId",
            )
            ->get();

        return response()->json(['success' => true, 'payload' =>  $schedule]);
    }
}
