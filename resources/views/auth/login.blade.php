@extends('layouts.app')
@section('content')
    {{-- <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>{{ __('Log in') }}</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('login') }}" id="login">
                @csrf
                <div class="form-group">
                    <div class="formfield">
                        <input id="email" type="text"
                            class="form-control form-class @error('password') invalid-feedback @enderror" name="email"
                            value="{{ old('email', request()->cookie('rememberme') ? explode('_', request()->cookie('rememberme'))[0] : '') }}"
                            placeholder="{{ __('Email') }}">
                        <span class="icon">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
            
                    <div class="formfield">
                        <input id="password" type="password"
                            class="form-control form-class @error('password') is-invalid @enderror" name="password"
                            placeholder="{{ __('Password') }}"
                            value="{{ request()->cookie('rememberme') ? explode('_', request()->cookie('rememberme'))[1] : '' }}">
                        <span class="icon toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check remember-me">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ request()->cookie('rememberme') ? 'checked' : '' }} id="flexCheckDefault">
                    <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                </div>
                <button type="submit" class="primary-btn width-full">{{ __('Log in') }}</button>
                @if (Route::has('password.request'))
                    <div class="forgot-password-sec">
                        <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                    </div>
                @endif
                 <p class="have-account">{{ __("Don't have an account?") }} <a
                        href="{{ route('register') }}">{{ __('Sign Up') }}</a></p> 
            </form>
        </div>
    </div> --}}
    <!-- header Section Starts Here  -->
    <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>{{ __('Log in') }}</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('login') }}" id="login">
                @csrf
                <div class="form-group">
                    <label for="email">Email or phone number</label>
                    <div class="formfield">
                        <input type="email" class="form-control form-class @error('email') invalid-feedback @enderror"
                            name="email" id="email" placeholder="Enter email"
                            value="{{ old('email', request()->cookie('rememberme') ? explode('_', request()->cookie('rememberme'))[0] : '') }}">
                        <span class="form-icon">
                            <svg width="22" height="15" viewBox="0 0 22 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.66371 0.941406C1.43464 0.941406 1.21568 0.989306 1.02054 1.07806L10.2178 9.30251C10.4367 9.49816 10.8993 9.49816 11.1182 9.30251L20.3154 1.07806C20.1203 0.989306 19.9013 0.941406 19.6722 0.941406H1.66371ZM0.280908 1.80164C0.1797 2.00734 0.120117 2.23941 0.120117 2.48499V12.7756C0.120117 13.6307 0.808558 14.3192 1.66371 14.3192H19.6722C20.5274 14.3192 21.2158 13.6307 21.2158 12.7756V2.48499C21.2158 2.23941 21.1562 2.00734 21.055 1.80164L11.8015 10.0663C11.1582 10.6412 10.1777 10.6412 9.53441 10.0663L0.280908 1.80164Z"
                                    fill="#DEE0E3" />
                            </svg>
                        </span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control form-class @error('password') is-invalid @enderror"
                            name="password" placeholder="Enter your password"
                            value="{{ request()->cookie('rememberme') ? explode('_', request()->cookie('rememberme'))[1] : '' }}">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check remember-me">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value=""
                        {{ request()->cookie('rememberme') ? 'checked' : '' }} id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                </div>
                <button class="button primary-btn full-btn">Log in</button>
                @if (Route::has('password.request'))
                    <div class="forgot-password-sec">
                        <a href="{{ route('password.request') }}">Forgot Password</a>
                    </div>
                @endif
                <p class="have-account">Don't have an account? <a href="{{ route('register') }}">{{ __('Sign Up') }}</a></p>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_login'])
    @includeFirst(['validation.js_show_password'])
@endpush
