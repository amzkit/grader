<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    if ($request->user()->student_id != null) {
        $classroom = DB::table('users')->join('students', 'users.student_id', '=', 'students.id')
            ->select(
                'users.email',
                'users.email_verified_at',
                'users.id',
                'users.name',
                'users.role',
                'students.student_id',
                'users.created_at',
                'users.updated_at'
            )
            ->first();
        return response()->json(['success' => true, 'user' => $classroom]);
    }
    return response()->json(['success' => true, 'user' => $request->user()]);
});

Route::get('classroom', 'App\Http\Controllers\ClassroomController@classrooms');
Route::get('classroom/room', 'App\Http\Controllers\ClassroomController@room');

// Route::get('classroom/{id}', 'App\Http\Controllers\ClassroomController@show');

// Route::resource('manage-classroom', 'App\Http\Controllers\ManageClassroomController');

// Route::resource('manage-std-classroom', 'App\Http\Controllers\ManageStdClassroomController');

// Route::resource('classroom', 'App\Http\Controllers\ClassroomController');

// Route::resource('languages', 'App\Http\Controllers\LanguagesController');

// Route::resource('quiz', 'App\Http\Controllers\QuizsController');

// Route::resource('status', 'App\Http\Controllers\StatusController');
