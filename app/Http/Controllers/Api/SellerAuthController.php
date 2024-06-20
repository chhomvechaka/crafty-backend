<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SellerAuthController extends Controller
{
    public function index()
    {
        // Check if the current user is authenticated as an admin
        if (Auth::check() && Auth::user()->role_name === 'seller') {
            // Return a response or view that is specific to admins
            return response()->json([
                'message' => 'This is seller',
                'user' => Auth::user() // Optionally return user data
            ]);
        }

        // If the user is not an admin, return an unauthorized error.
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    public function sellerLogin(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to authenticate with provided credentials
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                // Ensure the user has the seller role
                if ($user->isSeller()){
                    $token = $user->createToken('seller-access-token')->plainTextToken;
                    Log::info('Seller logged in: ' . $user->email); // Log successful login

                    return response()->json([
                        'message' => 'Logged in successfully as seller.',
                        'access_token' => $token,
                        'user' => $user
                    ], 200);
                } else {
                    // Log failed attempt due to incorrect role
                    Log::warning('Login attempt failed: Not a seller. Email: ' . $user->email);
                    Auth::logout(); // Ensure to logout the user
                    return response()->json(['message' => 'Access denied. Not a seller.'], 403);
                }
            } else {
                // Log failed attempt due to invalid credentials
                Log::warning('Login attempt failed: Invalid credentials. Email: ' . $request->email);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            // Log unexpected exceptions
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }
    public function sellerLogout(Request $request)
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
    public function currentSeller()
    {
        $user = Auth::user();
        return response()->json($user);
    }



}
