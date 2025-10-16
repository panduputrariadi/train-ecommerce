<?php

use App\Http\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('auth/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('admin/login', [AuthController::class, 'loginAdmin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/logout', [AuthController::class, 'logout']);
});
