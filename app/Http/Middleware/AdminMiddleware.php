<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is an admin
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login to access this page.');
        }

        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin access required.');
        }

        return $next($request);
    }
}
