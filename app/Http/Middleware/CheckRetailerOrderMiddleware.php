<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRetailerOrderMiddleware
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

        if ($order['order']->item->retailer_id != auth()->user()->id) {
            return redirect()->route('retailer.orders');
        }

        return $next($request);
    }
}
