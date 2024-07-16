<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Stripe\Review;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $url = url()->previous();
        if ($url == route('open.model')) {
            setcookie('lend', route('open.model'));
        }
        return view('auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => __('user.validations.emailRequired'),
            'password.required' => __('user.validations.passwordRequired')
        ]);
    }

    protected function credentials(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->email, 'password' => $request->password];
        } else {
            return ['phone_number' => $request->email, 'password' => $request->password];
        }
    }

    protected function authenticated(Request $request, $user)
    {

        $remember = $request->has('remember');
        if ($remember) {
            $loginby = $request->email . '_' . $request->password;
            $cookie = Cookie::get('rememberme');

            if (!$cookie || ($request->email != explode("_", $cookie)[0] || $request->password != explode("_", $cookie)[1])) {
                Cookie::queue('rememberme', $loginby, 2628000);  // 2628000 minutes (five years)
            }
        } else {
            Cookie::queue(Cookie::forget('rememberme'));
        }
        // Check OTP verification and email verification
        if ($user->otp_is_verified != 1 || is_null($user->email_verified_at)) {
            $user = $user->id;
            return view('auth.verify_otp');
            // return redirect()->route('auth.verify_otp_form', ['user' => $user->id]);
        }

        // Set redirection based on user role
        if ($user->role->name == 'admin') {
            $this->redirectTo = 'admin/dashboard';
        } elseif ($user->role->name == 'retailer') {
            $this->redirectTo = 'retailer/dashboard';
        }

        if (!is_null(session('redirectUrl'))) {
            $this->redirectTo = session('redirectUrl');
            session()->forget('redirectUrl');
        } elseif ($user->role->name == 'customer') {
            $this->redirectTo = route('index');
        }

        if (Session::has('lendurl')) {
            $this->redirectTo = route('index');
        }

        return redirect($this->redirectTo);
    }



    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login');
    }

    public function loginUser(User $user)
    {
        Auth::login($user);
        return redirect($this->redirectPath());
    }

    // protected function getRedirectUrl($user)
    // {
    //     if ($user->role->name == 'admin') {
    //         return 'admin/dashboard';
    //     } elseif ($user->role->name == 'retailer') {
    //         return 'retailer/dashboard';
    //     } elseif ($user->role->name == 'customer') {
    //         return route('index');
    //     }

    //     return '/';
    // }
}
