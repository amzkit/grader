<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});


Route::get('logout', [\App\Http\Controllers\Auth\MJUOAuthController::class, 'logout']);

Auth::routes();

Route::get('login/mju/redirect', [\App\Http\Controllers\Auth\MJUOAuthController::class, 'redirect']);
Route::get('login/mju/callback', [\App\Http\Controllers\Auth\MJUOAuthController::class, 'callback']);
Route::get('login/mju/select_role', [\App\Http\Controllers\Auth\MJUOAuthController::class, 'selectRole'])->name('select_role');

////////////////////////
// Socialite
Route::get('login/{provider}/redirect', [\App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [\App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/{any?}', function(){
        return view('index');
    });
});
