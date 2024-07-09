@extends('layouts.app')
@section('content')
    {{-- <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>{{ __('Register') }}</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('register') }}" id="register">
                @csrf
                <input type="hidden" name="g_recaptcha_response" id="recaptcha_token" value="">
                <div class="form-group">
                
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
    </div> --}}
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <h4>Sign up</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('register') }}" id="register" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="g_recaptcha_response" id="recaptcha_token" value="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="formfield">
                        <input type="text" class="form-control form-class @error('name') is-invalid @enderror"
                            name="name" id="name" placeholder="{{ __('Full Name') }}">
                        <span class="form-icon">
                            <img src="images/user-icon.svg" alt="">
                        </span>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>
                {{-- <div class="form-group">
                    <div class="formfield">
                        <input id="phone_number" type="text"
                            class="form-control form-class @error('phone_number') is-invalid @enderror" name="phone_number[main]"
                            id="phone_number" value="{{ old('phone_number') }}" autocomplete="off" placeholder="{{ __('Phone Number') }}">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                    </div>
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div> --}}
                <div class="form-group">
                    <div class="formfield">
                        <input id="phone_number" type="text"
                            class="form-control form-class @error('phone_number.main') is-invalid @enderror"
                            name="phone_number[main]" value="{{ old('phone_number.main') }}" autocomplete="off"
                            placeholder="{{ __('Phone Number') }}">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                    </div>
                    @error('phone_number.main')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- <div class="col-md-12 mb-2 mb-sm-3 phone-error-label-div">
                    <div class="form-floating">
                        <input type="tel" class="form-control @error('phone.full') is-invalid @enderror" id="phone"
                            placeholder="Phone" name="phone[main]" value="{{ old('phone.full') }}" autofocus
                            autocomplete="off">

                    </div>
                </div> --}}

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="formfield">
                        <input type="email" class="form-control form-class @error('email') is-invalid @enderror"
                            name="email" placeholder="{{ __('Email') }}">
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
                    <label for="gov_id">Gov Id</label>
                    <div class="formfield upload-id-field">
                        <input type="file" class="form-control form-class @error('gov_id') is-invalid @enderror"
                            name="gov_id" placeholder="Enter goverment id">

                    </div>
                    @error('gov_id')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="password_confirmation">Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control form-class @error('password') is-invalid @enderror"
                            id="password_confirmation" name="password" placeholder="{{ __('Password') }}">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control form-class" id="password-confirm"
                            name="password_confirmation" autocomplete="new-password"
                            placeholder="{{ __('Confirm Password') }}">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    </div>

                </div>
                {{-- <div class="form-group">
                    <label for="complete_address">Complete address</label>
                    <div class="formfield">
                        <input type="text"
                            class="form-control form-class @error('complete_address') is-invalid @enderror"
                            name="complete_address" id="complete_address" placeholder="Enter complete address">
                        <span class="form-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="21" viewBox="0 0 17 21"
                                fill="none">
                                <path
                                    d="M8.25694 0.236328C3.97059 0.236328 0.482422 3.74868 0.482422 8.06699C0.482422 14.2028 7.52613 20.5382 7.82588 20.8042C7.94941 20.9139 8.10317 20.9684 8.25694 20.9684C8.4107 20.9684 8.56446 20.9139 8.68799 20.8051C8.98774 20.5382 16.0314 14.2028 16.0314 8.06699C16.0314 3.74868 12.5433 0.236328 8.25694 0.236328ZM8.25694 12.33C5.87534 12.33 3.93776 10.3924 3.93776 8.01084C3.93776 5.62925 5.87534 3.69167 8.25694 3.69167C10.6385 3.69167 12.5761 5.62925 12.5761 8.01084C12.5761 10.3924 10.6385 12.33 8.25694 12.33Z"
                                    fill="#DEE0E3" />
                            </svg>
                        </span>
                    </div>
                    @error('complete_address')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div> --}}
                <div class="form-group">

                    {{-- <div class="formfield">
                        <input id="about" type="text"
                            class="form-control form-class @error('about') is-invalid @enderror" name="about"
                            value="{{ old('about') }}" autocomplete="off" placeholder="About me">
                        <span class="form-icon">
                            <i class="bi bi-person-circle fs-4"></i>
                        </span>
                    </div> --}}

                </div>
                <button class="button primary-btn full-btn">Sign up</button>
                <p class="have-account">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="
    https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
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

        var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
            separateDialCode: true,
            preferredCountries: ["us", "in"],
            hiddenInput: "full",
            formatOnDisplay: false,
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
        $(document).ready(function() {
            $(document).on('keypress', 'input', function(e) {
                if (e.which === 13) { // 13 is the Enter key code
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
