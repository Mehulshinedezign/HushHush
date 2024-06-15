@extends('layouts.app')
@section('content')
    <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>{{ __('Register') }}</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('register') }}" id="register">
                @csrf
                <input type="hidden" name="g_recaptcha_response" id="recaptcha_token" value="">
                <div class="form-group">
                    {{-- <label for="html">{{ __('Username') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="username" type="text"
                            class="form-control form-class @error('username') is-invalid @enderror" name="username"
                            value="{{ old('username') }}" autofocus autocomplete="off" placeholder="{{ __('Username') }}">
                        <span class="icon">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                    @error('username')
                        <span id="removeuser"class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <label for="html">{{ __('Full name') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="name" type="text"
                            class="form-control form-class @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" autocomplete="name" placeholder="{{ __('Full Name') }}">
                        <span class="icon">
                            <i class="fa-solid fa-user"></i>
                        </span>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <label for="email">{{ __('Email') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="email" type="email"
                            class="form-control form-class @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" autocomplete="email" placeholder="{{ __('Email') }}">
                        <span class="icon">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                    </div>
                    @error('email')
                        <span id="removeemail" class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <label for="phone_number">{{ __('Phone Number') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="phone_number" type="text"
                            class="form-control form-class @error('phone_number') is-invalid @enderror" name="phone_number"
                            value="{{ old('phone_number') }}" autocomplete="off" placeholder="{{ __('Phone Number') }}">
                        <span class="icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                    </div>
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <label for="password_confirmation">{{ __('Password') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="password_confirmation" type="password"
                            class="form-control form-class @error('password') is-invalid @enderror" name="password"
                            placeholder="{{ __('Password') }}">
                        <span class="icon toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- <label for="html">{{ __('Confirm Password') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="password-confirm" type="password" class="form-control form-class"
                            name="password_confirmation" autocomplete="new-password"
                            placeholder="{{ __('Confirm Password') }}">
                        <span class="icon toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    {{-- <label for="html">{{ __('Zip Code') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input id="zipcode" type="text"
                            class="form-control form-class @error('zipcode') is-invalid @enderror" name="zipcode"
                            autocomplete="off" value="{{ old('zipcode') }}" placeholder="{{ __('Zip Code') }}">
                        @error('zipcode')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                </div>
                <div class="form-group">
                    {{-- <label for="html">{{ __('Zip Code') }}<span class="text-danger">*</span></label> --}}
                    <div class="formfield">
                        <input type="text"
                            class="form-control form-class @error('is_captcha') is-invalid @enderror d-none"
                            name="is_captcha" autocomplete="off" value="">
                        @error('is_captcha')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" onclick="setCaptchaToken(`{{ env('GOOGLE_RECAPTCHA_KEY') }}`)"
                    class="primary-btn width-full">{{ __('Submit') }}</button>

                <p class="have-account">Already have an account?<a href="{{ route('login') }}">{{ __('Sign in.') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- <script>
        function setCaptchaToken(key) {

            grecaptcha.ready(function() {
                grecaptcha.execute(key, {
                    action: 'homepage'
                }).then(function(token) {
                    document.getElementById('recaptcha_token').value = token;
                    $('#register').submit();

                });
            });
        }
    </script> --}}

    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
    @includeFirst(['validation'])
    @includeFirst(['validation.js_register'])
    @includeFirst(['validation.js_show_password'])
    <script>
        jQuery("input[name=username]").on("keyup", function() {
            jQuery(this).removeClass('is-invalid')
            jQuery('span[id=removeuser]').empty();
        });
        jQuery("input[type=email]").on("keyup", function() {
            jQuery(this).removeClass('is-invalid')
            jQuery('span[id=removeemail]').empty();
        });
    </script>
@endpush
