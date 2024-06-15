jQuery(document).ready(function () {

    jQuery('form#userDetail').validate({
        errorClass: 'error-messages',
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
                regex: /^[a-zA-Z]+[a-zA-Z\s]+$/,
            },
            email: {
                required: true,
                email: true,
                regex: /^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/,
            },
            phone_number: {
                required: true,
                regex: /^[(]?[0-9]{3}[)]?[-]?[0-9]{3}[-]?[0-9]{4}$/
            },
            // address1: {
            //     required: true,
            // },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
            // city: {
            //     required: true,
            // },
            postcode: {
                required: true,
            },
            proof: {
                accept: allowedProofExtension,
                filesize: uploadFileSize
            },
            profile_pic: {
                accept: allowedExtensionMessage,
                filesize: uploadFileSize
            }
        },
        messages: {
            name: {
                required: 'This field is required.',
                minlength: 'Name must be 3-50 characters long.',
                maxlength: 'Name must be 3-50 characters long.',
                regex: 'Name contains alphabets and space only.',
            },
            email: {
                required: 'This field is required.',
                email: 'Invalid email address.',
                regex: 'Invalid email address',
            },
            phone_number: {
                required: 'This field is required.',
                regex: "Invalid phone number"
            },
            // address1: {
            //     required: 'This field is required.',
            // },
            country: {
                required: 'This field is required.',
            },
            state: {
                required: 'This field is required.',
            },
            // city: {
            //     required: 'Please select the city',
            // },
            postcode: {
                required: 'This field is required.',
            },
            proof: {
                accept: 'Please upload only ' + allowedProofExtension,
                filesize: 'File size should not be more than ' + uploadFileSizeInMb
            },
            profile_pic: {
                accept: 'Please upload only ' + allowedExtensionMessage,
                filesize: 'File size should not be more than ' + uploadFileSizeInMb
            }
        },
        errorPlacement: function (label, element) {
            if (element.is("textarea")) {
                label.insertAfter(element.next());
            } else if (element.attr('type') == 'file') {
                label.insertAfter(jQuery(element).parent())
            } else if (jQuery(element).hasClass('form-class')) {
                label.insertAfter($(element).parent())
            } else if (jQuery(element).hasClass('form-select')) {
                label.insertAfter($(element).parent())
            } else {
                label.insertAfter(element)
            }
        }
    });

    jQuery('form#changePassword').validate({
        errorClass: "error-messages",
        rules: {
            current_password: {
                required: true
            },
            new_password: {
                required: true,
                minlength: 8,
                maxlength: 32,
                regex: /^[a-zA-Z0-9!@#$%^&*]+$/,
            },
            confirm_password: {
                required: true,
                equalTo: "#newPassword",
            },
        },
        messages: {
            current_password: {
                required: 'This field is required.',
            },
            new_password: {
                required: 'This field is required.',
                minlength: 'Password must be 8-32 characters and contain at least one of the following characters !@#$%^&*',
                maxlength: 'Password must be 8-32 characters and contain at least one of the following characters !@#$%^&*',
                regex: 'Password can contains [a-zA-Z0-9!@#$%^&*] letters.',
            },
            confirm_password: {
                required: 'This field is required.',
                equalTo: 'Confirm password does not match with new password.',
            },
        },
        errorPlacement: function (label, element) {
            label.insertAfter(element.parent().after());
        }
    });

    jQuery('#country').change(function () {
        let countryId = jQuery(this).val();
        let url = APP_URL + '/states/' + countryId;
        if (jQuery.isNumeric(countryId) && countryId > 0) {
            const result = ajaxCall(url, 'get', {});
            result.then(handleCountryData).catch(handleCountryError)
        }
    })

    function handleCountryData(response) {
        let options = '<option value="">Select State</option>';
        jQuery('#state').html(options);
        jQuery('#city').html('<option value="">Select City</option>');
        response.data.forEach(state => {
            options += '<option value="' + state.id + '">' + state.name + '</option>';
        });
        jQuery('#state').html(options);
    }

    function handleCountryError(error) {
        console.log('error', error)
    }


    jQuery('#state').change(function () {
        let stateId = jQuery(this).val();
        let url = APP_URL + '/cities/' + stateId;
        if (jQuery.isNumeric(stateId) && stateId > 0) {
            const result = ajaxCall(url, 'get', {});
            result.then(handleStateData).catch(handleStateError)
        }
    })

    if (stateId) {
        jQuery('#state').trigger('change');
    }

    function handleStateData(response) {
        let options = '<option value="">Select City</option>';
        jQuery('#city').html(options);
        response.data.forEach(city => {
            if (cityId == city.id) {
                var selected = 'selected';
            }
            options += '<option ' + selected + ' value="' + city.id + '">' + city.name + '</option>';
        });
        jQuery('#city').html(options);
    }

    function handleStateError(error) {
        console.log('error', error)
    }
})
