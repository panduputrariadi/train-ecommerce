<?php

use App\Http\OTP\Controllers\SendOtpController;
use App\Http\OTP\Controllers\VerifyOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-otp', [SendOtpController::class, 'send']);
Route::post('/verify-otp', [VerifyOtpController::class, 'verify']);
