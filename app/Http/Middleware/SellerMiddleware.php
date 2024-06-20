<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
// useless for now
class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Log user role and requested path for auditing purposes
            Log::info('User Role: ' . Auth::user()->role_name);
            Log::info('Requested Path: ' . $request->path());

            if (Auth::user()->role_name === 'seller') {
                return $next($request);
            } else {
                Log::warning('Unauthorized access attempt by non-seller');
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
