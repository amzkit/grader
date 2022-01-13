<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->save();
        return $request->input('role');
    }

    public function import(Request $request)
    {


        $validator = Validator::make(
            [
                'file'      => $request->import_file,
                'extension' => strtolower($request->import_file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:rtf',
            ]

        );

        $extension = $request->import_file->extension();
        $fileName = $request->import_file->getClientOriginalName();
        $import_file = $request->import_file;

        // Extract Data from imported file
        $request->import_file->storeAs('import_file/', $fileName);

        $file = file($import_file->getPathname());

        preg_match('/}{\\\hich\\\af19\\\dbch\\\af15\\\loch\\\f19\s\s}{\\\b\s\\\hich\\\af19\\\dbch\\\af15\\\loch\\\f19\s[0-9]}/', $file[0], $section);
        $section = explode('}', explode(' ', $section[0])[4])[0];

        preg_match_all('/\s\D{2}[0-9]{3}\s/', $file[0], $course);
        $course = (iconv('TIS-620', 'UTF-8//ignore', $course[0][0])); // Only work on b"xxxx" string
        $course = str_replace(' ', '', $course);
        preg_match_all('!\d+!', $course, $course_id);
        $course_id = $course_id[0][0];


        $file_data = (iconv('TIS-620', 'UTF-8//ignore', $file[0]));


        /////////////////////////////////////////////////////////////////////
        //  Extract Semester and Year
        preg_match('/ปีการศึกษา}{\\\b\\\fs32\s\\\hich\\\af19\\\dbch\\\af15\\\loch\\\f19\s\s[0-9]{1}\\/[0-9]{4}/', $file_data, $semester_year);
        $semester_year = explode('  ', $semester_year[0])[1];
        $semester = explode('/', $semester_year)[0];
        $year = explode('/', $semester_year)[1];

        /////////////////////////////////////////////////////////////////////
        //  Extract Student



        preg_match_all('/[0-9]{10}\\\cell\\\hich\\\af19\\\dbch\\\af15\\\loch\\\f19\s[^0-9]+}/', $file_data, $students);

        foreach ($students[0] as $i => $student) {
            $student_id = substr($student, 0, 10);
            $name = explode('\\f19 ', $student)[1];
            $name = explode('}', $name)[0];



            $userWhere = [
                'username'  => $student_id
            ];

            $userData = [
                'name'  =>  $name,
                'email' =>  $student_id . "@mju.ac.th",
                'role_student' =>  1,
                'password' => Hash::make($student_id),
            ];

            $user = User::updateOrCreate($userWhere, $userData);

            // $enrolWhere = [
            //     'user_id'   =>  $user->id,
            //     'course_id' =>  $course_id,
            // ];

            // $enrolData = [
            //     'section'   =>  $section,
            //     'year'      =>  $year,
            //     'semester'  =>  $semester
            // ];

            // //  Extract dates from request
            // $start_datetime = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->start_date.' '.$request->start_time);
            // $end_datetime = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->end_date.' '.$request->end_time);

            // if(isset($start_datetime) && $start_datetime != ''){
            //     $enrolData['start_datetime'] = $start_datetime;
            // }
            // if(isset($end_datetime) && $end_datetime != ''){
            //     $enrolData['end_datetime'] = $end_datetime;
            // }
            // //dd($enrolWhere, $enrolData);
            // \App\Enrollment::updateOrCreate($enrolWhere, $enrolData);

        }

        // \Alert::toast('<strong>Success</strong> : '.count($students[0]).' users on CS'.$course_id.' ('. $semester_year .') imported')->position($position = 'center')->timerProgressBar()->autoClose(3000);
        // return back();
    }
}
