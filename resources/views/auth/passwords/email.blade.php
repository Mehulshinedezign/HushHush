@extends('layouts.app')
@section('content')
<div class="cust-form-bg">
    <div class="form-setup login-form">
        <h4>Forgot Password</h4>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form class="form-inline" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="html">Email Address</label>
                <div class="formfield">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>        
                    <span class="icon">
                         <i class="fa-solid fa-envelope"></i>
                    </span>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>                    
            <button type="submit" class="primary-btn width-full">{{ __('Send Password Reset Link') }}</button>                    
            <p class="have-account">
                <a href="{{ route('login') }}">Back to Login</a>
            </p>
        </form>
    </div>
</div>
@endsection
