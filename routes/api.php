<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password',  [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);        // list + search + pagination
    Route::get('/users/{id}', [UserController::class, 'show']);    // detail user
    Route::put('/user', [UserController::class, 'update']);        // update profile
    Route::post('/user/avatar', [UserController::class, 'uploadAvatar']); // upload avatar
});


Route::get('/test-mail', function() {
    \Mail::raw('This is a test email', function($message) {
        $message->to('ahmadshofihasibuan@gmail.com')
                ->subject('Test Mail');
    });
    return 'Email sent';
});
