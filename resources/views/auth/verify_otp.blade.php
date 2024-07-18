@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form otp-form">
            <x-alert />

            <h3>You have verify email and phone OTP</h3>
            {{-- @if (emailValidate(auth()->user()->id)) --}}
            <form id="emailOtpVerify" class="form-inline emailverify" action="{{ route('verify.email.otp') }}" method="POST">
                @csrf

                <div class="form-group ">
                    <label for="">Email OTP Send To: {{ auth()->user()->email }}</label>
                    <div class="formfield custm-otp-field">
                        <input id="emailotp" type="text"
                            class="form-control form-class @error('emailotp') is-invalid @enderror" name="emailotp"
                            value="{{ old('emailotp') }}" @if (auth()->user()->emailOtp->status == 1) disabled @endif
                            autocomplete="name"
                            placeholder="@if (auth()->user()->emailOtp->status == 1) verified @else Enter OTP @endif">
                        <button type="submit" @if (auth()->user()->emailOtp->status == 1) disabled @endif
                            class="button primary-btn full-btn">
                            @if (auth()->user()->emailOtp->status != 1)
                                {{ __('Verify OTP') }}
                            @else
                                <i class="fa-solid fa-circle-check"></i>
                            @endif
                        </button>
                    </div>
                    @error('emailotp')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('resend.otp', ['type' => 'email']) }}" class="text-dark ">Resend
                        Email OTP</a>
                </div>
            </form>
            {{-- @endif --}}

            {{-- @if (phoneNumberValidate(auth()->user()->id)) --}}
            <form id="phoneOtpVerify" class="form-inline phoneverify" action="{{ route('verify.phone.otp') }}"
                method="POST">
                @csrf
                <div class="form-group mt-3">
                    <label for="">Phone OTP Send To: {{ auth()->user()->phone_number }}</label>
                    <div class="formfield custm-otp-field   ">
                        <input id="phoneotp" type="text"
                            class="form-control form-class @error('phoneotp') is-invalid @enderror" name="phoneotp"
                            value="{{ old('phoneotp') }}" @if (auth()->user()->phoneOtp->status == 1) disabled @endif
                            autocomplete="name"
                            placeholder="@if (auth()->user()->phoneOtp->status == 1) verified @else Enter OTP @endif">
                        <button type="submit" id="submitPhoneOtp" @if (auth()->user()->phoneOtp->status == 1) disabled @endif
                            class="button primary-btn full-btn">
                            @if (auth()->user()->phoneOtp->status != 1)
                                {{ __('Verify OTP') }}
                            @else
                                <i class="fa-solid fa-circle-check"></i>
                            @endif
                        </button>
                    </div>
                    @error('phoneotp')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                {{-- @dd($user) --}}
                <div class="d-flex justify-content-end">
                    <a href="{{ route('resend.otp', ['type' => 'phone_number']) }}" class="text-dark">Resend Phone OTP</a>
                </div>
            </form>
            {{-- @endif --}}
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
                response = ajaxCall(url, 'post', formData)
                response.then(response => {
                    console.log(response.login);
                    if (response.login == 1) {
                        window.location.href = response.url;
                    } else if (response.status == true) {
                        // $('#emailotp').prop('disabled', true);
                        location.reload();
                        // return iziToast.success({
                        //     message: response.message,
                        //     position: 'topRight'
                        // });
                    }

                })

            });

            $('#phoneOtpVerify').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = new FormData($(this)[0]);
                response = ajaxCall(url, 'post', formData)
                response.then(response => {
                    if (response.login == 1) {
                        window.location.href = response.url;
                    } else if (response.status == true) {
                        location.reload()
                        //$('#phoneotp').prop('disabled', true);
                        // return iziToast.success({
                        //     message: response.message,
                        //     position: 'topRight'
                        // });
                    }

                })
            });
        });
    </script>
@endpush
