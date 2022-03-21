<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function insert(Request $request)
    {
        $courseWhere = [
            'course_name' => $request->course_name,
        ];

        $courseData = [
            'course_name' => $request->course_name,
        ];

        $courseDB = Course::updateOrCreate($courseWhere, $courseData);
        return response()->json(['success' => true, 'payload' => $courseDB]);
    }
}
