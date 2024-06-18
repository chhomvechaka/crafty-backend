<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Handle an attempt to log in as an admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the logged in user is an admin
            if ($user->isAdmin()) {
                // Create a new token for the user
                $token = $user->createToken('admin-access-token')->plainTextToken;

                return response()->json([
                    'message' => 'Logged in successfully as admin.',
                    'access_token' => $token,
                    'user' => $user
                ], 200);
            } else {
                Auth::logout();
                return response()->json(['message' => 'Access denied. Not an admin.'], 403);
            }
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Log out the admin from the application.
     */
    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            // Revoke all tokens...
            $user->tokens()->delete();

            // Redirect to a safe page instead of assuming the login route exists
            return response()->json(['message' => 'Successfully logged out'], 200);
        }

        return response()->json(['message' => 'No user currently logged in'], 404);
    }

}

