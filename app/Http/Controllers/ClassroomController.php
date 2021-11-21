<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizStatusStudent;
use App\Models\StdClassrooms;
use App\Models\Quizs;
use Illuminate\Support\Facades\DB;



class ClassroomController extends Controller
{
    public function classrooms(Request $request)
    {
        $studentId = $request->input('studentid');
        $classroom = StdClassrooms::join('students', 'std_classrooms.student_id', '=', 'students.id')
            ->join('classrooms', 'std_classrooms.classroom_id', '=', 'classrooms.id')
            ->select(
                'classrooms.id AS roomId',
                'classrooms.className',
                'students.name',
                'students.email',
                'students.student_id',
            )
            ->where('students.student_id', $studentId)
            ->get();
        return response()->json(['success' => true, 'payload' =>  $classroom]);
    }

    public function room(Request $request)
    {
        $studentId = $request->input('studentid');
        $roomId = $request->input('roomid');


        $classroom = Quizs::join('classrooms', 'quizs.classroom_id', '=', 'classrooms.id')
            ->leftJoin('quiz_status_students', 'quizs.id', '=', 'quiz_status_students.quiz_id')
            ->leftJoin('students', 'quiz_status_students.id', '=', 'students.id')
            ->select(
                'classrooms.id AS roomId',
                'classrooms.className',
                'quizs.language',
            )
            ->where('classrooms.id', $roomId)
            ->get();
        return response()->json(['success' => true, 'payload' => $classroom]);
    }
}
