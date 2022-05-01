<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Problem;
use Carbon\Carbon;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Submission;
use App\Models\Language;

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

        $language = Language::where('id', '=',  $schedules->language_id)->first();

        $schedule = [
            'id' => $schedules->id,
            'title' => $problems->title,
            'question' => $problems->question,
            'level' => $problems->level,
            'score' => $schedules->score,
            'file' => $problems->file,
            'start_date' => $schedules->start_date,
            'end_date' => $schedules->end_date,
            'problemsId' => $problems->id,
            'course_id' => $schedules->course_id,
            'language' => $language->lang,
            'language_accept' => $language->type,
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
            ->join("languages", "languages.id", "=", "schedules.language_id")
            ->where('schedules.IsActive', '=', 1)
            ->select(
                "schedules.id",
                "problems.title",
                "problems.question",
                "problems.level",
                "problems.file",
                "schedules.score",
                "schedules.start_date",
                "schedules.end_date",
                "problems.id as problemsId",
                "schedules.language_id",
                "languages.lang",
                "schedules.late",
                "languages.type",
                "schedules.IsAnalysis",
            )
            ->get();

        return response()->json(['success' => true, 'payload' =>  $schedule]);
    }

    public function insertManageExample(Request $request)
    {
        foreach ($request->exampleId as $exampleId) {

            $schedule = [
                'course_id'  => $request->roomId,
                'problem_id' =>  $exampleId,
                'start_date'  =>  $request->start_date,
                'end_date'  =>  $request->end_date,
                'problem_id' =>  $exampleId,
                'score' =>  $request->score,
                'late' => $request->late,
                'IsAnalysis' =>  $request->IsAnalysis,
                'language_id' => $request->language_id,

            ];

            $schedule = Schedule::create($schedule);
        }
        return response()->json(['success' => true, 'payload' => $schedule]);
    }

    public function updateManageExample(Request $request)
    {
        $schedule = Schedule::where("id", $request->id)->first();
        $schedule->update(
            [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'score' =>  $request->score,
                'late' => $request->late,
                'IsAnalysis' =>  $request->IsAnalysis,
                'language_id' => $request->language_id,
            ]
        );
        return response()->json(['success' => true, 'payload' => $schedule]);
    }

    public function deleteManageExample($id)
    {

        $schedule = Schedule::where("id", $id)->first();
        $schedule->update(
            [
                'IsActive' => 0
            ]
        );
        return response()->json(['success' => true]);
    }
}
