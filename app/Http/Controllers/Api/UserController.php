<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Add this line

class UserController extends Controller
{
    /**
     * Retrieve and display a listing of all users.
     * Returns a JSON response with all users if found, or a 404 error if no users exist.
     */
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
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'role_id' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
            'firebase_uid' => 'nullable|string' // Marking firebase_uid as nullable and must be a string if provided
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
