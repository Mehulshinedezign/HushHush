@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('verify.otp.submit') }}">
                @csrf
                <div class="form-group">
                    <div class="formfield">
                        <input id="otp" type="text"
                            class="form-control form-class @error('otp') is-invalid @enderror" name="otp"
                            value="{{ old('otp') }}" autocomplete="name" placeholder="Enter OTP">
                    </div>
                    @error('otp')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- <a href="{{ route('resend.otp', ['user_id' => $userId]) }}" class="text-dark">Resend</a> -->

                <button type="submit" 
                    class="button primary-btn full-btn">{{ __('Verify OTP') }}</button>
                </p>
            </form>
        </div> 
    </div>
@endsection

