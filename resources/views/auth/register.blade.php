@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <h4>Sign up</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('register') }}" id="register"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="g_recaptcha_response" id="recaptcha_token" value="">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="formfield">
                        <input type="text" class="form-control form-class @error('first_name') is-invalid @enderror"
                            name="first_name" id="first_name" placeholder="{{ __('First Name') }}">
                        <span class="form-icon">
                            <img src="images/user-icon.svg" alt="">
                        </span>
                    </div>
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="formfield">
                        <input type="text" class="form-control form-class @error('last_name') is-invalid @enderror"
                            name="last_name" id="last_name" placeholder="{{ __('Last Name') }}">
                        <span class="form-icon">
                            <img src="images/user-icon.svg" alt="">
                        </span>
                    </div>
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

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

                <div class="form-group">


                </div>
                <button class="button primary-btn full-btn">Sign up</button>
                <p class="have-account">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
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

        document.addEventListener('DOMContentLoaded', function() {
            const phoneNumberInput = document.getElementById('phone_number');

            phoneNumberInput.addEventListener('keypress', function(e) {
                const charCode = e.which ? e.which : e.keyCode;
                if (charCode < 48 || charCode > 57) {
                    e.preventDefault();
                }
            });

            phoneNumberInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    </script>
@endpush
