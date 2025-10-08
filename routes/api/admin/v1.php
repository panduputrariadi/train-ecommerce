<?php

use App\Http\Admin\Controllers\CategoryController;
use App\Http\Admin\Controllers\CustomerController;
use App\Http\Admin\Controllers\DiscountController;
use App\Http\Admin\Controllers\ProductController;
use App\Modules\Share\Enum\UserRole;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:'.implode(',', UserRole::adminRoles())])->group(function () {
    Route::post('/admin/product-categories', [CategoryController::class, 'createCategory']);
    Route::get('/admin/product-categories', [CategoryController::class, 'getDataCategory']);
    Route::get('/admin/product-categories/{id}', [CategoryController::class, 'getCategoryDetail']);
    Route::patch('/admin/product-categories/{id}', [CategoryController::class, 'updateCategory']);

    Route::post('/admin/products', [ProductController::class, 'createProduct']);
    Route::get('/admin/products', [ProductController::class, 'getProduct']);
    Route::get('/admin/products/{product}', [ProductController::class, 'getDetailProduct']);
    Route::patch('/admin/products/{product}', [ProductController::class, 'updateProduct']);
    Route::delete('/admin/products/{product}', [ProductController::class, 'deleteProduct']);

    Route::post('/admin/discount', [DiscountController::class, 'createDiscount']);
    Route::get('/admin/discount', [DiscountController::class, 'getDiscount']);
    Route::post('admin/attach-discount', [DiscountController::class, 'attachDiscountToProducts']);

    Route::get('/admin/customers', [CustomerController::class, 'index']);
    Route::patch('/admin/customers/{profile}/block', [CustomerController::class, 'blockCustomer']);
    Route::patch('/admin/customers/{profile}/unblock', [CustomerController::class, 'unblockCustomer']);
});
