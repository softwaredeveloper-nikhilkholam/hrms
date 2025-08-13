<?php

namespace App\Http\Middleware;

use Closure;

class ItDepartmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ($request->user()->userType == '71'))
        {
            return $next($request);
        }

        return redirect()->back()->withInput()->with("error","You don't have sufficient permission to access this page!!!");
 
    }
}
