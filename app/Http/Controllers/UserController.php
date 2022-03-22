<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Classroom;
use Carbon\Carbon;
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

    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        // $user->save();
        return $user;
    }

    public function getUser()
    {
        $user = User::get();
        return response()->json(['success' => true, 'payload' =>  $user]);
    }

    public function import(Request $request)
    {

        if ($request->role === 'student' || $request->role === 'ta') {

            if (User::where('username', $request->student_id)->first()) {
                $userDB = User::where('username', $request->student_id)->first();
                $userDB->update([
                    'role_ta' =>  $request->role === 'ta' ? 1 : $userDB->role_ta,
                    'role_student' =>  $request->role === 'student' ? 1 : $userDB->role_student,
                    'role' =>  $request->role,
                ]);
            } else {
                $userWhere = [
                    'username'  => $request->student_id
                ];
                $userData = [
                    'email' =>  $request->student_id . "@mju.ac.th",
                    'password' => Hash::make($request->student_id),
                    'name'  =>  $request->name,
                    'role_ta' =>  $request->role === 'ta' && 1,
                    'role_student' =>  $request->role === 'student' && 1,
                    'role' =>  $request->role,
                ];
                $userDB = User::updateOrCreate($userWhere, $userData);
            }

            $classroomWhere = [
                'user_id'   =>  $userDB->id,
                'course_id' => $request->course_id,
            ];

            $classroomData = [
                'section'   =>  $request->section,
                'year'      =>  $request->year,
                "role"      =>  $request->role,
                'semester'  =>  $request->semester
            ];

            $classroomDB = Classroom::updateOrCreate($classroomWhere, $classroomData);
            return response()->json(['success' => true, 'payload' =>  $request]);
        }

        if ($request->check_file == true) {

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
                    'role' =>  "student",
                    'password' => Hash::make($student_id),
                ];

                $userDB = User::updateOrCreate($userWhere, $userData);

                $classroomWhere = [
                    'user_id'   =>  $userDB->id,
                    'course_id' => $request->course_id,
                ];

                $classroomData = [
                    'section'   =>  $section,
                    'year'      =>  $year,
                    "role"      =>  "student",
                    'semester'  =>  $semester
                ];

                $classroomData['start_datetime'] =  $request->start_date !== '' ?  $request->start_date : NULL;
                $classroomData['end_datetime'] =  $request->end_date !== '' ?  $request->end_date : NULL;

                $classroomDB = Classroom::updateOrCreate($classroomWhere, $classroomData);
            }
        }
        return response()->json(['success' => $request]);
    }
}