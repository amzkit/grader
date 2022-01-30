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

    public function getManageClassrooms(Request $request)
    {
        $courseId = $request->course_id;

        $item = Classroom::where('course_id', '=', $courseId)
            ->join('users', 'classrooms.user_id', '=', 'users.id')
            ->select('classrooms.*', 'users.name')
            ->get();



        return response()->json(['success' => true, 'payload' =>  $item]);
    }

    public function updateClassrooms(Request $request, $id)
    {
        if (Classroom::where('user_id', $id)->where('course_id', $request->course_id)->exists()) {
            Classroom::where('user_id', $id)
                ->where('course_id', $request->course_id)
                ->update(['role' => $request->role]);
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Student not found"
            ], 404);
        }
    }

    public function delClassrooms($stdId, $courseId)
    {
        if (Classroom::where('user_id', $stdId)->where('course_id', $courseId)->exists()) {
            Classroom::where('user_id', $stdId)
                ->where('course_id', $courseId)
                ->delete();
            return response()->json([
                "message" => "records delete successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Student not found"
            ], 404);
        }
    }
}
