<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::prefix('email')->group(function () {
    Route::post('/send-code', [EmailController::class, 'sendCode']);
    Route::post('/verify-code', [EmailController::class, 'verifyCode']);
});

