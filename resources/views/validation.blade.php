<script>
    const usernameRegex = {{ config('validation.username_regex') }};

    //media
    const mediaSize = {{ config('validation.js_media_size') }};
    const mediaNameMaxLength = {{ config('validation.js_media_name_max_length') }};
    const mediaMimes = "{{ config('validation.js_media_mimes') }}";

    //name
    const nameRegex = {{ config('validation.name_regex') }};
    const nameMinLength = {{ config('validation.name_minlength') }};
    const nameMaxLength = {{ config('validation.name_maxlength') }};

    //phone
    const phoneMinLength = {{ config('validation.phone_minlength') }};
    const phoneMaxLength = {{ config('validation.phone_maxlength') }};

    //email
    const emailRegex = {{ config('validation.email_regex') }};

    //password
    const passwordRegex = {{ config('validation.password_regex') }};
    const passwordMinLength = parseInt(`${passwordRegex}`.match(/(?<={)\d+/)[0]);
    const passwordMaxLength = parseInt(`${passwordRegex}`.match(/\d+(?=})/)[0]);

    //profile
    const profilePicMimes = "{{ config('validation.js_profile_pic_mimes') }}";
    const profilePicSize = "{{ config('validation.js_profile_pic_size_user') }}";

    //certificate
    const certificateMimes = "{{ config('validation.js_certificate_mimes') }}";
    const certificateSize = "{{ config('validation.js_certificate_size') }}";

    //name
    const packageRegex = {{ config('validation.package_regex') }};
    const packageMinLength = parseInt(`${packageRegex}`.match(/(?<={)\d+/)[0]);
    const packageMaxLength = parseInt(`${packageRegex}`.match(/\d+(?=})/)[0]);

    const accountMinLength = {{ config('validation.account_minlength') }};
    const accountMaxLength = {{ config('validation.account_maxlength') }};

    const routingMinLength = {{ config('validation.routing_minlength') }};
    const routingMaxLength = {{ config('validation.routing_maxlength') }};

    const descriptionMaxLength = {{ config('validation.descriptionMaxLength') }};

    const priceMinLength = {{ config('validation.price_minlength') }};
    const priceMaxLength = {{ config('validation.price_maxlength') }};

    const zipcodeRegex = {{ config('validation.zipcode_regex') }};

    const priceRegex = {{ config('validation.price_regex') }};

    const descMinLength = {{ config('validation.product_desc_minlength') }};
    const descMaxLength = {{ config('validation.product_desc_maxlength') }};
</script>
