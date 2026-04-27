<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\VariantController;

Route::middleware('auth:sanctum')->group(function () {
    // Admin product management endpoints
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::get('/products/{id}/edit', [ProductController::class, 'edit']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Variant management endpoints
        Route::get('/products/{productId}/variants', [VariantController::class, 'index']);
        Route::post('/products/{productId}/variants', [VariantController::class, 'store']);
        Route::get('/products/{productId}/variants/{variantId}', [VariantController::class, 'show']);
        Route::put('/products/{productId}/variants/{variantId}', [VariantController::class, 'update']);
        Route::delete('/products/{productId}/variants/{variantId}', [VariantController::class, 'destroy']);
    });
});

// Public endpoints
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
