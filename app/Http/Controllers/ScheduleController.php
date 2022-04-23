<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Problem;
use Carbon\Carbon;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Submission;



class ScheduleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getScheduleGuest()
    {

        $course_id = Course::where('course_name', '=', 'Guest')->first();

        $submissions = Submission::where('user_id', '=',  auth()->user()->id)
            ->select("submissions.schedule_id")
            ->get();

        $schedules = Schedule::where('course_id', '=',  $course_id->id)
            ->join("problems", "problems.id", "=", "schedules.problem_id")
            ->select(
                "schedules.id",
                "problems.title",
            )
            ->get();

        return response()->json(['success' => true, 'submissions' =>  $submissions, 'schedules' =>  $schedules]);
    }

    public function getScheduleGuestById($id)
    {

        $schedules = Schedule::where('id', '=',  $id)->first();
        $problems = Problem::where('id', '=',  $schedules->problem_id)->first();

        $schedule = [
            'id' => $schedules->id,
            'title' => $problems->title,
            'question' => $problems->question,
            'level' => $problems->level,
            'score' => $problems->score,
            'file' => $problems->file,
            'start_date' => $schedules->start_date,
            'end_date' => $schedules->end_date,
            'problemsId' => $problems->id,
            'course_id' => $schedules->course_id
        ];

        return response()->json(['success' => true, 'payload' =>  $schedule]);
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
