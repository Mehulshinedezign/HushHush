@extends('layouts.app')
@section('content')
    <div class="cust-form-bg full-hight-sm">
        <div class="form-setup login-form">
            <h4>OTP Verification</h4>
            <x-alert />
            <form class="form-inline" method="POST" action="{{ route('verify') }}" id="verify_otp">
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
                <div class="error-message invalid-feedback"></div>
                {{-- <a href="{{ route('password.email') }}" class="resend-otp">resend otp</a> --}}
                <button class="button primary-btn full-btn">Confirm</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>    
    $(document).ready(function() {
    
        $(".formfield input").on("input", function() {
            var $input = $(this);
            if ($input.val().length >= $input.attr("maxlength")) {
                $input.next('input').focus();
            } else if ($input.val().length === 0) {
                $input.prev('input').focus();
            }
            
            checkIfAllFilled(); 
        });
    
        function validateOTP() {
            let isValid = true;
            let emptyFields = 0;
    
            $(".formfield input").each(function() {
                if ($(this).val().trim() === "") {
                    isValid = false;
                    emptyFields++;
                }
            });
    
            if (emptyFields > 0) {
                $(".error-message").text("Please fill in all OTP fields.").show();
            } else {
                $(".error-message").hide();
            }
    
            return isValid;
        }
    
        $('#verify_otp').on('submit', function(e) {
            e.preventDefault();
            if (validateOTP()) {
                this.submit(); 
            }
        });
    
        $(".error-message").hide();
    
        function checkIfAllFilled() {
            let allFilled = $(".formfield input").toArray().every(input => $(input).val().trim() !== '');
            
            if (allFilled) {
                $('.error-message').hide();
            } else {
                $('.error-message').show();
            }
        }
    
    });
    </script>
    
@endpush


