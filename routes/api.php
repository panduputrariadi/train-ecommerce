<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/v1')->as('api.admin.v1.')->group(function () {
    require __DIR__.'/api/admin/v1.php';
});
Route::prefix('common/v1')->as('api.common.v1.')->group(function () {
    require __DIR__.'/api/common/v1.php';
});
Route::prefix('auth/v1')->as('api.auth.v1.')->group(function () {
    require __DIR__.'/api/auth/v1.php';
});
Route::prefix('user/v1')->as('api.user.v1.')->group(function () {
    require __DIR__.'/api/user/v1.php';
});
