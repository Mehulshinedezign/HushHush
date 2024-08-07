<script>
    jQuery(document).ready(function() {
        
        const rules = {
            current_password: {
                required: true,

            },

            new_password: {
                required: true,
                minlength: passwordMinLength,
                maxlength: passwordMaxLength,
                regex: passwordRegex,
            },
            confirm_password: {
                required: true,
                equalTo: "#confirm_password"
            },

        }
        const messages = {
            current_password: {
                required: `{{ __('customvalidation.user.password.required') }}`,
            },

            new_password: {
                required: `{{ __('customvalidation.user.password.required') }}`,
                minlength: `{{ __('customvalidation.user.password.min', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.user.password.max', ['min' => '${passwordMinLength}', 'max' => '${passwordMaxLength}']) }}`,
                regex: `{{ __('customvalidation.user.password.regex', ['regex' => '${passwordRegex}']) }}`,

            },
            confirm_password: {
                equalTo: `{{ __('customvalidation.user.confirm_password.equal') }}`,
                required: `{{ __('customvalidation.user.confirm_password.required') }}`,
            },

        };

        handleValidation('changePassword', rules, messages);
    });
</script>