@extends('layouts.app')
@section('content')
    <div class="cust-form-bg fill-hight">
        <div class="form-setup login-form">
            <h4>OTP Verification</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('verify') }}">
                @csrf
                <input type="hidden" name="phone_number" value="{{ session()->get('phone_number') }}">
                <div class="form-group">
                    <div class="formfield verify-no">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no1">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no2">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no3">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no4">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no5">
                        <input type="text" maxlength="1" size="1" min="0" max="9" pattern="[0-9]{1}"
                            class="form-control" name="verify_no6">
                    </div>
                </div>
                {{-- <a href="{{ route('password.email') }}" class="resend-otp">resend otp</a> --}}
                <button class="button primary-btn full-btn">Confirm</button>
            </form>
        </div>
    </div>
@endsection
