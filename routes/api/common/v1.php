<?php

use App\Http\Common\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [OtpController::class, 'sendOtp'])->middleware('throttle:send-otp');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/auth/forget-password', [OtpController::class, 'sendOtpForgetPassword'])->middleware('throttle:forget-password-otp');
Route::post('/auth/reset-password', [OtpController::class, 'resetPassword']);
