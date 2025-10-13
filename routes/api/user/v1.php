<?php

use App\Http\Customer\Controllers\OrderController;
use App\Http\Customer\Controllers\PaymentController;
use App\Http\User\Controllers\UserController;
use App\Modules\Share\Enum\UserRole;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:'.implode(',', UserRole::isCustomer())])->group(function () {
    Route::get('/auth/profile', [UserController::class, 'getProfile']);
    Route::patch('/auth/profile', [UserController::class, 'updateUser']);
    Route::patch('/auth/password', [UserController::class, 'changePassword']);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'get']);
    Route::get('/orders/{order}/pdf', [OrderController::class, 'getOrderInvoice']);

    Route::post('/payments', [PaymentController::class, 'store']);
});
