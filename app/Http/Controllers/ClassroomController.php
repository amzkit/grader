<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizStatusStudent;
use App\Models\StdClassrooms;
use App\Models\Quizs;
use App\Models\Classrooms;

use Illuminate\Support\Facades\DB;



class ClassroomController extends Controller
{
    public function classrooms(Request $request)
    {
        $studentId = $request->input('studentid');

        $classroom = StdClassrooms::leftJoin('students', 'std_classrooms.student_id', '=', 'students.id')
            ->join('classrooms', 'std_classrooms.classroom_id', '=', 'classrooms.id')
            ->select(
                'classrooms.id AS roomId',
                'classrooms.className',
                'students.name',
                'students.email',
                'students.student_id',
            );

        if ($studentId == 'admin') {
            return response()->json(['success' => true, 'payload' =>  Classrooms::select('id AS roomId', 'className')->get()]);
        }
        return response()->json(['success' => true, 'payload' =>  $classroom->where('students.student_id', $studentId)->get()]);
    }

    public function room(Request $request)
    {
        $studentId = $request->input('studentid');
        $roomId = $request->input('roomid');

        $classroom = Quizs::join('classrooms', 'quizs.classroom_id', '=', 'classrooms.id')
            ->leftJoin('quiz_status_students', 'quizs.id', '=', 'quiz_status_students.quiz_id')
            ->select(
                [
                    'classrooms.id AS roomId',
                    'quizs.id AS quizId',
                    'classrooms.className',
                    'quizs.language',
                    'quizs.score',
                    'quizs.send_start_work',
                    'quizs.send_end_work',
                    'quizs.subject_file_path',
                    'quizs.subject_name',
                    'quizs.work_name',
                ]
            )
            ->where('classrooms.id', $roomId)
            ->get();

        $status = QuizStatusStudent::join('classrooms', 'quiz_status_students.classroom_id', '=', 'classrooms.id')
            ->join('students', 'quiz_status_students.student_id', '=', 'students.id')
            ->leftJoin('quizs', 'quiz_status_students.quiz_id', '=', 'quizs.id')
            ->select(
                [
                    'quizs.id AS quizId',
                    'quiz_status_students.status',
                    'students.student_id'
                ]
            )
            ->where('students.student_id', $studentId)
            ->where('classrooms.id', $roomId)
            ->get();

        if ($studentId == 'admin') {
            return response()->json(['success' => true, 'payload' =>  $classroom]);
        }
        return response()->json(['success' => true, 'payload' =>  $classroom, 'status' => $status]);
    }
}
