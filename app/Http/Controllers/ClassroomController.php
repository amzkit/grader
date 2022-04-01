<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\User;

class ClassroomController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getClassrooms()
    {

        if (auth()->user()->role == 'admin') {
            $classroom = Course::select(
                "courses.id as courseId",
                "courses.course_name",
            )->get();
        } else {
            $classroom = Classroom::select(
                "classrooms.id as classroomId",
                "courses.id as courseId",
                "courses.course_name",
            )->leftJoin("courses", "courses.id", "=", "classrooms.course_id")
                ->where('user_id', '=', auth()->user()->id)
                ->get();
        }

        return response()->json(['success' => true, 'payload' =>  $classroom]);
    }

    public function getClassroomsAll()
    {
        if (auth()->user()->role == 'ta') {
            $classroom = Classroom::select(
                "classrooms.id as classroomId",
                "courses.id as courseId",
                "courses.course_name",
            )
                ->leftJoin("courses", "courses.id", "=", "classrooms.course_id")
                ->where('user_id', '=', auth()->user()->id)
                ->get();
        } else {
            $classroom = Course::select(
                "courses.id as courseId",
                "courses.course_name",
            )->get();
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

    public function updateClassrooms(Request $request)
    {
        $classroom = Classroom::find($request->id);

        if ($classroom) {
            $classroom->update(
                [
                    'ta' => $request->ta,
                    'teacher' => $request->teacher,
                    'student' => $request->student,
                    'section' => $request->section,
                    'semester' => $request->semester
                ]
            );
            $user = User::find($classroom->user_id);

            $checkRole = Classroom::where('user_id', '=', $user->id);

            $classroomLengthRoleTA =  $checkRole->where('ta', '=', 1)->count();
            $classroomLengthRoleTC =  $checkRole->where('teacher', '=', 1)->count();
            $classroomLengthRoleSTD =  $checkRole->where('student', '=', 1)->count();

            $user->update([
                'role_ta' =>  $request->ta ? 1 : $classroomLengthRoleTA > 0 && $user->role_ta,
                'role_teacher' => $request->teacher ? 1 : $classroomLengthRoleTC > 0 && $user->role_teacher,
                'role_student' =>  $request->student ? 1 : $classroomLengthRoleSTD > 0 && $user->role_student,
            ]);

            // if ($user->role == null || $user->role == "") {
            //     $user->update(['role' => $request->role]);
            // }
            return response()->json(['success' => true, 'payload' => $classroom]);
        }
        return response()->json(["message" => "Student not found"]);
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
