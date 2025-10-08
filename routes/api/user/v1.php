<?php

use App\Http\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/profile', [UserController::class, 'getProfile']);
    Route::patch('/auth/profile', [UserController::class, 'updateUser']);
    Route::patch('/auth/password', [UserController::class, 'changePassword']);
});
