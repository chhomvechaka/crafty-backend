<?php

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\UserController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\SellerAuthController;
use App\Http\Middleware\CheckRole;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
|--------------------------------------------------------------------------
| FIREBASE
|--------------------------------------------------------------------------
*/
use Kreait\Firebase\Exception\FirebaseException;

Route::get('/test-firebase', function () {
    $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
    $auth = $factory->createAuth();

    try {
        $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        $totalUsers = 0;
        foreach ($users as $user) {
            $totalUsers++;
        }
        return response()->json([
            'message' => 'Firebase is working!',
            'total_users' => $totalUsers
        ]);
    } catch (FirebaseException $e) {
        return response()->json([
            'error' => 'Firebase SDK exception',
            'message' => $e->getMessage()
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'General error',
            'message' => $e->getMessage()
        ], 500);
    }
});



/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

Route::get('/user',[UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

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
// Route to handle store creation
Route::post('/seller/store', [SellerAuthController::class, 'createStore'])->middleware('auth:sanctum');
Route::post('/seller/login', [SellerAuthController::class, 'login']);

// Route to test DigitalOcean Spaces connectivity
Route::get('/test-dospaces', [SellerAuthController::class, 'testDigitalOceanSpaces'])->middleware('auth:sanctum');

// Google OAuth
Route::get('login/google',[SellerAuthController::class,'redirectToGoogle'] );
Route::get('login/google/callback', [SellerAuthController::class,'handleGoogleCallback']);


