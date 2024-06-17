<script>
    const rules = {
        name: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            }
        },
        category: {
            required: true,
        },
        size: {
            required: true,
        },
        brand: {
            required: true,
        },
        color: {
            required: true,
        },
        product_condition: {
            required: true,
        },
        neighborhoodcity: {
            required: true,
        },
        neighborhood: {
            required: true,
        },
        description: {
            required: true,
            minlength: descMinLength,
            maxlength: descMaxLength,
            normalizer: function(value) {
                return $.trim(value);
            }
        },
        rent: {
            required: true,
            // number: true,
            min: priceMinLength,
            max: priceMaxLength,
            regex: priceRegex
        },
        price: {
            required: true,
            // number: true,
            min: priceMinLength,
            max: priceMaxLength,
            regex: priceRegex
        },
        thumbnail_image: {
            required: true,
            accept: "jpg,png,jpeg"
        },
        uploaded_image: {
            required: function() {
                let uploadedImageLength = jQuery('ul.product-img-preview').children().length;
                if (uploadedImageLength > minProductImageCount) {
                    return true;
                }
            }
        },
        product_market_value:{
            required:true,
        },
        product_link:{
            required:true,
        },
        'min_rent_days':{
            required:true,
            regex:minDaysItemRegex,
            range: [3, 7],
        }
    }
    const messages = {
        name: {
            required: `{{ __('customvalidation.product.name.required') }}`,
        },
        category: {
            required: `{{ __('customvalidation.product.category.required') }}`,
        },
        size: {
            required: `{{ __('customvalidation.product.size.required') }}`,
        },
        brand: {
            required: `{{ __('customvalidation.product.brand.required') }}`,
        },
        color: {
            required: `{{ __('customvalidation.product.color.required') }}`,
        },
        product_condition: {
            required: `{{ __('customvalidation.product.condition.required') }}`,
        },
        neighborhoodcity: {
            required: `{{ __('customvalidation.product.city.required') }}`,
        },
        neighborhood: {
            required: `{{ __('customvalidation.product.neighborhood.required') }}`,
        },
        description: {
            required: `{{ __('customvalidation.product.description.required') }}`,
            minlength: `{{ __('customvalidation.product.description.min', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
            maxlength: `{{ __('customvalidation.product.description.max', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
        },
        rent: {
            required: `{{ __('customvalidation.product.rent.required') }}`,
            min: `Invalid price.`,
            max: `Invalid price.`,
            regex: `{{ __('customvalidation.product.rent.regex') }}`,
        },
        price: {
            required: `{{ __('customvalidation.product.price.required') }}`,
            min: `Invalid price.`,
            max: `Invalid price.`,
            regex: `{{ __('customvalidation.product.price.regex') }}`,
        },
        thumbnail_image: {
            required: `{{ __('customvalidation.product.image.required') }}`,
            accept: "Only image type jpg/png/jpeg is allowed"
        },
        uploaded_image: {
            required: `{{ __('customvalidation.product.uploaded_image.required', ['min' => '${minProductImageCount}']) }}`,
        },
        product_market_value: {
            required: `{{ __('customvalidation.product.product_market_value.required') }}`,
        },
        product_link: {
            required: `{{ __('customvalidation.product.product_link.required') }}`,
        },
        min_rent_days:{
            required: `{{ __('customvalidation.product.min_rent_days.required') }}`,
            regex: `{{ __('customvalidation.product.min_rent_days.regex', ['regex' => '${minDaysItemRegex}']) }}`,
            range: `{{ __('customvalidation.product.min_rent_days.range', ['min' => 3, 'max' => 7]) }}`
        },
    };

    // jQuery.validator.addClassRules("location-required", {
    //     locationRequired: true,
    //     normalizer: function(value) {
    //         return $.trim(value);
    //     }
    // });
    // jQuery.validator.addMethod("locationRequired", jQuery.validator.methods.required,
    //     `{{ __('customvalidation.product.location.required') }}`);

    // jQuery.validator.addClassRules("location-custom-required", {
    //     locationCustomRequired: true,
    //     normalizer: function(value) {
    //         return $.trim(value);
    //     }
    // });
    // jQuery.validator.addMethod("locationCustomRequired", jQuery.validator.methods.required,
    //     `{{ __('customvalidation.product.custom_location.required') }}`);

    // jQuery.validator.addClassRules("size-required", {
    //     SizeRequired: true,
    //     normalizer: function(value) {
    //         return $.trim(value);
    //     }
    // });
    // jQuery.validator.addMethod("SizeRequired", jQuery.validator.methods.required,
    //     `{{ __('customvalidation.product.size.required') }}`);


</script>
