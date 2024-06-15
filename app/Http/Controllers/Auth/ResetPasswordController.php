<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash};
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;
        if (!$token)
            return redirect()->route('login')->with('error', 'Token not found');
        if (!$email)
            return redirect()->route('login')->with('error', 'Email not found');
        $reset = DB::table('password_reset_tokens')->where('email', $request->input('email'))->first();
        if (!$reset)
            return redirect()->route('login')->with('error', 'The password reset link is invalid');
        $expiry  = Carbon::now()->subMinutes(10);
        if ($reset->created_at <= $expiry) {
            return redirect()->route('login')->with('error', 'Password reset url expired');
        }
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
    protected function resetPassword($user, $password)
    {

        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        // event(new PasswordReset($user));
        Auth::logout();
        $this->redirectTo = 'login';

        return redirect($this->redirectTo);
    }
}
