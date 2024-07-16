<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::with('phoneOtp', 'emailOtp')->where('id', auth()->user()->id)->first();
        if ($user->email_verified_at == null) {
            return redirect()->route('auth.verify_otp_form');
        }
        return $next($request);
    }
}
