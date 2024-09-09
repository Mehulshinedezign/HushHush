<script>
    $(document).ready(function() {
        // var url = window.location.href;
        // var path = new URL(url).pathname;
        // var segments = path.split('/');
        // var action1 = segments[1];
        // var action2 = segments[2];
        // const url = action1+'/'+action2;
        const commission = parseInt("{{ adminsetting()->value }}");

        $.validator.addMethod("completeAddress", function(value, element) {
            return $('#product_address1').val() !== ''&& $(
                '#product_country').val() !== '' && $('#product_state').val() !== '' ;
        }, "Please enter the complete address");
        const rules = {
            name: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                }
            },
            // image: {
            //     required: true,
            // },
            // 'new_images[]':{
            //     required: true,
            //     accept: "image/*",
            //     maxfiles: 5
            // }
            'images[]': {
                required: true,
                accept: "image/*",
                maxfiles: 5,

            },
            product_name: {
                required: true,
            },
            category: {
                required: true,
            },
            // subcategory: {
            //     required: true,
            // },
            product_condition: {
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
            // state: {
            //     required: true,
            // },
            // city: {
            //     required: true,
            // },
            description: {
                required: true,
                minlength: descMinLength,
                maxlength: descMaxLength,
                normalizer: function(value) {
                    return $.trim(value);
                }
            },
            // pick_up_location: {
            //     required: true,
            // },
            price: {
                required: true,
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
                    return uploadedImageLength <= minProductImageCount;
                }
            },
            product_market_value: {
                required: true,
                regex: OnlydigitRegex,
            },
            product_link: {
                // required: true,
                pattern:  /^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,6}(\/[\w#.-]*)*(\?[a-zA-Z0-9_=&%-]*)?\/?$/,
            },


            min_rent_days: {
                required: true,
                regex: minDaysItemRegex,
                range: [1, 30],
            },
            rent_price_day: {
                required: true,
                regex: OnlydigitRegex,
                min: commission,

            },
            rent_price_week: {
                required: true,
                regex: OnlydigitRegex,
                min: commission,
            },
            rent_price_month: {
                required: true,
                regex: OnlydigitRegex,
                min: commission,
            },
            // non_available_dates: {
            //     required: true,
            // },
            // country:{
            //     required:true,
            // },
            product_complete_location: {
                required: true,
                completeAddress: true,
            },


        };

        const messages = {
            // image: {
            //     required: `{{ __('customvalidation.product.image.required') }}`,
            // },
            // new_images:{
            //     "Please upload at least one image"
            // },
            'images[]': {
                required: "Please upload at least one image",
                accept: "Please upload only image files",
                maxfiles: "You can upload a maximum of 5 images"
            },

            product_name: {
                required: `{{ __('customvalidation.product.product_name.required') }}`,
            },
            category: {
                required: `{{ __('customvalidation.product.category.required') }}`,
            },
            // subcategory: {
            //     required: `{{ __('customvalidation.product.subcategory.required') }}`,
            // },
            product_condition: {
                required: `{{ __('customvalidation.product.condition.required') }}`,
            },
            size: {
                required: 'Please select the size',
            },
            brand: {
                required: 'Please select the brand',
            },
            color: {
                required: 'Please select the color',
            },
            // state: {
            //     required: `{{ __('customvalidation.product.state.required') }}`,
            // },
            // city: {
            //     required: `{{ __('customvalidation.product.city.required') }}`,
            // },
            // pick_up_location: {
            //     required: `{{ __('customvalidation.product.pick_up_location.required') }}`,
            // },
            description: {
                required: `{{ __('customvalidation.product.description.required') }}`,
                minlength: `{{ __('customvalidation.product.description.min', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
                maxlength: `{{ __('customvalidation.product.description.max', ['min' => '${descMinLength}', 'max' => '${descMaxLength}']) }}`,
            },
            rent_price_day: {
                required: `{{ __('customvalidation.product.rent_price_day.required') }}`,
                regex: `{{ __('customvalidation.product.rent_price_day.regex', ['regex' => '${OnlydigitRegex}']) }}`,
                min: `Please enter the price greater than ${commission}`,
            },
            rent_price_week: {
                required: `{{ __('customvalidation.product.rent_price_week.required') }}`,
                regex: `{{ __('customvalidation.product.rent_price_week.regex', ['regex' => '${OnlydigitRegex}']) }}`,
                min: `Please enter the price greater than ${commission}`,

            },
            rent_price_month: {
                required: `{{ __('customvalidation.product.rent_price_month.required') }}`,
                regex: `{{ __('customvalidation.product.rent_price_month.regex', ['regex' => '${OnlydigitRegex}']) }}`,
                min: `Please enter the price greater than ${commission}`,

            },
            product_market_value: {
                required: `{{ __('customvalidation.product.product_market_value.required') }}`,
                regex: `{{ __('customvalidation.product.product_market_value.regex', ['regex' => '${OnlydigitRegex}']) }}`,
            },
            product_link: {
                // required: `{{ __('customvalidation.product.product_link.required') }}`,
                pattern: 'Please enter valid url link.',
            },
            min_rent_days: {
                required: `{{ __('customvalidation.product.min_rent_days.required') }}`,
                regex: `{{ __('customvalidation.product.min_rent_days.regex', ['regex' => '${minDaysItemRegex}']) }}`,
                range: `{{ __('customvalidation.product.min_rent_days.range', ['min' => 1, 'max' => 30]) }}`
            },
            // country:{
            //     required: "Please enter country.",
            // },
            product_complete_location: {
                required: `{{ __('customvalidation.product.product_complete_location.required') }}`,
                completeAddress: 'Please enter the complete address',
            }
        };


        jQuery.validator.addMethod("maxfiles", function(value, element, param) {
            return this.optional(element) || element.files.length <= param;
        }, "You can select a maximum of {0} files.");


        // jQuery('#addProduct').submit(function(e) {
        //     e.preventDefault();
        //     handleValidation('addProduct', rules, messages);
        //     if ($('#addProduct').valid()) {
        //         e.currentTarget.submit();
        //     }
        // });



        $('#addProduct').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            // Perform form validation
            handleValidation('addProduct', rules, messages);

            // Check if the form is valid
            if ($('#addProduct').valid()) {
                $('body').addClass('loading');
                setTimeout(function() {
                    e.currentTarget.submit();
                }, 1000); // Adjust delay as needed
            }
        });


        $('#product_address1,#product_address2').on('change', function() {
            // alert('hello');
            // $('#product_address').valid();
            handleValidation('addProduct', rules, messages);
            $('#addProduct').validate({
                rules: rules,
                messages: messages,
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    });
    // Initialize form validation
</script>
