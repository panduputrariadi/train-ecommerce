<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/v1')->as('api.admin.v1')->group(base_path('routes/api/admin/v1.php'));
Route::prefix('user/v1')->as('api.user.v1')->group(base_path('routes/api/user/v1.php'));
