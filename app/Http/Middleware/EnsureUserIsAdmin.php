<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;



class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            // Redirect or handle the unauthorized access here
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }

}
