<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Add this line

class UserController extends Controller
{
    /**
     * Retrieve and display a listing of all users.
     * Returns a JSON response with all users if found, or a 404 error if no users exist.
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                Log::warning('Login attempt failed: Invalid credentials. Email: ' . $request->email);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            Log::info('User logged in: ' . $user->email);

            if  ($user->isAdmin()) {
                $token = $user->createToken('admin-access-token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully as admin.',
                    'access_token' => $token,
                    'user' => $user
                ], 200);
            } elseif  ($user->isSeller()) {
                $token = $user->createToken('seller-access-token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully as seller.',
                    'access_token' => $token,
                    'user' => $user
                ], 200);
            }
            elseif  ($user->isBuyer()) {
                $token = $user->createToken('buyer-access-token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully as buyer.',
                    'access_token' => $token,
                    'user' => $user
                ], 200);
            } else {
                Log::warning('Login attempt failed: Unauthorized role. Email: ' . $user->email);
                Auth::logout();
                return response()->json(['message' => 'Access denied. Unauthorized role.'], 403);
            }
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Log out the user from the application.
     */
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'No user currently logged in'], 404);
        }

        $user = Auth::user();
        $user->currentAccessToken()->delete();
        Log::info('User logged out: ' . $user->email);
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


    public function index()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No users found'
            ], 404);
        }
        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }
    /**
     * Store a newly created user in the database.
     * Validates the incoming request data and creates a user if validation passes.
     * Returns a JSON response with the created user's data or validation errors.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:table_users',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'role_id' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
            'firebase_uid' => 'nullable|string'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Create user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
            'firebase_uid' => $request->firebase_uid // Add this line if firebase_uid is passed from the client
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }
    /**
     * Remove the specified user from the database.
     * Finds the user by ID and deletes them, returning a success message or an error if not found.
     */
    //delete user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully'
        ], 200);
    }
}
