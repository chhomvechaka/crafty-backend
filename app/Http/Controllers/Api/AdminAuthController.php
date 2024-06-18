<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    /**
     * Handle an attempt to log in as an admin
     *

     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the logged in user is an admin
            if ($user->isAdmin()) {
                // Successfully logged
                return response()->json([
                    'message' => 'Logged in successfully as admin.',
                    'user' => $user
                ], 200);
            } else {
                // Logged in but not as admin, log them out
                Auth::logout();
                return response()->json(['message' => 'Access denied. Not an admin.'], 403);
            }
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
