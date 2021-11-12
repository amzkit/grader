<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('manage-classroom', 'App\Http\Controllers\ManageClassroomController');

Route::resource('manage-std-classroom', 'App\Http\Controllers\ManageStdClassroomController');

Route::resource('classroom', 'App\Http\Controllers\ClassroomController');

Route::resource('languages', 'App\Http\Controllers\LanguagesController');

Route::resource('quiz', 'App\Http\Controllers\QuizsController');
