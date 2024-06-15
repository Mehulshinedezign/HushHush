<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
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
        if (Auth::user()) {
            if (Auth::user()->role->name == 'admin') {
                return redirect()->route("admin.dashboard");
            } elseif (Auth::user()->role->name == 'retailer') {
                return redirect()->route("retailer.dashboard");
            } elseif(Auth::user()->role->name == 'customer' && Auth::user()->status == '0') {
                Auth::logout();

                return redirect()->route("login")->with('error', 'Your account has been suspended. Please contact to site owner');
            }

            return $next($request);

        } else {
            Auth::logout();

            return redirect()->route("login");
        }

    }
}
