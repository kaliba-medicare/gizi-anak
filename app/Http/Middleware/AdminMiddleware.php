<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin area.');
        }

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Access denied. Admin privileges required.');
        }

        // Check if admin account is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
