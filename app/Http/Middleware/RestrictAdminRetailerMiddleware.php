<?php

namespace App\Http\Middleware;

use Closure, Auth;
use Illuminate\Http\Request;

class RestrictAdminRetailerMiddleware
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
        if (!is_null(Auth::user()) && (Auth::user()->role->name == 'retailer' ||  Auth::user()->role->name == 'admin' ||  Auth::user()->role->name == 'customer')) {
            if (Auth::user()->role->name == 'admin') {
                return redirect()->route("admin.dashboard");
            }

            // return redirect()->route("index");
        }

        return $next($request);
    }
}
