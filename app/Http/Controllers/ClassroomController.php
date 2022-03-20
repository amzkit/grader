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
                "classrooms.role",
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
        if (Classroom::where('id', $id)->exists()) {
            $item = Classroom::where("id", $id)->first();

            $item->update(['role' => $request->role]);

            $user = User::where("id",  $item->user_id)->first();


            User::where("id",  $user->id)->update([
                'role_ta' =>  $request->role === 'ta' ? 1 : $user->role_ta,
                'role_student' =>  $request->role === 'student' ? 1 : $user->role_student,
                'role' =>  $request->role,
            ]);

            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Student not found"
            ], 404);
        }
    }

    public function delClassrooms($id)
    {
        if (Classroom::where('id', $id)->exists()) {
            Classroom::find($id)->delete();
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
