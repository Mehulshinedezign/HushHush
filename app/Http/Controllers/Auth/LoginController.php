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

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            $this->username() . '.required' => __('user.validations.emailRequired'),
            //$this->username().'.email' => __('user.validations.emailType'),
            //$this->username().'.exists' => __('user.validations.invalidEmail'),
            'password.required' => __('user.validations.passwordRequired')
        ]);
    }

    protected function credentials(Request $request)
    {
        // dd('here');
        if (filter_var($request->email, FILTER_VALIDATE_INT)) {
            return ['phone_number' => $request->email, 'password' => $request->password, 'status' => '1'];
        }


        $credentials = $request->only($this->username(), 'password');

        $credentials['status'] = '1';

        return $credentials;
    }

    protected function authenticated(Request $request, $user)
    {
        $remember = $request->remember ? true : false;
        if ($remember) {
            $loginby = $request->email . '_' . $request->password;
            if (!request()->cookie('rememberme')) {
                Cookie::queue(Cookie::make('rememberme', $loginby, 2628000));  //2628000 (five years)
            } else if ($request->email != explode("_", request()->cookie('rememberme'))[0] && $request->password != explode("_", request()->cookie('rememberme'))[1]) {
                Cookie::queue(Cookie::make('rememberme', $loginby, 2628000));  //2628000 (five years)
            }
        } else {
            Cookie::queue(Cookie::forget('rememberme'));
        }
        // dd('hererr', $_COOKIE['lendurl']);


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

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
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
}
