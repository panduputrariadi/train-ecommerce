<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/v1')->as('api.admin.v1.')->group(function () {
    require __DIR__.'/api/admin/v1.php';
});
Route::prefix('user/v1')->as('api.user.v1.')->group(function () {
    require __DIR__.'/api/user/v1.php';
});
