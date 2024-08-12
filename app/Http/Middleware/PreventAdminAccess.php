<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventAdminAccess
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
        $user= auth()->user();
        if (Auth::check() && $user->role->name == 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        // dd($next ,Auth::user()->role[0]=='admin');
        return $next($request);
    }
}
