@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <h4>Forgot Password</h4>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form class="form-inline" method="POST" action="{{ route('password.email') }}" id="forget_password">
                @csrf
                <div class="recove-account">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#recover-by-email" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">
                                <p>Recover By Email</p>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill"
                                data-bs-target="#recover-by-phone" type="button" role="tab"
                                aria-controls="pills-complete" aria-selected="false">
                                <p>Recover By Phone</p>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="recover-by-email" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="formfield">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" placeholder="Enter email"
                                        name="email" value="{{ old('email') }}">
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
                        </div>
                        <div class="tab-pane fade" id="recover-by-phone" role="tabpanel"
                            aria-labelledby="pills-complete-tab" tabindex="0">
                            <label for="phone_number">Phone</label>
                            <div class="form-group">
                                <div class="formfield">
                                    <input id="phone_number" type="text"
                                        class="form-control form-class @error('phone_number.main') is-invalid @enderror"
                                        name="phone_number[main]" value="{{ old('phone_number.main') }}"
                                        placeholder="{{ __('Phone Number') }}">
                                    <span class="form-icon">
                                        <img src="{{ asset('front/images/telephone-icon.svg') }}" alt="">
                                    </span>
                                </div>
                                @error('phone_number.main')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="button primary-btn full-btn" id="reset_pass">{{ __('Send Password Reset Link') }}</button>
                <p class="have-account"><a href="{{ route('login') }}">Back to Login</a></p>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@includeFirst(['validation'])
    <link href="//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script>
        var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
            separateDialCode: true,
            preferredCountries: ["us"],
            hiddenInput: "full",
            formatOnDisplay: false,
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        $(document).ready(function() {
            const rules = {
                email: {
                    required: true,
                    email: true,
                    regex: emailRegex,
                },
                "phone_number[main]": {
                    required: true,
                    digits: true,
                    isValidPhoneNumber: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
            }
            const messages = {
                email: {
                    required: `{{ __('customvalidation.user.email.required') }}`,
                    email: `{{ __('customvalidation.user.email.email') }}`,
                    regex: `{{ __('customvalidation.user.email.regex', ['regex' => '${emailRegex}']) }}`,
                },
                "phone_number[main]": {
                    required: `{{ __('customvalidation.user.phone_number.required') }}`,
                    digits: `{{ __('customvalidation.user.phone_number.digits') }}`,
                    minlength: `{{ __('customvalidation.user.phone_number.min', ['min' => '${phoneMinLength}', 'max' => '${phoneMaxLength}']) }}`,
                    maxlength: `{{ __('customvalidation.user.phone_number.max', ['min' => '${phoneMinLength}', 'max' => '${phoneMaxLength}']) }}`,
                },
            };
            $.validator.addMethod("isValidPhoneNumber", function(value, element) {
                return phone_number.isValidNumber();
            }, "Please enter a valid phone number");


            // handleValidation('forget_password', rules, messages, function(form) {
            //     $('body').addClass('loading');
            //     form.submit();
            // });

             // Handle validation
            $('#forget_password').validate({
                rules: rules,
                messages: messages,
                ignore: ":hidden",
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $('body').addClass('loading');
                    form.submit();
                }
            });


            $('button[data-bs-toggle="pill"]').on('click', function() {
                const activeTab = $('.tab-pane.active').attr('id');
                if (activeTab === 'recover-by-email') {
                    $('#reset_pass').click(function(){
                        $('#forget_password').validate().element('#email');
                        $('#phone_number').removeClass('is-invalid');
                    });
                }else{
                    $('#forget_password').validate().element('#phone_number');
                    $('#email').removeClass('is-invalid');
                } 
                // else if (activeTab === 'recover-by-phone')
                //  {
                // }
            });
        });
    </script>
@endpush
