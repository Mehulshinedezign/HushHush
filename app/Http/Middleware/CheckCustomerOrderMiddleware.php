<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomerOrderMiddleware
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
        $order = $request->route()->parameters();
        // dd('here');
        if (isset($order['order']->user_id) && $order['order']->user_id != auth()->user()->id) {
            return redirect()->route('orders');
        }

        return $next($request);
    }
}
