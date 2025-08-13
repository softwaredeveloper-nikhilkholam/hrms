<?php

namespace App\Http\Middleware;

use Closure;

class PurchaseMiddleware
{
    
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->storeStatus == '1' && $request->user()->userType == '701' && ($request->user()->userType == '00' || $request->user()->userType == '401' || $request->user()->userType == '201' || $request->user()->userType == '501'))
        {
            return $next($request);
        }

        return redirect()->back()->withInput()->with("error","You don't have sufficient permission to access this page!!!");
 
    }
}
