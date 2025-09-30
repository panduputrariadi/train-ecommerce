<?php

use App\Http\Auth\Controllers\CreateRegisterUserController;
use App\Http\Auth\Controllers\LoginUserController;
use App\Http\Auth\Controllers\LogOutUserController;
use App\Http\OTP\Controllers\SendOtpController;
use App\Http\OTP\Controllers\VerifyOtpController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [SendOtpController::class, 'send']);
Route::post('/verify-otp', [VerifyOtpController::class, 'verify']);
Route::post('/auth/register', [CreateRegisterUserController::class, 'register']);
Route::post('/auth/login', [LoginUserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/logout', [LogOutUserController::class, 'logout']);
});
