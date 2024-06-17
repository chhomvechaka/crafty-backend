<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ViewUserController extends Controller
{
    //
    // New method to view user details
    public function viewUserDetails($user_id)
    {
        $users = User::find($user_id); // Find the user by ID

        if (!$users) {
            // User not found
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return user details
        return response()->json($users);
    }

    // New method to get all users
    public function getAllUsers()
    {
        $users = User::all(); // Retrieve all users

        if (empty($users)) {
            // No users found
            return response()->json(['error' => 'No users found'], 404);
        }

        // Return all users
        return response()->json($users);
    }
}
