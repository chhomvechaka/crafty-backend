<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SellerAuthController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductController;

// Public routes
Route::get('/all-user', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes for general users
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{store}', [StoreController::class, 'show']); // Get a specific store by ID
    Route::get('/products/{id}', [ProductController::class, 'getProductById']);
});

// Admin routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware(['auth:sanctum', 'check-role:admin'])->group(function () {
    Route::get('/admin/user', [AdminAuthController::class, 'user']);
});

// Seller routes
Route::post('/seller/login', [SellerAuthController::class, 'sellerLogin']);
Route::post('/seller/logout', [SellerAuthController::class, 'sellerLogout'])->middleware('auth:sanctum');
Route::middleware(['auth:sanctum', 'check-role:seller'])->group(function () {
    Route::get('/seller/user', [SellerAuthController::class, 'currentSeller']);
});

// Store routes (only for sellers)

//Route::get('/stores/all', [StoreController::class, 'getAllStore']);
Route::middleware(['auth:sanctum', 'check-role:seller'])->group(function () {
    //Store routes (only for sellers)
    Route::get('/my-store', [StoreController::class, 'getStoresByUserId']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);

    //Product routes (only for sellers)
    // Route::post('/products', [ProductController::class, 'store']);
    Route::post('/store/{store}/products', [ProductController::class, 'storeProductsToStore']);
    Route::get('/store/{store}/products', [ProductController::class, 'getProductsByStore']);
});
