<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // Check if the request is for admin area
        if ($request->is('admin*') || $request->is('admin_management*')) {
            return route('admin.login');
        }
        
        // Default redirect for non-admin routes
        return route('admin.login'); // Since we only have admin authentication for now
    }
}
