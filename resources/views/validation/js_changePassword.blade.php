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
                equalTo: "#newPassword"
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
        
        $('#changePassword').submit(function(e) {
            e.preventDefault(); // Prevent form submission
            $('.passwordUpdate').removeClass('d-none');  
            handleValidation('changePassword', rules, messages);
            // Perform form validation

            // Check if the form is valid
            if ($('#changePassword').valid()) {
                $('#updatePassword').attr('disabled', true);
                this.submit(); 
            }else{
            $('.passwordUpdate').addClass('d-none');  
            }
        });
        
    });
</script>