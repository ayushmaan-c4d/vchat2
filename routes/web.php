<?php

use App\Http\Controllers\VideoChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/video-chat', [VideoChatController::class, 'index'])->name('video-chat');
    Route::post('/video/call-user', [VideoChatController::class, 'callUser']);
    Route::post('/video/accept-call', [VideoChatController::class, 'acceptCall']);
});