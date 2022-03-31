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
    return response()->json(['success' => true, 'user' => $request->user()]);
});

Route::put('user/{user_id}', 'App\Http\Controllers\UserController@update');
Route::post('user/file/upload', 'App\Http\Controllers\UserController@import');
Route::post('changePassword', 'App\Http\Controllers\Auth\ChangePasswordController@changePassword');

Route::get('problem', 'App\Http\Controllers\ProblemsController@getProblem');
Route::post('problem', 'App\Http\Controllers\ProblemsController@insert');
Route::post('problem/update', 'App\Http\Controllers\ProblemsController@updateProblem');
Route::delete('problem/{id}', 'App\Http\Controllers\ProblemsController@delProblem');

Route::get('classroom', 'App\Http\Controllers\ClassroomController@getClassrooms');
Route::get('schedule', 'App\Http\Controllers\ScheduleController@getSchedule');
Route::get('schedule/download/{file_name}', 'App\Http\Controllers\ProblemsController@download');

Route::get('manage/user', 'App\Http\Controllers\UserController@getUser');

Route::get('manage/classroom', 'App\Http\Controllers\ClassroomController@getManageClassrooms');

Route::put('manage/classroom', 'App\Http\Controllers\ClassroomController@updateClassrooms');

Route::delete('manage/classroom/{id}', 'App\Http\Controllers\ClassroomController@delClassrooms');

Route::post('manage/example', 'App\Http\Controllers\ScheduleController@insertManageExample');
Route::put('manage/example', 'App\Http\Controllers\ScheduleController@updateManageExample');
Route::delete('manage/example/{id}', 'App\Http\Controllers\ScheduleController@deleteManageExample');

Route::post('test-case', 'App\Http\Controllers\TestcaseController@insert');

Route::post('course', 'App\Http\Controllers\CourseController@insert');

Route::post('submission', 'App\Http\Controllers\SubmissionController@submission');
Route::get('submission', 'App\Http\Controllers\SubmissionController@getSubmission');
Route::get('submission/{id}', 'App\Http\Controllers\SubmissionController@getSubmissionById');

Route::get('language', 'App\Http\Controllers\LanguageController@getLanguage');
Route::post('language', 'App\Http\Controllers\LanguageController@insert');

Route::get('score', 'App\Http\Controllers\AnalysesController@getScore');

Route::get('scoreboard', 'App\Http\Controllers\SubmissionController@getScoreBoard');

Route::post('comment', 'App\Http\Controllers\CommentController@insertComment');
