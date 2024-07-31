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
            // email: {
            //     required: true,
            //     email: true,
            //     regex: /^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/,
            // },
            phone_number: {
                required: true,
                regex: /^[(]?[0-9]{3}[)]?[-]?[0-9]{3}[-]?[0-9]{4}$/
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
            city: {
                required: true,
            },
            postcode: {
                required: true,
            },
            proof: {
                accept: allowedProofExtension,
                filesize: uploadFileSize
            },
            profile_pic: {
                // required: true,
                accept: allowedExtensionMessage,
                filesize: uploadFileSize
            },
            address1: {
                required: true,
            },
            address2: {
                required: true,
            },
            // password: {
            //     required: true,
            //     minlength: passwordMinLength,
            //     maxlength: passwordMaxLength,
            //     regex: passwordRegex,
            // },
            // confirm_password: {
            //     required: true,
            //     equalTo: "[name='password']"
            // },
        },
        messages: {
            name: {
                required: 'This field is required.',
                minlength: 'Name must be 3-50 characters long.',
                maxlength: 'Name must be 3-50 characters long.',
                regex: 'Name should contain alphabets and in-between space only.',
            },
            // email: {
            //     required: 'This field is required.',
            //     email: 'Invalid email address.',
            //     regex: 'Invalid email address',
            // },
            phone_number: {
                required: 'This field is required.',
                regex: "Invalid phone number"
            },
            country: {
                required: 'Please select a country.',
            },
            state: {
                required: 'Please select a state.',
            },
            city: {
                required: 'Please select a city.',
            },
            postcode: {
                required: 'This field is required.',
            },
            proof: {
                accept: 'Please upload only ' + allowedProofExtension,
                filesize: 'File size should not be more than ' + uploadFileSizeInMb
            },
            profile_pic: {
                // required: 'This field is required.',
                accept: 'Please upload only ' + allowedExtensionMessage,
                filesize: 'File size should not be more than ' + uploadFileSizeInMb
            },
            address1: {
                required: 'This field is required.',
            },
            address2: {
                required: 'This field is required.',
            },
            // password: {
            //     required: 'This field is required.',
            //     minlength: 'Password must be at least ' + passwordMinLength + ' characters long.',
            //     maxlength: 'Password cannot exceed ' + passwordMaxLength + ' characters.',
            //     regex: 'The password must contain at least one uppercase , one lowercase , one numeric digit and one special character from the following: @#$%^&*.',
            // },
            // confirm_password: {
            //     equalTo: 'Passwords must match.',
            //     required: 'This field is required.',
            // },
        },
        errorPlacement: function (label, element) {
            if (element.is("textarea")) {
                label.insertAfter(element.next());
            }
            //  else if (element.attr('type') == 'file') {
            //     label.insertAfter(jQuery(element).parent())
            // }
             else if (jQuery(element).hasClass('form-class')) {
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

        let selectedOption = jQuery(this).find('option:selected');
        let countryId = selectedOption.data('country_id');
        // alert(countryId);
        let url = `${APP_URL}/states/${countryId}`;
    
        if (countryId) {
            ajaxCall(url, 'get', {})
                .then(handleCountryData)
                .catch(handleCountryError);
        } else {
            jQuery('#state').html('<option value="">Select State</option>');
            jQuery('#city').html('<option value="">Select City</option>');
        }
    });
    
    function handleCountryData(response) {
        let options = '<option value="">Select State</option>';
        jQuery('#state').html(options);
        jQuery('#city').html('<option value="">Select City</option>');
    
        if (Array.isArray(response)) {
            response.forEach(state => {
                options += `<option value="${state.name}" data-state_id="${state.id}">${state.name}</option>`;
            });
            jQuery('#state').html(options);
        } else {
            console.error('Unexpected response format:', response);
        }
    }
    
    function handleCountryError(error) {
        console.error('Error fetching states:', error);
    }
    
        jQuery('#state').change(function () {
            // let stateId = $(this).val();
            let selectedOption = jQuery(this).find('option:selected');
            let stateId = selectedOption.data('state_id');
    
            if (stateId) {
                let url = `${APP_URL}/cities/${stateId}`;
                ajaxCall(url, 'GET', {})
                    .then(handleStateData)
                    .catch(handleStateError);
            } else {
                jQuery('#city').html('<option value="">Select City</option>');
            }
        });
    
        function handleStateData(response) {
            if (Array.isArray(response)) {
                let options = '<option value="">Select City</option>';
                response.forEach(city => {
                    if (city && city.name) {
                        options += `<option value="${city.name}">${city.name}</option>`;
                    }
                });
                jQuery('#city').html(options);
            } else {
                console.error('Unexpected response format:', response);
            }
        }
        function handleStateError(error) {
            console.error('Error fetching cities:', error);
        }

});