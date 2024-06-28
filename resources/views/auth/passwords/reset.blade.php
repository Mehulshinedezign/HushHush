@extends('layouts.app')
@section('content')
    <div class="cust-form-bg">
        <div class="form-setup login-form">
            <h4>Reset Password</h4>
            <form class="form-inline" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                {{-- <div class="form-group">
                <label for="html">Email Address</label>
                <div class="formfield">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    <span class="icon">
                        <svg width="22" height="15" viewBox="0 0 22 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.66371 0.941406C1.43464 0.941406 1.21568 0.989306 1.02054 1.07806L10.2178 9.30251C10.4367 9.49816 10.8993 9.49816 11.1182 9.30251L20.3154 1.07806C20.1203 0.989306 19.9013 0.941406 19.6722 0.941406H1.66371ZM0.280908 1.80164C0.1797 2.00734 0.120117 2.23941 0.120117 2.48499V12.7756C0.120117 13.6307 0.808558 14.3192 1.66371 14.3192H19.6722C20.5274 14.3192 21.2158 13.6307 21.2158 12.7756V2.48499C21.2158 2.23941 21.1562 2.00734 21.055 1.80164L11.8015 10.0663C11.1582 10.6412 10.1777 10.6412 9.53441 10.0663L0.280908 1.80164Z"
                                fill="#DEE0E3" />
                        </svg>
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
                        <span class="form-icon">
                            <svg width="17" height="21" viewBox="0 0 17 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.6689 7.62891V5.62891C13.6689 2.82891 11.4689 0.628906 8.66895 0.628906C5.86895 0.628906 3.66895 2.82891 3.66895 5.62891V7.62891C1.96895 7.62891 0.668945 8.92891 0.668945 10.6289V17.6289C0.668945 19.3289 1.96895 20.6289 3.66895 20.6289H13.6689C15.3689 20.6289 16.6689 19.3289 16.6689 17.6289V10.6289C16.6689 8.92891 15.3689 7.62891 13.6689 7.62891ZM5.66895 5.62891C5.66895 3.92891 6.96895 2.62891 8.66895 2.62891C10.3689 2.62891 11.6689 3.92891 11.6689 5.62891V7.62891H5.66895V5.62891ZM9.66895 15.6289C9.66895 16.2289 9.26895 16.6289 8.66895 16.6289C8.06895 16.6289 7.66895 16.2289 7.66895 15.6289V12.6289C7.66895 12.0289 8.06895 11.6289 8.66895 11.6289C9.26895 11.6289 9.66895 12.0289 9.66895 12.6289V15.6289Z"
                                    fill="#DEE0E3" />
                            </svg>
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
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}" />
                <button type="submit" class="primary-btn width-full">Reset Password</button>
            </form>
        </div>
    </div>
@endsection
