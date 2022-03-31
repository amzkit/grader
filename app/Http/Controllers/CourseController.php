<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Classroom;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function insert(Request $request)
    {

        if (auth()->user()->role == 'teacher') {
            $courseWhere = [
                'course_name' => $request->course_name,
            ];

            $courseData = [
                'course_name' => $request->course_name,
            ];

            $courseDB = Course::updateOrCreate($courseWhere, $courseData);

            $classroomWhere = [
                'course_id' => $courseDB->id,
            ];

            $classroomData = [
                'course_id' => $courseDB->id,
                'user_id' => auth()->user()->id,
                'teacher' => 1
            ];

            Classroom::updateOrCreate($classroomWhere, $classroomData);
            return response()->json(['success' => true, 'payload' => $courseDB]);
        }
        return response()->json(['message' => "You don't teacher !!"]);
    }
}
