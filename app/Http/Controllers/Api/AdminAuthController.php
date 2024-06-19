<?php
//
//namespace App\Http\Controllers\Api;
//
//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;
//use Illuminate\Validation\ValidationException;
//
//class AdminAuthController extends Controller
//{
//    /**
//     * Handle an attempt to log in as an admin.
//     */
//    public function login(Request $request)
//    {
//        $request->validate([
//            'email' => 'required|email',
//            'password' => 'required|string|min:6',
//        ]);
//
//        try {
//            $credentials = $request->only('email', 'password');
//            if (Auth::attempt($credentials)) {
//                $user = Auth::user();
//                if ($user->isAdmin()) {
//                    $token = $user->createToken('admin-access-token')->plainTextToken;
//                    return response()->json([
//                        'message' => 'Logged in successfully as admin.',
//                        'access_token' => $token,
//                        'user' => $user
//                    ], 200);
//                } else {
//                    Auth::logout();
//                    return response()->json(['message' => 'Access denied. Not an admin.'], 403);
//                }
//            }
//            throw ValidationException::withMessages([
//                'email' => [trans('auth.failed')],
//            ]);
//        } catch (\Exception $e) {
//            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
//        }
//    }
//
//    /**
//     * Log out the admin from the application.
//     */
//    public function logout(Request $request)
//    {
//        $user = Auth::user();
//
//        if ($user) {
//            // Revoke the current token
//            $user->currentAccessToken()->delete();
//            return response()->json(['message' => 'Successfully logged out'], 200);
//        }
//
//        return response()->json(['message' => 'No user currently logged in'], 404);
//    }
//
//    /**
//     * Get the current authenticated user's information.
//     */
//    public function user()
//    {
//        $user = Auth::user();
//        return response()->json($user);
//    }
//}

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    /**
     * Handle an attempt to log in as an admin.
     */
    public function login(Request $request)
    {
        try {
            // Check if a user is already logged in


            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->isAdmin()) {
                    $token = $user->createToken('admin-access-token')->plainTextToken;
                    Log::info('Admin logged in: ' . $user->email); // Log successful login
                    return response()->json([
                        'message' => 'Logged in successfully as admin.',
                        'access_token' => $token,
                        'user' => $user
                    ], 200);
                } else {
                    Log::warning('Login attempt failed: Not an admin. Email: ' . $request->email); // Log failed attempt
                    Auth::logout();
                    return response()->json(['message' => 'Access denied. Not an admin.'], 403);
                }
            } else {
                Log::warning('Login attempt failed: Invalid credentials. Email: ' . $request->email); // Log failed attempt
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage()); // Log exception
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    /**
     * Log out the admin from the application.
     */
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No user currently logged in'], 404);
        }

        $user = Auth::user();
        // Revoke only the current token (more precise)
        $user->currentAccessToken()->delete();
        Log::info('Admin logged out: ' . $user->email); // Log successful logout
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Get the current authenticated user's information.
     */
    public function user()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
