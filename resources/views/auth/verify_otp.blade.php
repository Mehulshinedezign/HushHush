@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <x-alert />
            @dd($user);
            @if (emailValidate($user->id))
                <form id="emailOtpVerify" class="form-inline" action="{{ route('verify.email.otp') }}" method="POST">
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
                    <a href="{{ route('resend.otp', ['type' => 'email', 'user_id' => $user->id]) }}"
                        class="text-dark">Resend</a>
                </form>
            @endif
            <hr>
            @if (phoneNumberValidate($user->id))
                <form id="phoneOtpVerify" class="form-inline" action="{{ route('verify.phone.otp') }}" method="POST">
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
                    <a href="{{ route('resend.otp', ['type' => 'phone_number', 'user_id' => $user->id]) }}"
                        class="text-dark">Resend</a>
                </form>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#emailOtpVerify').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.login == 1) {
                            location.reload();
                        } else if (response.status === true) {
                            alert(response.message);
                            $('#emailOtpVerify').remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            $('#phoneOtpVerify').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.login == 1) {
                            location.reload();
                        } else if (response.status === true) {
                            alert(response.message);
                            $('#phoneOtpVerify').remove();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(response) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
