<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreGuestRedirectUrl
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
        // Check if the user is a guest
        if (auth()->guest()) {
            // Check if the request contains a 'redirect_url' parameter
            if ($request->has('redirect_url')) {
                // Store the redirect URL in the session
                Session::put('redirect_after_login', $request->input('redirect_url'));
            }
        }

        return $next($request);
    }
}
