<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Good practice to import the facade

class CRMMiddleware
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
        // 1. First, check if the user is authenticated at all.
        // Auth::check() is the proper way to do this.
        if (!Auth::check()) {
            // If not logged in, redirect to the login page.
            return redirect()->route('login');
        }

        // 2. Now that we know the user is authenticated, check for authorization.
        // This assumes you have a method `hasCrmAccess()` on your User model.
        if (!Auth::user()->hasCrmAccess()) {
            // 3. Abort with a 403 "Forbidden" error. This is the standard
            // for when an authenticated user does not have permission.
            abort(403, 'You are not authorized to access this section. Please contact the Super Admin.');
        }

        // If all checks pass, allow the request to proceed.
        return $next($request);
    }
}
