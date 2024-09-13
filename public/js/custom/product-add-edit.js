jQuery(document).on("click", ".open-product-popup", function (event) {
    event.preventDefault();

    if (jQuery(this).attr("href")) {
        jQuery('#NproductModal').modal('show')

    }
    //  else if (product_check == 1 && checkdocuments == 0) {
    //     jQuery('#NproductModal').modal('hide')
    //     jQuery('#mutlistepForm1').modal("show");

    // } 
    else if (product_check == 1 && !is_bankdetail) {
        jQuery('#NproductModal').modal('hide')
        jQuery('#mutlistepForm2').modal("show");

    } else {

        jQuery('#NproductModal').modal('show')
    }
    var insertHtml = htmlForm;
    var other = '';
    if ($(this).attr("href")) {
        $.ajax({
            url: $(this).attr("href"),
            success: function (result) {
                if (result.status)
                    insertHtml = result.data.html;
                insertHtml_cat = result.data.subcat;
                insertHtml_type = result.data.type;
                insertHtml_size = result.data.size;
                insertHtml_neighborhoodcity = result.data.neighborhoodcity
                other = result.data.other;


            },
            async: false
        });
        $("#NproductModal").modal("show");
    }
    $("#ajax-form-html").html(insertHtml);
    if ($(this).attr("href")) {
        $("#sub_cat").html(insertHtml_cat);
        $("#type").html(insertHtml_type);
        $("#size").html(insertHtml_size);
        $("#neighborhood").html(insertHtml_neighborhoodcity);
        if (other) {
            $("select[name='size']").addClass('d-none');
            $("input[name='other_size']").removeClass('d-none');
            // add or remove require class
            $("select[name='size']").removeClass('size-required');
            $("input[name='other_size']").addClass('size-required');
        } else {
            $("select[name='size']").removeClass('d-none');
            $("input[name='other_size']").addClass('d-none');
            // add or remove require class
            $("select[name='size']").addClass('size-required');
            $("input[name='other_size']").removeClass('size-required');
        }
    }
    // if (jQuery('#addProduct').valid()) {
    // }
    jQuery('#addProduct').submit(function (e) {
        e.preventDefault();
        handleValidation('addProduct', rules, messages);
        if ($('#addProduct').valid()) {
            productformData = new FormData($('form#addProduct').get(0));
            var url = jQuery('#addProduct').attr('action');
            response = ajaxCall(url, 'post', productformData)
            response.then(response => {
                if (response.success) {
                    setCookie('img_token', '', '-1');
                    //console.log(response.checkdocuments, "check response here");
                    // if (response.checkdocuments == 0) {
                    //     jQuery('#mutlistepForm1').modal("show");
                    //     jQuery("#NproductModal").modal("hide");

                    // } else

                    getId = response.product;
                    localStorage.setItem("getid", getId);
                    if (!response.is_bankdetail && response.product) {
                        jQuery('#mutlistepForm2').modal("show");
                        jQuery("#NproductModal").modal("hide");
                    }
                    else {
                        // window.location.href = response.url

                        window.location.replace(APP_URL + '/' + 'products')
                    }
                }

            }).catch(error => {
                return iziToast.error({
                    title: 'Error',
                    message: error.msg,
                    position: 'topRight'
                });
            });


        }

    });
    var amountCheck = jQuery("#rentprice").val();
    if (amountCheck > 0) {
        var admincommision = amountCheck * commision / 100;
        var lenderrestamount = amountCheck - admincommision;
        jQuery('#amount').html("<small>Estimated Earnings/Day: $</small>" + parseFloat(lenderrestamount).toFixed(2));
        if (amountCheck == 0 || amountCheck == undefined) {
            jQuery('#amount').html("");

        }
    }

    var status = jQuery("#status").val();
    if (status == '0') {
        jQuery('#status').removeAttr('checked');
    }
});

// delete product
var getId = ''

jQuery('#close_bankdetails').on('click', function () {
    var product = localStorage.getItem("getid", getId);
    var productformData = new FormData();

    productformData.append('text', 'product');
    var producturl = APP_URL + "/" + "products/" + product + "/delete";
    var response = ajaxCall(producturl, 'post', productformData);
    response.then(response => {
        if (response.success) {
            localStorage.removeItem('getid');
            window.location.replace(APP_URL + '/' + 'products')
        }

    })
});


jQuery(document).ready(function () {

    var product = localStorage.getItem("getid", getId);
    // console.log("checkproduct", product);
    if (!is_bankdetail && product) {
        var productformData = new FormData();

        productformData.append('text', 'product');
        var producturl = APP_URL + "/" + "products/" + product + "/delete";
        var response = ajaxCall(producturl, 'post', productformData);
        response.then(response => {
            //console.log(response.success);
            if (response.success) {
                localStorage.removeItem('getid');
                window.location.replace(APP_URL + '/' + 'products')
            }

        })
    }
});
// end

// jQuery(document).on('click', "#submit-identification", function () {
jQuery(document).ready(function () {
    if (!is_bankdetail) {
        var startDate = moment().subtract(13, 'year');
    }
    // else if (is_bankdetail) {
    //     var startDate = getstartDate;
    // }
    // jQuery('input[name="account_holder_dob"]').daterangepicker({
    //     singleDatePicker: true,
    //     autoUpdateInput: false,
    //     startDate: startDate,
    //     locale: {
    //         format: dateFormat,
    //         cancelLabel: 'Clear'
    //     },
    //     showDropdowns: true,
    //     minYear: 1901,
    //     maxYear: parseInt(moment().format('YYYY'), 10)
    // }, function (start, end, label) {
    //     var years = moment().diff(start, 'years');
    // });

    $('input[name="account_holder_dob"]').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('input[name="account_holder_dob"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
})

// Prevent NULL input and replace text.
$(document).on('change', '#retailvalue', function (event) {
    this.value = this.value.replace(/[^0-9]+/g, '');
    if (this.value < 1) this.value = 0;
});

// Block non-numeric chars.
$(document).on('keypress', '#retailvalue', function (event) {
    return (((event.which > 47) && (event.which < 58)) || (event.which == 13));
});

// Prevent NULL input and replace text.
$(document).on('change', '#rentprice', function (event) {
    this.value = this.value.replace(/[^0-9]+/g, '');
    if (this.value < 1) this.value = 0;
});

// Block non-numeric chars.
$(document).on('keypress', '#rentprice', function (event) {
    return (((event.which > 47) && (event.which < 58)) || (event.which == 13));
});
// submit identificationprofile
jQuery(document).on('click', '#submit-identification', function (e) {
    e.preventDefault();
    jQuery('#identificationprofile').validate({
        errorClass: "error-messages",
        rules: {
            proof: {
                required: true,
            },
        },
        messages: {
            proof: {
                required: "Please enter id proof",
            },
        }

    });
    if ($('#identificationprofile').valid()) {
        var formData = new FormData($('form#identificationprofile').get(0));
        var response = ajaxCall($("#identificationprofile").attr("action"), 'post', formData);
        response.then(response => {
            console.log(response.success);
            if (response.success) {
                if (response.success) {
                    if (!is_bankdetail)
                        jQuery('#mutlistepForm2').modal("show");
                    jQuery('#mutlistepForm1').modal("hide");
                }
            }

        }).catch(error => {
            return iziToast.error({
                title: 'Error',
                message: error.msg,
                position: 'topRight'
            });
        });

    }
});

jQuery(document).on('click', '#submit-bankdetails', function (e) {
    e.preventDefault();
    jQuery(document).find("form#bankdetails").validate({
        errorClass: "error-messages",
        rules: {
            account_holder_first_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            account_holder_last_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            // account_holder_type: {
            //     required: true,
            // },
            // account_type: {
            //     required: true,
            //     inarray: ['Custom', 'Express', 'Standard']
            // },
            account_number: {
                required: true,
                minlength: 10,
                maxlength: 12,
            },
            routing_number: {
                required: true,
                minlength: 9,
                maxlength: 9,
            },
        },
        messages: {
            account_holder_first_name: {
                required: 'This field is required.',
                regex: 'Account holder first name should not contain any spaces, only alphabetical characters are allowed.'
            },
            account_holder_last_name: {
                required: 'This field is required.',
                regex: 'Account holder last name should not contain any spaces, only alphabetical characters are allowed.'
            },
            // account_holder_type: {
            //     required: 'Please enter the account holder type',
            // },
            // account_type: {
            //     required: 'Please select the account type',
            //     inarray: 'Account type can be Custom, Express or Standard'
            // },
            account_number: {
                required: 'This field is required.',
                minlength: 'Account number must be 10-12 digits.',
                maxlength: 'Account number must be 10-12 digits.',
            },
            routing_number: {
                required: 'This field is required.',
                minlength: 'Routing number must be 9 digits.',
                maxlength: 'Routing number must be 9 digits.',
            },
        }
    });

    if (jQuery(document).find("form#bankdetails").valid()) {
        var formData = new FormData($('form#bankdetails').get(0));
        var url = APP_URL + '/bankdetails'
        var response = ajaxCall($("#bankdetails").attr("action"), 'post', formData);
        response.then(handleStateData).catch(handleStateError)
        function handleStateData(response) {
            if (response.success) {
                swal({
                    text: 'Bank details stored successfully.',
                    icon: 'success',
                    button: true,
                    dangerMode: false,
                })
                    .then(() => {
                        window.location.replace(APP_URL + '/')
                    })
                // window.location.replace(APP_URL + '/')
            } else {
                jQuery('#errormessage').html(response.error);
            }
        }
        function handleStateError(error) {
            console.log('error', error)

        }

    }
});

const removedImages = [];
const addImages = [];

jQuery(document).ready(function () {
    jQuery(document).on("click", ".add-more-daterangepicker", function () {
        let nonAvailableDateCounterElem = jQuery('input[name="non_available_date_count"]');
        let nonAvailableDateCounterVal = parseInt(nonAvailableDateCounterElem.val()) + 1;
        nonAvailableDateCounterElem.val(nonAvailableDateCounterVal)
        let cloneDiv = jQuery('.clone-non-available-date-container').first().clone();
        cloneDiv.removeClass('clone-non-available-date-container hidden')
        cloneDiv.find('input').addClass('non-availability')
        jQuery('.append-non-available-dates').append(cloneDiv)
        jQuery(document).find('.non-availability').each(function (index) {
            jQuery(this).attr('name', 'non_availabile_dates[' + index + ']')
        });
    })

    // jQuery(document).on('hover', '.btnaction', function () {
    //     jQuery(this).toggleClass('a-action');
    // });
    $(".btnaction").hover(function () {
        $(this).toggleClass("a-action");
    });

    jQuery(document).on('click', '.remove-daterangepicker', function () {
        let nonAvailableDateCounterElem = jQuery('input[name="non_available_date_count"]');
        let nonAvailableDateCounterVal = parseInt(nonAvailableDateCounterElem.val()) - 1;
        nonAvailableDateCounterElem.val(nonAvailableDateCounterVal)
        jQuery(this).parents('.cp-unavailabilities').remove();
        jQuery(document).find('.non-availability').each(function (index) {
            jQuery(this).attr('name', 'non_availabile_dates[' + index + ']')
        });
    })

    jQuery(document).on("click", ".add-more-location", function () {
        let locationCounterElem = jQuery('input[name="location_count"]');
        let locationCounterVal = parseInt(locationCounterElem.val()) + 1;
        locationCounterElem.val(locationCounterVal)
        let cloneDiv = jQuery('.clone-location-container').clone();
        cloneDiv.removeClass('clone-location-container hidden')
        cloneDiv.find('input.location-value').addClass('location-required')
        cloneDiv.find('input.location-custom').addClass('location-custom-required')
        jQuery('.append-location').append(cloneDiv)

        jQuery(document).find('.location-required').each(function (index) {
            jQuery(this).attr('name', "locations[value][" + index + "]")
        });

        jQuery(document).find('.location-custom-required').each(function (index) {
            jQuery(this).attr('name', "locations[custom][" + index + "]")
        });

        jQuery(document).find('.append-location').find('.location-country').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[country][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-state').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[state][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-city').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[city][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-postal-code').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[postal_code][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-latitude').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[latitude][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-longitude').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[longitude][" + key + "]")
        });
    });

    jQuery(document).on('click', '.remove-location', function () {
        let locationCounterElem = jQuery('input[name="location_count"]');
        let locationCounterVal = parseInt(locationCounterElem.val()) - 1;
        locationCounterElem.val(locationCounterVal)
        jQuery(this).parents('.cp-location').remove();

        jQuery(document).find('.location-required').each(function (index) {
            jQuery(this).attr('name', "locations[value][" + index + "]")
        });

        jQuery(document).find('.location-custom-required').each(function (index) {
            jQuery(this).attr('name', "locations[custom][" + index + "]")
        });

        jQuery(document).find('.append-location').find('.location-country').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[country][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-state').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[state][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-city').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[city][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-postal-code').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[postal_code][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-latitude').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[latitude][" + key + "]")
        });

        jQuery(document).find('.append-location').find('.location-longitude').each(function (index) {
            let key = index + 1;
            jQuery(this).attr('name', "locations[longitude][" + key + "]")
        });
    });



    jQuery(document).on("change", ".product-images", function () {
        let fileName = fileType = checkSize = checkextension = '';
        jQuery(this).siblings('p').find('span.uploaded-file-name').text('');
        jQuery('form#addProduct').find('input#uploadedImage').removeClass('error-messages');
        jQuery('form#addProduct').find('label#uploadedImage-error').remove();
        //console.log(this.files);
        // get the order
        var ImageformData = new FormData();
        for (var i = 0; i < this.files.length; i++) {
            fileName = this.files[i].name;
            // console.log("the file name are :  ",fileName);
            fileType = fileName.substring(fileName.lastIndexOf('.') + 1)
            fileName = fileName.length > fileNameLength ? fileName.substring(0, fileNameLength) + '..' + fileType : fileName;
            //console.log("order",maxProductImageCount,i)
            if (this.files.length > 0 && jQuery.inArray(this.files[i].type, allowedExtension) != -1 && this.files[i].size <= uploadFileSize) {
                let checkSize = checkextension = '';
                let order = i;
                let uploadedImageValue = parseInt(jQuery('#uploadedImageCount').val());
                jQuery('#uploadedImageCount').val(uploadedImageValue + 1);
                if (maxProductImageCount == (uploadedImageValue + 1)) {
                    //addImages.push(this.files[i]);
                    jQuery(this).removeClass('upload-pending');
                    jQuery(this).addClass('hidden');
                    jQuery(this).addClass('upload-done');
                    jQuery('.limit-reached-text').text('Upload file limit reached');
                    var element = '<li><div class="card is-loading d-none"><div class="image"></div></div></li>';
                    jQuery(element).appendTo('ul.product-img-preview');

                    // var reader = new FileReader();
                    // reader.fileName = fileName;
                    // reader.fileSize = ((this.files[i].size) / 1024).toFixed(2) + ' kb';
                    // reader.onload = function (event) {
                    //     var element = '<li><div class="preview-img"><img src="' + event.target.result + '"  alt="img"/></div><div class="img-preview-desc"><p>' + event.target.fileName + '</p><p>' + event.target.fileSize + '</p><span class="remove-product" data-index="' + order + '">Remove</span></div></li>';
                    //     jQuery(element).appendTo('ul.product-img-preview');
                    // }
                    // reader.readAsDataURL(this.files[i]);
                    //return
                }
                if (maxProductImageCount >= (uploadedImageValue + 1)) {
                    // console.log("Below one ");
                    ImageformData.append('files[]', this.files[i]);
                    ImageformData.append('productId', jQuery(this).attr('productId'));
                    var element = '<li><div class="card is-loading d-none"><div class="image"></div></div></li>';
                    jQuery(element).appendTo('ul.product-img-preview');
                    // addImages.push(this.files[i]);
                    // var reader = new FileReader();
                    // reader.fileName = fileName;
                    // reader.fileSize = ((this.files[i].size) / 1024).toFixed(2) + ' kb';
                    // reader.onload = function (event) {
                    //     var element = '<li><div class="preview-img"><img src="' + event.target.result + '"  alt="img"/></div><div class="img-preview-desc"><p>' + event.target.fileName + '</p><p>' + event.target.fileSize + '</p><span class="remove-product" data-index="' + order + '">Remove</span></div></li>';
                    //     jQuery(element).appendTo('ul.product-img-preview');
                    // }
                    // reader.readAsDataURL(this.files[i]);
                }
            } else {
                if (jQuery.inArray(this.files[i].type, allowedExtension) == -1) {
                    checkSize = 'Allowed file extensions: JPG, JPEG, PNG';
                }
                if (this.files[i].size >= uploadFileSize) {
                    checkextension = 'Max file size: 7MB';
                }
                // <label class="invalid-feedback">Please enter a value with a valid mimetype.</label>
                //jQuery(this).siblings('p').find('span.uploaded-file-name').text(fileName);
                //jQuery(this).focusout();
            }

        }

        $('.size-error').text(checkSize);
        $('.extension-error').text(checkextension);

        if (!checkSize && !checkextension) {
            if (getCookie('img_token') == null) {
                setCookie('img_token', btoa(Math.random()), '1');
            }
            $('.card.is-loading').removeClass('d-none');
            getImagesname(image_store_url, ImageformData);
        }
    });

    jQuery(document).on('click', 'span.remove-product', function () {
        var RemoveImageformData = new FormData();
        let index = jQuery(this).attr('data-index');
        RemoveImageformData.append('dataId', jQuery(this).attr('data-id'));
        let uploadedImageValue = parseInt(jQuery('#uploadedImageCount').val());
        jQuery('#uploadedImageCount').val(uploadedImageValue - 1);
        jQuery(this).parents('li').remove();
        jQuery('.product-images').addClass('upload-pending');
        jQuery('.product-images').removeClass('hidden');
        jQuery('.product-images').removeClass('upload-done');
        jQuery('.limit-reached-text').text('Select File to upload...');
        getImagesname(image_store_url, RemoveImageformData,);
    });

    jQuery(document).on('click', '.proclose', function () {

        var RemoveImageformData = new FormData();
        var token = getCookie("img_token");
        let url = APP_URL + "/delete/image/" + token;

        // RemoveImageformData.append('dataId', token);
        ajaxCall(url, 'get');

    });

    jQuery(document).ready(function () {
        var token = getCookie("img_token");
        if (token) {
            let url = APP_URL + "/delete/image/" + token;
            // RemoveImageformData.append('dataId', token);
            ajaxCall(url, 'get');
        }
    });
    // cookie start
    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }
    // cookie end


    async function getImagesname(image_store_url, formData) {
        const response = await fetch(image_store_url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('error');
            })
            .then((responseJson) => {
                if (responseJson.status == true)
                    $('.card.is-loading').addClass('d-none');
                $(".product-img-preview").html(responseJson.html);
                //console.log("responseJson", responseJson);

            })
            .catch((error) => {
                console.log('error1', error);

            });
    }

    jQuery('.amount-limit').keyup(function () {
        let amount = jQuery(this).val();

        if (amount > maxProductRentAmount) {
            amount = maxProductRentAmount
        }

        amount = amount.replace(/\.\.+/g, '.');
        amount = amount.replace(/[^0-9\.]/g, '');
        jQuery(this).val(amount);
    });

    jQuery('#rentalType').on('change', function () {
        if (jQuery(this).val() == 'Day') {
            jQuery(".non-availability").each(function (ele) {
                jQuery(this).removeAttr('value').removeAttr('data-from data-to');
            });
            jQuery('input[name="rent"]').attr('placeholder', rentPerDay.placeholder);
            jQuery('input[name="rent"]').siblings('label').find('small').text(rentPerDay.small);
            dateOptions["timePicker"] = false;
            dateOptions.locale["format"] = dateFormat;
        } else if (jQuery(this).val() == 'Hour') {
            jQuery(".non-availability").each(function (ele) {
                jQuery(this).removeAttr('value').removeAttr('data-from data-to');
            });
            jQuery('input[name="rent"]').attr('placeholder', rentPerHour.placeholder);
            jQuery('input[name="rent"]').siblings('label').find('small').text(rentPerHour.small);
            dateOptions["timePicker"] = true;
            dateOptions["timePickerIncrement"] = calendarTimeGap;
            dateOptions.locale["format"] = dateTimeFormat;
        }
    })

    jQuery(document).on("change", ".get-category, .sub_category, .get_size", function () {
        if ($(this).val()) {
            $.ajax({
                url: APP_URL + '/subcat/' + $(this).val(),
                success: function (result) {
                    // console.log("result", result);
                    console.log(result.status, "checktype")
                    if (result.status == true) {
                        if (result.data || result.type) {
                            if (result.parent == 'main')
                                $("#sub_cat").html(result.data);
                            $("#type").html(result.type);
                            $("#size").html(result.size);
                        } else {
                            if (result.get_other != '1') {
                                $("#size").html(result.size);
                                $("select[name='size']").removeClass('d-none');
                                $("input[name='other_size']").addClass('d-none');
                                // add or remove require class
                                $("select[name='size']").addClass('size-required');
                                $("input[name='other_size']").removeClass('size-required');
                            } else {
                                $("select[name='size']").addClass('d-none');
                                $("input[name='other_size']").removeClass('d-none');
                                // add or remove require class
                                $("select[name='size']").removeClass('size-required');
                                $("input[name='other_size']").addClass('size-required');
                            }
                        }
                    }
                }
            });
        }
    });

    // neighborhood city
    jQuery(document).on('change', '.get-city', function () {
        jQuery('#neighborlist').removeClass('d-none');
        if ($(this).val()) {
            $.ajax({
                url: APP_URL + '/neighborhoodcity/' + $(this).val(),
                success: function (result) {
                    if (result.status == true) {
                        jQuery('#neighborlist').addClass('d-none');
                        if (result.neighborhoodcity) {
                            $("#neighborhood").html(result.neighborhoodcity);
                        }
                    }
                }
            })
        }
    })

    // end

    jQuery(document).on("keyup", "#rentprice", function (e) {
        var amountperday = $('#rentprice').val();
        // console.log(amountperday)
        // if (!$.isNumeric(amountperday)) {
        //     return;
        // }
        //$('#retailvalue').val(parseFloat(amountperday).toFixed(2));
        //$('#rentprice').val(parseFloat(amountperday).toFixed(2));
        // commision, amountperdayc

        if (commision_type == "Percentage") {
            var admincommision = amountperday * commision / 100;
            var lenderrestamount = amountperday - admincommision;

        } else {
            // console.log(typeof (amountperday), typeof (commision))
            if (parseInt(amountperday) <= parseInt(commision)) {
                // jQuery('.error-messages').text("Rentprice should be grater than" + " " + commision)
                jQuery('#saveProduct').addClass('disabled')
            } else {
                // jQuery('.error-messages').text('');
                jQuery('#saveProduct').removeClass('disabled')

            }
            var lenderrestamount = amountperday - commision;
        }
        jQuery('#amount').html("<small>Estimated Earnings/Day: $</small>" + parseFloat(lenderrestamount).toFixed(2));
        if (amountperday == 0) {
            jQuery('#amount').html("");

        }
    });

    // $(document).on("focusout", "input[name='price'],input[name='rent']", function () {
    //     if ($("input[name='price']").val()) {
    //         if (!$.isNumeric($("input[name='price']").val())) {
    //             $("input[name='price']").val('');
    //             return;
    //         }
    //         $("input[name='price']").val(parseFloat($("input[name='price']").val()).toFixed(2));
    //     }

    //     if ($("input[name='rent']").val()) {
    //         if (!$.isNumeric($("input[name='rent']").val())) {
    //             $("input[name='rent']").val('');
    //             jQuery('#amount').html("");
    //             return;
    //         }
    //         $("input[name='rent']").val(parseFloat($("input[name='rent']").val()).toFixed(2));
    //     }

    // });

    // jQuery(document).on("click", 'input[name="thumbnail_image"]', function() {
    //     $('input[name="thumbnail_image"]').attr("checked", false);
    //     $(this).attr("checked", true);
    // });

    jQuery(document).on("change", 'select[name="brand"]', function () {
        //console.log($(this).val());
        if ($(this).val() == '96') {
            $('#other_brand').removeClass('d-none');
        } else {
            $('#other_brand').addClass('d-none');
        }
    });
});