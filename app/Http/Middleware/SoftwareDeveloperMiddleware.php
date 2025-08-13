<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SoftwareDeveloperMiddleware
{
    public function handle($request, Closure $next)
    {
        // You can use userType == 'software_developer' or a specific ID like 301
        if (Auth::check() && Auth::user()->userType == '007') {
            return $next($request);
        }

        return redirect('/home')->with('error', 'Unauthorized Access');
    }
}
