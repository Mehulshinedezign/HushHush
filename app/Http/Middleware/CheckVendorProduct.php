<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Product;

class CheckVendorProduct
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
        $product = Product::whereId(jsdecode_userdata($request->route()->parameters()['id']))->first();
        if ($product->user_id != auth()->user()->id) {
            return redirect()->route('retailer.products')->with('message', 'Product not available');
        }

        return $next($request);
    }
}
