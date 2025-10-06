<?php

use App\Http\Admin\Controllers\CategoryController;
use App\Http\Admin\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/product-categories', [CategoryController::class, 'createCategory']);
    Route::get('/admin/product-categories', [CategoryController::class, 'getDataCategory']);
    Route::get('/admin/product-categories/{id}', [CategoryController::class, 'getCategoryDetail']);
    Route::patch('/admin/product-categories/{id}', [CategoryController::class, 'updateCategory']);

    Route::post('/admin/products', [ProductController::class, 'createProduct']);
    Route::get('/admin/products', [ProductController::class, 'getProduct']);
    Route::get('/admin/products/{code}', [ProductController::class, 'getDetailProduct']);
    Route::patch('/admin/products/{code}', [ProductController::class, 'updateProduct']);
    Route::delete('/admin/products/{code}', [ProductController::class, 'deleteProduct']);
});
