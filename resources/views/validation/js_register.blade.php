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
            phone_number: {
                required: true,
                digits: true,
                minlength: phoneMinLength,
                maxlength: phoneMaxLength
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
            phone_number: {
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
        };

        handleValidation('register', rules, messages);

       // $("#register").on("submit", function() {
        //   if ($('#register').valid()) {
        //        grecaptcha.ready(function() {
         //           grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {
         //               action: 'subscribe_newsletter'
          //          }).then(function(token) {
          //              $('#register').prepend(
          //                  '<input type="hidden" name="g_recaptcha_response" value="' +
          //                  token + '">');
          //             $('#register').unbind('submit').submit();
          //          });
          //      });
          //     $("#register").find('button').attr('disabled', true);
          //  }
      // });
    });
</script>
