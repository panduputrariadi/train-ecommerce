<?php

use App\Http\Common\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);
