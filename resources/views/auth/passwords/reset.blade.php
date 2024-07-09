@extends('layouts.app')
@section('content')
    <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>Reset Password</h4>
            <form class="form-inline" method="POST" action="{{ route('password.update') }}" id="reset_password">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                {{-- <div class="form-group">
                <label for="html">Email Address</label>
                <div class="formfield">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div> --}}
                <div class="form-group">
                    <label for="html"></label>
                    <div class="formfield">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Password" name="password" required autocomplete="new-password">
                            <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="html"></label>
                    <div class="formfield">
                        <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password"
                            name="password_confirmation" required autocomplete="new-password">
                            <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    </div>
                </div>
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
                <button type="submit" class="primary-btn width-full">Reset Password</button>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_register'])
    @includeFirst(['validation.js_show_password'])
    <script>
    jQuery(document).ready(function() {
        $("#reset_password").find('button').attr('disabled', false);
        const rules = {

   
            password: {
                required: true,
                minlength: passwordMinLength,
                maxlength: passwordMaxLength,
                regex: passwordRegex,
            },
            password_confirmation: {
                required: true,
                // equalTo: "#password_confirmation"
            },
        }
        const messages = {

            password: {
                required: `{{ __('customvalidation.user.password.required') }}`,
                minlength: `{{ __('customvalidation.user.password.min', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.password.max', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                regex: `{{ __('customvalidation.user.password.regex', ['regex' => '${passwordRegex}']) }}`,

            },
            password_confirmation: {
                // equalTo: `{{ __('customvalidation.user.confirm_password.equal') }}`,
                required: `{{ __('customvalidation.user.confirm_password.required') }}`,
            },
  
  
     
        };

        handleValidation('reset_password', rules, messages, function(form) {
            $('body').addClass('loading');
            form.submit();
        });

    });
</script>

@endpush
