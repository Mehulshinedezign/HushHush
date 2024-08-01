<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Providers\RouteServiceProvider;
use App\Models\{EmailOtp, Otp, PhoneOtp, User, Role, UserDetail, UserDocuments};
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Notifications\{VerificationEmail, WelcomeEmail};
use App\Services\OtpService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $otpService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $previousUrl = URL::previous();
        $retailerRegisterUrl = url('/') . '/retailer/register';
        $emailRegex = "/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/";
        $validation = [
            // 'username' => ['required', 'min:3', 'max:50', 'unique:users'],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex: ' . $emailRegex],
            'phone_number.main' => ['required', 'digits:' . config('validation.phone_minlength'), 'min:' . config('validation.phone_minlength'), 'max:' . config('validation.phone_maxlength')],
            // 'zipcode' => ['required'],
            'password' => ['required', 'string', 'min:8', 'max:32', 'confirmed'],
            // 'complete_address' => 'required',
            'gov_id' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
        ];

        $message = [
            // 'username.required' => __('customvalidation.user.username.required'),
            'name.required' => __('user.validations.nameRequired'),
            'name.string' => __('user.validations.nameString'),
            'name.min' => __('user.validations.nameMin'),
            'name.max' => __('user.validations.nameMax'),
            'email.required' => __('user.validations.emailRequired'),
            'email.string' => __('user.validations.emailString'),
            'email.email' => __('user.validations.emailType'),
            'email.regex' => __('user.validations.emailType'),
            'email.max' => __('user.validations.emailMax'),
            'email.unique' => __('user.validations.emailUnique'),
            'phone_number.main.required' => __('customvalidation.user.phone_number.required'),
            'phone_number.main.digits' => __('customvalidation.user.phone_number.digits'),
            'phone_number.main.min' => __('customvalidation.user.phone_number.min', ['min' => config('validation.phone_minlength'), 'max' => config('validation.phone_maxlength')]),
            'phone_number.main.max' => __('customvalidation.user.phone_number.max', ['min' => config('validation.phone_minlength'), 'max' => config('validation.phone_maxlength')]),
            'password.required' => __('user.validations.passwordRequired'),
            'password.string' => __('user.validations.passwordString'),
            'password.min' => 'Password must be 8-32 characters long',
            'password.min' => 'Password must be 8-32 characters long',
            'password.confirmed' => __('user.validations.passwordConfirmed'),
            // 'zipcode.required' => __('customvalidation.user.zipcode.required'),
            //'zipcode.numeric' => __('customvalidation.user.zipcode.numeric'),
            // 'complete_address' => __('customvalidation.user.complete_address.required'),
            // 'complete_address.min' => __('user.validations.completeAddressMin'),
            // 'complete_address.max' => __('user.validations.completeAddressMax'),

            'gov_id' => __('customvalidation.user.gov_id.required'),
            'gov_id.file' => __('customvalidation.user.gov_id.file'),
            'gov_id.max' => __('customvalidation.user.gov_id.max_size'),
        ];

        // if ($previousUrl == $retailerRegisterUrl) {
        //     $validation['type'] = ['required'];
        //     $validation['proof'] = ['required', 'mimes:jpg,jpeg,png', 'max:'.request()->global_php_file_size];
        //     $message['type.required'] = __('user.validations.typeRequired');
        //     $message['proof.required'] = __('user.validations.proofRequired');
        //     $message['proof.image'] = __('user.validations.proofImage');
        //     $message['proof.mimes'] = __('user.validations.proofExtenstion');
        //     $message['proof.max'] = 'File size should not be more than '.(request()->global_php_file_size/1000).'MB';
        // }


        return Validator::make($data, $validation, $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
        //     'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
        //     'response' => $data['g_recaptcha_response']
        // ]);

        // $recaptchaData = $response->json();
        // if (!($response->json()["success"] ?? false) || $response->json()["score"] < 0.5) {
        //     // session()->flash('status', 'error');
        //     // session()->flash('message', 'The google recaptcha is invalid.');

        //     return false;
        // }

        $signUpData = User::create([
            // 'username' => $data['username'],
            'name' => $data['name'],
            'status' => '0',
            'email' => $data['email'],
            'phone_number' => $data['phone_number']['main'],
            // 'zipcode' => $data['zipcode'],
            'password' => Hash::make($data['password']),
            'email_verification_token' => Str::random(50)
        ]);

        UserDetail::create([
            'user_id' => $signUpData->id,
            // 'address1' =>$data['complete_address'],
            // 'about' =>$data['about'],
        ]);


        $path = $data['gov_id']->store('user_documents');
        $filePath = str_replace("public/", "", $path);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        UserDocuments::create([
            'user_id' => $signUpData->id,
            'file' => $fileExtension,
            'url' => $filePath,
        ]);


        $otp = $this->otpService->generateOtp($signUpData);
        // PhoneOtp::updateOrCreate(['user_id' => $signUpData->id], [
        //     'otp' => $otp,
        //     'expires_at' => date('Y-m-d H:i:s'),
        //     'status' => '0',
        // ]);
        PhoneOtp::updateOrCreate(['user_id' => $signUpData->id], [
            'otp' => '123456',
            'expires_at' => date('Y-m-d H:i:s'),
            'status' => '0',
        ]);
        EmailOtp::updateOrCreate(['user_id' => $signUpData->id], [

            'otp' => $otp,
            'expires_at' => date('Y-m-d H:i:s'),
            'status' => '0',
        ]);
        // $this->otpService->sendOtp($otp, $data['phone_number']['full']);

        return $signUpData;
    }

    protected function register(Request $request)
    {

        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        if ($user) {

            event(new Registered($user));

            $this->guard()->login($user);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectPath());
        } else {
            return redirect()->back()->with(['message' => 'The google recaptcha is invalid.']);
        }
    }
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if (isset($request->is_captcha) && $request->is_captcha != null) {
            DB::table('users')->where('id', $user->id)->delete();
            auth()->logout();
            return redirect()->back()->with(['message' => 'data store successfully']);
        }

        // auth()->logout();

        try {
            $otp = $user->emailOtp->otp;
            $user->notify(new VerificationEmail($user, $otp));
            // $request->session()->put('userId', $user->id);
            // return redirect()->route('verify.otp', compact('user'));

            return view('auth.verify_otp');

            // return redirect()->route('login')->with('success', 'Registration successful. A confirmation email has been sent to ' . $user->email . '. Please verify to log in.');
        } catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }
    }

    public function verifyEmail(Request $request, User $user, $token)
    {
        if ($token != $user->email_verification_token) {
            return redirect()->route('login')->with('error', 'Email verification token is invalid.');
        }
        $expiry  = Carbon::now()->subMinutes(15);

        if ($user->created_at <= $expiry) {
            return redirect()->route('login')->with('error', 'Password verification link expired');
        }
        try {
            User::where("id", $user->id)->update(["status" => "1", "email_verified_at" => date('Y-m-d H:i:s'), 'email_verification_token' => null]);
            // $user->notify(new WelcomeEmail($user));
        } catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }

        return redirect()->route('login')->with('success', 'Email successfully verified.');

        // auth()->login($user);

        // if ($user->role->name == 'admin') {
        //     $this->redirectTo = 'admin/dashboard';
        // } elseif ($user->role->name == 'individual' || $user->role->name == 'retailer') {
        //     $this->redirectTo = 'retailer/dashboard';
        // } else {
        //     $this->redirectTo = route('index');
        // }

        // return redirect($this->redirectTo);

    }
}
