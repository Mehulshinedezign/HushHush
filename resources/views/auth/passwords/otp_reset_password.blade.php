@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <h4>Reset Password</h4>
            <form class="form-inline" action="{{ route('user.updatepassword') }}" method="POST" id="register">
                @csrf
                <input type="hidden" name="phone_number" value="{{ $phone_number }}">
                {{-- <div class="form-group">
                <label for="html">New Password</label>
                <div class="formfield">
                    <input type="password" class="form-control" name="password"
                        placeholder="Enter your password">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                </div>
               
            </div> --}}
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
                {{-- <div class="form-group">
                <label for="html">Confirm Password</label>
                <div class="formfield">
                    <input type="password" class="form-control" name="password"
                        placeholder="Enter your password">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                </div>
               
            </div> --}}
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
                <button class="button primary-btn full-btn">Done</button>
                <p class="have-account">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_register'])
    @includeFirst(['validation.js_show_password'])
@endpush
