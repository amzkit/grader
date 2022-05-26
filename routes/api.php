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

Route::get('picture', 'App\Http\Controllers\AnnouncementController@getAnnounce');
Route::get('announce', 'App\Http\Controllers\AnnouncementController@getAnnounceByAdmin');
Route::post('announce', 'App\Http\Controllers\AnnouncementController@postAnnounce');
Route::post('announce/update', 'App\Http\Controllers\AnnouncementController@putAnnounce');
Route::delete('announce/{id}', 'App\Http\Controllers\AnnouncementController@deleteAnnounce');

Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

Route::put('user/{user_id}', 'App\Http\Controllers\UserController@update');
Route::post('user/file/upload', 'App\Http\Controllers\UserController@import');
Route::get('manage/user', 'App\Http\Controllers\UserController@getUser');
Route::put('manage/user', 'App\Http\Controllers\UserController@editUser');

Route::post('changePassword', 'App\Http\Controllers\Auth\ChangePasswordController@changePassword');

Route::get('problem', 'App\Http\Controllers\ProblemsController@getProblem');
Route::post('problem', 'App\Http\Controllers\ProblemsController@insert');
Route::post('problem/update', 'App\Http\Controllers\ProblemsController@updateProblem');
Route::delete('problem/{id}', 'App\Http\Controllers\ProblemsController@delProblem');

Route::get('user-problems/{id}', 'App\Http\Controllers\ProblemsController@getProblemGuestById');

Route::get('classroom', 'App\Http\Controllers\ClassroomController@getClassrooms');
Route::get('classrooms', 'App\Http\Controllers\ClassroomController@getClassroomsAll');

Route::get('schedule', 'App\Http\Controllers\ScheduleController@getSchedule');
Route::get('schedule/{id}', 'App\Http\Controllers\ScheduleController@getScheduleById');
Route::get('schedule/download/{file_name}', 'App\Http\Controllers\ProblemsController@download');

Route::get('user-schedule', 'App\Http\Controllers\ScheduleController@getScheduleGuest');
Route::get('user-schedule/{id}', 'App\Http\Controllers\ScheduleController@getScheduleGuestById');

Route::get('manage/classroom', 'App\Http\Controllers\ClassroomController@getManageClassrooms');

Route::put('manage/classroom', 'App\Http\Controllers\ClassroomController@updateClassrooms');

Route::delete('manage/classroom/{id}', 'App\Http\Controllers\ClassroomController@delClassrooms');

Route::post('manage/example', 'App\Http\Controllers\ScheduleController@insertManageExample');
Route::put('manage/example', 'App\Http\Controllers\ScheduleController@updateManageExample');
Route::delete('manage/example/{id}', 'App\Http\Controllers\ScheduleController@deleteManageExample');

Route::post('test-case', 'App\Http\Controllers\TestcaseController@insert');
Route::get('test-case/{id}', 'App\Http\Controllers\TestcaseController@get');
Route::delete('test-case/{id}', 'App\Http\Controllers\TestcaseController@delete');
Route::post('test-case/update', 'App\Http\Controllers\TestcaseController@update');


Route::post('course', 'App\Http\Controllers\CourseController@insert');

Route::post('submission', 'App\Http\Controllers\SubmissionController@submission');
Route::get('submission', 'App\Http\Controllers\SubmissionController@getSubmission');
Route::get('submission/scoreboard', 'App\Http\Controllers\SubmissionController@getSubmissionById');

Route::get('language', 'App\Http\Controllers\LanguageController@getLanguage');
Route::post('language', 'App\Http\Controllers\LanguageController@insert');

Route::get('score', 'App\Http\Controllers\AnalysesController@getScore');
Route::get('score-guest', 'App\Http\Controllers\AnalysesController@getScoreGuest');

Route::get('scoreboard/{id}', 'App\Http\Controllers\SubmissionController@getScoreBoard');
Route::get('user-scoreboard/{id}', 'App\Http\Controllers\SubmissionController@getScoreBoardGuest');

Route::post('comment', 'App\Http\Controllers\CommentController@insertComment');
Route::get('comment', 'App\Http\Controllers\CommentController@getComment');
