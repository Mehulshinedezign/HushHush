@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <x-alert />
            {{-- @dd($user) --}}
            @if (emailValidate($user->id))
                <form id="emailOtpVerify" class="form-inline" action="{{ route('verify.email.otp') }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <div class="formfield">
                            <input id="emailotp" type="text"
                                class="form-control form-class @error('emailotp') is-invalid @enderror" name="emailotp"
                                value="{{ old('emailotp') }}" autocomplete="name" placeholder="Enter OTP">
                        </div>
                        @error('emailotp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="button primary-btn full-btn">{{ __('Verify OTP') }}</button>

                    {{-- <!-- <a href="{{ route('resend.otp', ['user_id' => $userId]) }}" class="text-dark">Resend</a> --> --}}
                </form>
            @endif
            <hr>
            @if (phoneNumberValidate($user->id))
                <form id="phoneOtpVerify" class="form-inline" action1="{{ route('verify.phone.otp') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <div class="formfield">
                            <input id="phoneotp" type="text"
                                class="form-control form-class @error('phoneotp') is-invalid @enderror" name="phoneotp"
                                value="{{ old('phoneotp') }}" autocomplete="name" placeholder="Enter OTP">
                        </div>
                        @error('phoneotp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <button type="submit" id="submitPhoneOtp"
                        class="button primary-btn full-btn">{{ __('Verify OTP') }}</button>
                    {{-- <!-- <a href="{{ route('resend.otp', ['user_id' => $userId]) }}" class="text-dark">Resend</a> --> --}}


                </form>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            jQuery('#emailOtpVerify').submit(function(e) {
                e.preventDefault();
                // handleValidation('verifyForm', rules, messages);
                // if ($('#verifyForm').valid()) {

                // }
                var url = $('#emailOtpVerify').attr('action');

                productformData = new FormData($('form#emailOtpVerify').get(0));

                response = ajaxCall(url, 'post', productformData)
                response.then(response => {
                    if (response.login == 1) {
                        location.reload();
                    }

                })
            });

        });
        $(document).ready(function() {
            jQuery('#phoneOtpVerify').submit(function(e) {

                e.preventDefault();
                // handleValidation('verifyForm', rules, messages);
                // if ($('#verifyForm').valid()) {

                // }
                var url1 = $('#phoneOtpVerify').attr('action1');


                productformData1 = new FormData($('form#phoneOtpVerify').get(0));

                response = ajaxCall(url1, 'post', productformData1)
                response.then(response => {
                    if (response.login == 1) {
                        location.reload();
                    }

                })
            });
        });
    </script>
@endpush
