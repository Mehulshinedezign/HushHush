@extends('layouts.app')
@section('content')
<div class="cust-form-bg fill-hight">
    <div class="form-setup login-form">
        <h4>Reset Password</h4>
        <form class="form-inline" action="{{ route('user.updatepassword') }}" method="POST">
            @csrf
            <input type="hidden" name="phone_number" value="{{ $phone_number }}">
            <div class="form-group">
                <label for="html">New Password</label>
                <div class="formfield">
                    <input type="password" class="form-control" name="password"
                        placeholder="Enter your password">
                    <span class="form-icon">
                        <svg width="17" height="21" viewBox="0 0 17 21" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.6689 7.62891V5.62891C13.6689 2.82891 11.4689 0.628906 8.66895 0.628906C5.86895 0.628906 3.66895 2.82891 3.66895 5.62891V7.62891C1.96895 7.62891 0.668945 8.92891 0.668945 10.6289V17.6289C0.668945 19.3289 1.96895 20.6289 3.66895 20.6289H13.6689C15.3689 20.6289 16.6689 19.3289 16.6689 17.6289V10.6289C16.6689 8.92891 15.3689 7.62891 13.6689 7.62891ZM5.66895 5.62891C5.66895 3.92891 6.96895 2.62891 8.66895 2.62891C10.3689 2.62891 11.6689 3.92891 11.6689 5.62891V7.62891H5.66895V5.62891ZM9.66895 15.6289C9.66895 16.2289 9.26895 16.6289 8.66895 16.6289C8.06895 16.6289 7.66895 16.2289 7.66895 15.6289V12.6289C7.66895 12.0289 8.06895 11.6289 8.66895 11.6289C9.26895 11.6289 9.66895 12.0289 9.66895 12.6289V15.6289Z"
                                fill="#DEE0E3" />
                        </svg>
                    </span>
                </div>
               
            </div>
            <div class="form-group">
                <label for="html">Confirm Password</label>
                <div class="formfield">
                    <input type="password" class="form-control" name="password"
                        placeholder="Enter your password">
                    <span class="form-icon">
                        <svg width="17" height="21" viewBox="0 0 17 21" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.6689 7.62891V5.62891C13.6689 2.82891 11.4689 0.628906 8.66895 0.628906C5.86895 0.628906 3.66895 2.82891 3.66895 5.62891V7.62891C1.96895 7.62891 0.668945 8.92891 0.668945 10.6289V17.6289C0.668945 19.3289 1.96895 20.6289 3.66895 20.6289H13.6689C15.3689 20.6289 16.6689 19.3289 16.6689 17.6289V10.6289C16.6689 8.92891 15.3689 7.62891 13.6689 7.62891ZM5.66895 5.62891C5.66895 3.92891 6.96895 2.62891 8.66895 2.62891C10.3689 2.62891 11.6689 3.92891 11.6689 5.62891V7.62891H5.66895V5.62891ZM9.66895 15.6289C9.66895 16.2289 9.26895 16.6289 8.66895 16.6289C8.06895 16.6289 7.66895 16.2289 7.66895 15.6289V12.6289C7.66895 12.0289 8.06895 11.6289 8.66895 11.6289C9.26895 11.6289 9.66895 12.0289 9.66895 12.6289V15.6289Z"
                                fill="#DEE0E3" />
                        </svg>
                    </span>
                </div>
               
            </div>
            <button class="button primary-btn full-btn">Done</button>
            <p class="have-account">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
        </form> 
    </div>
</div>
@endsection
