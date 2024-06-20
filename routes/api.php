<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SellerAuthController;





// In routes/api.php

// Public route for login
Route::get('/all-user',[UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);
Route::post('/login', [UserController::class, 'login']);
// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);

});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', [AdminAuthController::class, 'index']);
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [SellerAuthController::class, 'index']);
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/admin/user', [AdminAuthController::class, 'user'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/admin/user', [AdminAuthController::class, 'user']);

/*
|--------------------------------------------------------------------------
| SELLER
|--------------------------------------------------------------------------
*/
// Public route for seller login, not requiring seller middleware but still using Sanctum for auth initiation
Route::post('/seller/login', [SellerAuthController::class, 'sellerLogin']);
Route::post('/seller/logout', [SellerAuthController::class, 'sellerLogout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/seller/user', [SellerAuthController::class, 'currentSeller']);




