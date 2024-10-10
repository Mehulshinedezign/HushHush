@extends('layouts.front')
@section('content')
    {{-- <x-alert /> --}}
    <div class="cust-form-bg fill-hight">
        <div class="form-setup ">
            <h4>Change Password</h4>
            <form class="change-password form-inline" method="post" id="changePassword" action="{{ route('changepassword') }}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control" name="current_password" placeholder="Enter your password">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                        @error('current_password')
                            <label class="error-messages">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="Enter your password">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                        @error('new_password')
                            <label class="error-messages">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="formfield">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Enter your password">
                        <span class="form-icon extra-icon">
                            <i class="fa-solid fa-lock togglePassword"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" id="updatePassword" class="button primary-btn full-btn">Done <div class="spinner-border passwordUpdate d-none" role="status">
                    <span class="sr-only"></span>
                  </div></button>
            </form>
            
        </div>
    </div>
@endsection

@push('scripts')
@includeFirst(['validation'])
@includeFirst(['validation.js_changePassword'])
@includeFirst(['validation.js_show_password'])
    <script src="{{ asset('js/custom/profile.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    {{-- <script src="{{ asset('js/custom/card.js') }}"></script> --}}
@endpush
