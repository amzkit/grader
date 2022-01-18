<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\User;

class ClassroomController extends Controller
{
    //
    public function getClassrooms(Request $request)
    {
        $studentId = $request->studentid;
        $item = User::where('username', '=', $studentId)->first();

        if ($item->role !== 'ta' && $item->role !== 'student') {
            $classroom = Course::select(
                "courses.id as courseId",
                "courses.course_name",
            )->get();
        } else {
            $classroom = Classroom::select(
                "classrooms.id as classroomId",
                "courses.id as courseId",
                "courses.course_name",
            )->leftJoin("courses", "courses.id", "=", "classrooms.course_id")->where('user_id', '=',  $item->id)->get();
        }

        return response()->json(['success' => true, 'payload' =>  $classroom]);
    }
}
