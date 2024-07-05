<script>
    jQuery(document).ready(function() {
        $("#register").find('button').attr('disabled', false);
        const rules = {
            username: {
                required: true,
                minlength: nameMinLength,
                maxlength: nameMaxLength,
                regex: usernameRegex,
            },
            name: {
                required: true,
                minlength: nameMinLength,
                maxlength: nameMaxLength,
                regex: nameRegex,
            },
            email: {
                required: true,
                email: true,
                regex: emailRegex,
            },
            "phone_number[main]": {
                required: true,
                digits: true,
                minlength: phoneMinLength,
                maxlength: phoneMaxLength,
                isValidPhoneNumber:true,
                normalizer: function(value) {
                return $.trim(value);
                }
            },
            password: {
                required: true,
                minlength: passwordMinLength,
                maxlength: passwordMaxLength,
                regex: passwordRegex,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password_confirmation"
            },
            zipcode: {
                required: true,
                regex: zipcodeRegex,
            },
            // complete_address:{
            //     required: true,
            //     minlength: minCompleteAddress,
            //     maxlength: maxCompleteAddress,
            // },
            gov_id: {
                required:true,
                filesize: gov_idSize,
                extension: gov_idMimes,
            },
        }
        const messages = {
            username: {
                required: `{{ __('customvalidation.user.username.required') }}`,
                minlength: `{{ __('customvalidation.user.username.min', ['min' => '${nameMinLength}', 'max' => '${nameMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.username.max', ['min' => '${nameMinLength}', 'max' => '${nameMaxLength}']) }}`,
                regex: `{{ __('customvalidation.user.username.regex', ['regex' => '${usernameRegex}']) }}`,
            },
            name: {
                required: `{{ __('customvalidation.user.name.required') }}`,
                minlength: `{{ __('customvalidation.user.name.min', ['min' => '${nameMinLength}', 'max' => '${nameMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.name.max', ['min' => '${nameMinLength}', 'max' => '${nameMaxLength}']) }}`,
                regex: `{{ __('customvalidation.user.name.regex', ['regex' => '${nameRegex}']) }}`,
            },
            email: {
                required: `{{ __('customvalidation.user.email.required') }}`,
                email: `{{ __('customvalidation.user.email.email') }}`,
                regex: `{{ __('customvalidation.user.email.regex', ['regex' => '${emailRegex}']) }}`,
            },
            "phone_number[main]": {
                required: `{{ __('customvalidation.user.phone_number.required') }}`,
                digits: `{{ __('customvalidation.user.phone_number.digits') }}`,
                minlength: `{{ __('customvalidation.user.phone_number.min', ['min' => '${phoneMinLength}', 'max' => '${phoneMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.phone_number.max', ['min' => '${phoneMinLength}', 'max' => '${phoneMaxLength}']) }}`,
            },
            password: {
                required: `{{ __('customvalidation.user.password.required') }}`,
                minlength: `{{ __('customvalidation.user.password.min', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.password.max', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                regex: `{{ __('customvalidation.user.password.regex', ['regex' => '${passwordRegex}']) }}`,

            },
            password_confirmation: {
                equalTo: `{{ __('customvalidation.user.confirm_password.equal') }}`,
                required: `{{ __('customvalidation.user.confirm_password.required') }}`,
            },
            zipcode: {
                required: `{{ __('customvalidation.user.zipcode.required') }}`,
                regex: `{{ __('customvalidation.user.zipcode.regex', ['regex' => '${zipcodeRegex}']) }}`,
            },
            // complete_address: {
            //     required: `{{ __('customvalidation.user.complete_address.required') }}`,
            //     minlength: `{{ __('customvalidation.user.complete_address.min', ['min' => '${minCompleteAddress}', 'max' => '${maxCompleteAddress}']) }}`,
            //     maxlength: `{{ __('customvalidation.user.complete_address.max', ['min' => '${minCompleteAddress}', 'max' => '${maxCompleteAddress}']) }}`,
            // },
            gov_id: {
                required:  `{{ __('customvalidation.user.gov_id.required') }}`,
                extension:     `{{ __('customvalidation.user.gov_id.file') }}`,
                filesize: `{{ __('customvalidation.user.gov_id.max_size') }}`,
            },
        };
        $.validator.addMethod("isValidPhoneNumber", function(value, element) {
            return phone_number.isValidNumber();
        }, "Please enter a valid phone number");

        handleValidation('register', rules, messages);



    });
</script>
