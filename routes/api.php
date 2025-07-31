<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public product and category routes
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('vendors', VendorController::class);
    });
    
    // Vendor routes
    Route::middleware('role:vendor')->group(function () {
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::get('/vendor/products', [ProductController::class, 'vendorProducts']);
        Route::get('/vendor/orders', [OrderController::class, 'vendorOrders']);
    });
    
    // Customer and vendor routes
    Route::apiResource('orders', OrderController::class);
    
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [OrderController::class, 'getCart']);
        Route::post('/add', [OrderController::class, 'addToCart']);
        Route::put('/update/{id}', [OrderController::class, 'updateCartItem']);
        Route::delete('/remove/{id}', [OrderController::class, 'removeFromCart']);
        Route::delete('/clear', [OrderController::class, 'clearCart']);
    });
});