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

    public function insertManageExample(Request $request)
    {
        foreach ($request->exampleId as $exampleId) {

            $sheduleWhere = [
                'course_id'  => $request->roomId,
                'problem_id' =>  $exampleId
            ];

            $sheduleData = [
                'start_date'  =>  $request->start_date,
                'end_date'  =>  $request->end_date,
                'problem_id' =>  $exampleId
            ];

            $shedule = Schedule::updateOrCreate($sheduleWhere, $sheduleData);

        }
        return response()->json(['success' => true, 'payload' => $shedule]);
    }
}
