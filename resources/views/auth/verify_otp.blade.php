@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <x-alert />
            @if (emailValidate(auth()->user()->id))
                <form id="emailOtpVerify" class="form-inline emailverify" action="{{ route('verify.email.otp') }}"
                    method="POST">
                    @csrf

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
                    <a href="{{ route('resend.otp', ['type' => 'email']) }}" class="text-dark">Resend</a>
                </form>
            @endif
            <hr>
            @if (phoneNumberValidate(auth()->user()->id))
                <form id="phoneOtpVerify" class="form-inline phoneverify" action="{{ route('verify.phone.otp') }}"
                    method="POST">
                    @csrf
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
                    {{-- @dd($user) --}}
                    <button type="submit" id="submitPhoneOtp"
                        class="button primary-btn full-btn">{{ __('Verify OTP') }}</button>
                    <a href="{{ route('resend.otp', ['type' => 'phone_number']) }}" class="text-dark">Resend</a>
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
                console.log(url, "herere");
                response = ajaxCall(url, 'post', formData)
                response.then(response => {
                    console.log(response.login);
                    if (response.login == 1) {
                        window.location.href = response.url;
                    } else if (response.status == true) {
                        $('.emailverify').addClass('d-none');
                        return iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                    }

                })

            });

            $('#phoneOtpVerify').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = new FormData($(this)[0]);
                response = ajaxCall(url, 'post', formData)
                response.then(response => {
                    console.log(response.login);
                    if (response.login == 1) {
                        window.location.href = response.url;
                    } else if (response.status == true) {
                        $('.phoneverify').addClass('d-none');
                        return iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                    }

                })
            });
        });
    </script>
@endpush
