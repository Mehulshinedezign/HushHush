@extends('layouts.app')
@section('content')
    <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>{{ __('Log in') }}</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('login') }}" id="login">
                @csrf
                <div class="form-group">
                    {{-- <label>{{ __('Email') }}</label> --}}
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
                    {{-- <label>{{ __('Password') }}</label> --}}
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
                <!-- <p class="have-account">{{ __("Don't have an account?") }} <a
                        href="{{ route('register') }}">{{ __('Sign Up') }}</a></p> -->
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_login'])
    @includeFirst(['validation.js_show_password'])
@endpush
