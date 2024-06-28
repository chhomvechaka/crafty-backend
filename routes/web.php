<?php

use Illuminate\Support\Facades\Route;
// Firebase
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*
|--------------------------------------------------------------------------
| FIREBASE
|--------------------------------------------------------------------------
*/

//Route::get('/test-firebase', function () {
//    $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));
//    $auth = $factory->createAuth();
//
//    try {
//        $users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
//        $totalUsers = 0;
//        foreach ($users as $user) {
//            $totalUsers++;
//        }
//        return response()->json([
//            'message' => 'Firebase is working!',
//            'total_users' => $totalUsers
//        ]);
//    } catch (FirebaseException $e) {
//        return response()->json([
//            'error' => 'Firebase SDK exception',
//            'message' => $e->getMessage()
//        ], 500);
//    } catch (\Exception $e) {
//        return response()->json([
//            'error' => 'General error',
//            'message' => $e->getMessage()
//        ], 500);
//    }
//});





/*
|--------------------------------------------------------------------------
| TOKEN
|--------------------------------------------------------------------------
*/
// routes/web.php

Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

/*
|--------------------------------------------------------------------------
| SELLER
|--------------------------------------------------------------------------
*/
