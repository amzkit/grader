<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->save();
        return $request->input('role');
    }

    public function editUser(Request $request)
    {
        if (auth()->user()->role == 'teacher' || auth()->user()->role == 'admin') {
            $user = User::find($request->id);
            $user->name = $request->name;
            if ($request->resetPassword != "" || $request->resetPassword != null) {
                $user->password = Hash::make($request->resetPassword);
            }
            $user->save();
            return response()->json(['success' => true, 'payload' =>  $user]);
        }
        return response()->json(['message' => "Your don't Teacher"]);
    }

    public function getUser()
    {

        $user = User::where('role_admin', '!=', 1)->where('id', '!=', auth()->user()->id)->get();
        return response()->json(['success' => true, 'payload' =>  $user]);
    }

    public function import(Request $request)
    {
        if ($request->role === 'teacher') {

            if (User::where('username', $request->username)->first()) {
                return response()->json(['message' =>  "username duplicate"]);
            }

            $userWhere = [
                'username'  => $request->username
            ];
            $userData = [
                'email' =>  $request->email,
                'password' => Hash::make($request->password),
                'name'  =>  $request->name,
                'role_teacher' =>  $request->role === 'teacher' && 1,
                'role' =>  $request->role,
                'username' => $request->username
            ];
            $userDB = User::updateOrCreate($userWhere, $userData);

            return response()->json(['success' => true, 'payload' =>  $request]);
        }

        if ($request->addItem == true) {

            $classroomFirst = Classroom::where('course_id', '=', $request->course_id)->first();

            $classroomWhere = [
                'user_id'   =>  $request->user_id,
                'course_id' => $request->course_id,
            ];

            $classroomData = [
                'section'   =>  $request->section,
                'year'      =>  $request->year,
                'semester'  =>  $request->semester,
                'ta' => $request->ta,
                'teacher' => $request->teacher,
                'student' => $request->student,
                // "start_datetime" =>  $classroomFirst->start_datetime,
                // "end_datetime" =>  $classroomFirst->end_datetime,
            ];

            Classroom::updateOrCreate($classroomWhere, $classroomData);

            $user = User::find($request->user_id);

            $checkRole = Classroom::where('user_id', '=', $user->id);

            $classroomLengthRoleTA =  $checkRole->where('ta', '=', 1)->count();
            $classroomLengthRoleTC =  $checkRole->where('teacher', '=', 1)->count();
            $classroomLengthRoleSTD =  $checkRole->where('student', '=', 1)->count();

            $user->update([
                'role_ta' =>  $request->ta ? 1 : $classroomLengthRoleTA > 0 && $user->role_ta,
                'role_teacher' => $request->teacher ? 1 : $classroomLengthRoleTC > 0 && $user->role_teacher,
                'role_student' =>  $request->student ? 1 : $classroomLengthRoleSTD > 0 && $user->role_student,
            ]);
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


                $user =  DB::table('users')->where('username', '=', $student_id)->first();

                $userWhere = [
                    'username'  => $student_id
                ];

                $userData = [
                    'name'  =>  $name,
                    'email' =>  $student_id . "@mju.ac.th",
                    'role_student' =>  1,
                    'role' =>  "student",
                    'password' => $user ? $user->password : Hash::make($student_id),
                ];

                $userDB = User::updateOrCreate($userWhere, $userData);

                $classroomWhere = [
                    'user_id'   =>  $userDB->id,
                    'course_id' => $request->course_id,
                ];

                $classroomData = [
                    'section'   =>  $section,
                    'year'      =>  $year,
                    "student"      =>  1,
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
