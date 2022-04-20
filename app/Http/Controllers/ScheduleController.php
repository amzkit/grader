<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Problem;
use Carbon\Carbon;
use App\Models\Classroom;

class ScheduleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSchedule()
    {
        // $course_id = $request->course_id;
        $user_id = auth()->user()->id;

        $classroom = Classroom::where('user_id', '=',  $user_id)
            ->join("courses", "courses.id", "=", "classrooms.course_id")
            ->select("courses.*")
            ->get();

        return response()->json(['success' => true, 'payload' =>  $classroom]);
    }

    public function getScheduleById($id)
    {

        $schedule = Schedule::where('course_id', '=',  $id)
            ->join("problems", "problems.id", "=", "schedules.problem_id")
            ->select(
                "schedules.id",
                "problems.title",
                "problems.question",
                "problems.level",
                "problems.score",
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

            $scheduleWhere = [
                'course_id'  => $request->roomId,
                'problem_id' =>  $exampleId
            ];

            $scheduleData = [
                'start_date'  =>  $request->start_date,
                'end_date'  =>  $request->end_date,
                'problem_id' =>  $exampleId
            ];

            $schedule = Schedule::updateOrCreate($scheduleWhere, $scheduleData);
        }
        return response()->json(['success' => true, 'payload' => $schedule]);
    }

    public function updateManageExample(Request $request)
    {
        $schedule = Schedule::where("id", $request->id)->first();
        $schedule->update(
            [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]
        );
        return response()->json(['success' => true, 'payload' => $schedule]);
    }

    public function deleteManageExample($id)
    {
        if (Schedule::where('id', $id)->exists()) {
            Schedule::find($id)->delete();
            return response()->json([
                "message" => "records delete successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }
}
