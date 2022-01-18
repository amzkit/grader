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

Route::post('problem', 'App\Http\Controllers\ProblemsController@insert');

Route::get('classroom', 'App\Http\Controllers\ClassroomController@getClassrooms');
Route::get('schedule', 'App\Http\Controllers\ScheduleController@getSchedule');
Route::get('schedule/download/{file_name}', 'App\Http\Controllers\ProblemsController@download');
