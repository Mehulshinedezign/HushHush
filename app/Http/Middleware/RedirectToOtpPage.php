<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToOtpPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->id()){

            $user = User::with('phoneOtp', 'emailOtp')->where('id', auth()->user()->id)->first();
            if($user && $user->phoneOtp && $user->phoneOtp->status == "1"  && $user->emailOtp && $user->emailOtp->status == "1" ){
                return $next($request);
            }else{
                return redirect()->route('auth.verify_otp_form');
            }
        }else{
            return $next($request);
        }

    }
}
