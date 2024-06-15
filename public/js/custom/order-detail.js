jQuery(document).ready(function () {
    const removedImages = [];
    console.log('here');
    jQuery("button.upload-img").click(function () {
        // get the form id
        let form = jQuery(this).parents('form').attr('id');
        if (jQuery('form#' + form).valid()) {
            jQuery(this).prop('disabled', true);
            jQuery(this).html(loaderIcon);
            jQuery("form#" + form).submit();
        }
    });

    // picked up and returned image validation 
    const validateRules = {
        uploaded_image: {
            required: function () {
                let uploadedImageLength = jQuery('ul.product-img-preview').children().length;
                if (uploadedImageLength < orderMinImageCount) {
                    return true;
                }
            }
        }
    };
    const validateMessages = {
        uploaded_image: {
            required: 'Please upload atleast ' + orderMinImageCount + ' images.',
        },
    };

    for (let i = 1; i <= orderMaxImageCount; i++) {
        validateRules['image' + i] = {
            accept: allowedExtensionMessage,
            filesize: uploadFileSize
        };

        validateMessages['image' + i] = {
            accept: 'Please upload only ' + allowedExtensionMessage + '.',
            filesize: 'File size should not be more than ' + uploadFileSizeInMb + '.',
        };
    }
    jQuery('form#imageForm').validate({
        ignore: [],
        errorClass: "error-messages",
        rules: validateRules,
        messages: validateMessages,
        errorPlacement: function (label, element) {
            if (element.attr('type') == 'file') {
                label.insertAfter(jQuery(element).parent())
            } else {
                label.insertAfter(element)
            }
        }
    });
    // end of picked up and returned images

    jQuery(document).on("click", '.upload_image', function () {
        jQuery('.order-detail-photo').removeClass('d-none');

        // jQuery('.upload_image').addClass('disabled');
    });
    jQuery(document).on("click", '.accept_order', function () {
        jQuery(this).addClass('disabled');

        var biling_token_id = jQuery('.accept_order').attr('billing_token')
        // console.log('vgervevervrever', biling_token_id);
        var data = { payment_intent_id: biling_token_id }
        jQuery('#accept_order').show();

        var url = APP_URL + '/' + 'payment' + '/' + 'approve'
        console.log(url)
        var response = ajaxCall(url, 'post', data);
        response.then(handleStateData).catch(handleStateError)
        function handleStateData(response) {
            jQuery('#accept_order').hide();
            window.location.href = response.url;
        }
        function handleStateError(error) {
            console.log('error', error)
        }

    });

    jQuery(document).on('click', '.reject_order', function () {
        jQuery(this).addClass('disabled');

        var biling_token_id = jQuery('.reject_order').attr('billing_token')
        var data = { payment_intent_id: biling_token_id }
        $("#reject_order").show();

        // $(".loader-show").show();
        var url = APP_URL + '/' + 'reject' + '/' + 'order'
        console.log(biling_token_id, data, url)
        var response = ajaxCall(url, 'post', data)
        response.then(handleStateData).catch(handleStateError)
        function handleStateData(response) {
            // $(".loader-show").hide();
            $("#reject_order").hide();

            window.location.replace(APP_URL + '/' + 'retailer/order')

        }
        function handleStateError(error) {
            console.log('error', error)
        }
    });
    // form validation of dispute order
    const disputeRules = {
        dispute_uploaded_image: {
            required: function () {
                let uploadedImageLength = jQuery('ul.dispute-img-preview').children().length;
                if (uploadedImageLength < minDisputeImageCount) {
                    return true;
                }
            }
        }
    };
    const diputeMessages = {
        dispute_uploaded_image: {
            required: 'Please upload atleast ' + minDisputeImageCount + ' images.',
        },
    };

    for (let i = 1; i <= maxDisputeImageCount; i++) {
        disputeRules['dispute_image' + i] = {
            accept: allowedExtensionMessage,
            filesize: uploadFileSize
        };

        diputeMessages['dispute_image' + i] = {
            accept: 'Please upload only ' + allowedExtensionMessage + '.',
            filesize: 'File size should not be more than ' + uploadFileSizeInMb + '.',
        };
    }

    jQuery('form#disputeForm').validate({
        ignore: [],
        errorClass: "error-messages",
        rules: disputeRules,
        messages: diputeMessages,
        errorPlacement: function (label, element) {
            if (element.attr('type') == 'file') {
                label.insertAfter(jQuery(element).parent())
            } else {
                label.insertAfter(element)
            }
        }
    });
    // end of form validation of dispute order

    // preview image of picked up and returned
    jQuery('.customerImages').change(function () {
        var fileName = fileType = '';
        jQuery(this).siblings('p').find('span.uploaded-file-name').text('');
        jQuery('form#imageForm').find('label.error-messages').remove();
        jQuery('form#imageForm').find('input.error-messages.hidden').val(null);
        jQuery('form#imageForm').find('input.error-messages.hidden').removeClass('error-messages');
        if (this.files.length) {
            fileName = this.files[0].name;
            fileType = fileName.substring(fileName.lastIndexOf('.') + 1)
            fileName = fileName.length > fileNameLength ? fileName.substring(0, fileNameLength) + '..' + fileType : fileName;
        }

        // get the order
        if (this.files.length > 0 && jQuery.inArray(this.files[0].type, allowedExtension) != -1 && this.files[0].size <= uploadFileSize) {
            let order = parseInt(jQuery(this).attr('data-order'));
            var reader = new FileReader();
            reader.fileName = fileName;
            reader.fileSize = ((this.files[0].size) / 1024).toFixed(2) + ' kb';
            reader.onload = function (event) {
                var element = '<li><span class="remove-preview-img" data-index="' + order + '"><i class="fas fa-times"></i></span><div class="product-image-box"><img src="' + event.target.result + '"  alt="img"/></div><div class="img-preview-desc"><p>' + event.target.fileName + '</p><p>' + event.target.fileSize + '</p></div></li>';
                jQuery(element).prependTo('ul.product-img-preview');
            }
            reader.readAsDataURL(this.files[0]);
            let uploadedImageValue = parseInt(jQuery('#uploadedImageCount').val());
            jQuery('#uploadedImageCount').val(uploadedImageValue + 1);
            jQuery(this).addClass('hidden');
            jQuery(this).removeClass('upload-pending');
            jQuery(this).addClass('upload-done');
            let previewImageLength = jQuery(document).find('ul.product-img-preview').children().length;
            if (previewImageLength == (orderMaxImageCount - 1)) {
                jQuery('.customerImages').removeClass('hidden');
                jQuery('.customerImages').addClass('hidden');
                jQuery('.limit-reached-text').text('Upload file limit reached');
            } else {
                jQuery(".customerImages").each(function (index, element) {
                    if (jQuery(element).hasClass('upload-pending')) {
                        jQuery(element).removeClass('hidden');

                        return false;
                    }
                });
            }
        } else {
            jQuery(this).siblings('p').find('span.uploaded-file-name').text(fileName);
            jQuery(this).focusout();
        }
    });
    // end of preview image

    // remove pickedup and returned image
    jQuery(document).on('click', 'span.remove-preview-img', function () {
        let index = jQuery(this).attr('data-index');
        let imageId = jQuery(this).attr('data-id');
        if (jQuery.isNumeric(imageId) && imageId > 0) {
            removedImages.push(imageId);
            jQuery('#removedImages').val(removedImages.join(','));
        }
        let uploadedImageValue = parseInt(jQuery('#uploadedImageCount').val());
        jQuery('#uploadedImageCount').val(uploadedImageValue - 1);
        jQuery(this).parent().remove();
        jQuery('.customerImages').removeClass('hidden');
        jQuery('.customerImages').addClass('hidden');
        jQuery('#image' + index + '-error').remove();
        if (jQuery(document).find('ul.product-img-preview').children().length == 0) {
            jQuery('input[name="image1"]').removeClass('hidden');
            jQuery('form#imageForm').trigger("reset");
        } else {
            jQuery('input[name="image' + (index) + '"]').removeClass('hidden');
        }
        jQuery('input[name="image' + (index) + '"]').val(null);
        jQuery('input[name="image' + (index) + '"]').removeClass('upload-done');
        jQuery('input[name="image' + (index) + '"]').addClass("upload-pending");
        jQuery('.limit-reached-text').text('Select File to upload...');
        jQuery('button.upload-img').removeClass('d-none');
    });

    // Dispute image section
    jQuery('.disputeImages').change(function () {
        var fileName = fileType = '';
        jQuery(this).siblings('p').find('span.dispute-uploaded-file-name').text('');
        jQuery('label#disputeUploadedImage-error').remove();
        if (this.files.length) {
            fileName = this.files[0].name;
            fileType = fileName.substring(fileName.lastIndexOf('.') + 1)
            fileName = fileName.length > fileNameLength ? fileName.substring(0, fileNameLength) + '..' + fileType : fileName;
        }
        // get the order
        if (this.files.length > 0 && jQuery.inArray(this.files[0].type, allowedExtension) != -1 && this.files[0].size <= uploadFileSize) {
            let order = parseInt(jQuery(this).attr('data-order'));
            var reader = new FileReader();
            reader.fileName = fileName;
            reader.fileSize = ((this.files[0].size) / 1024).toFixed(2) + ' kb';
            reader.onload = function (event) {
                var element = '<li><span class="dispute-remove-preview-img" data-index="' + order + '"><i class="fas fa-times"></i></span><div class="product-image-box"><img src="' + event.target.result + '"  alt="img"/></div><div class="img-preview-desc"><p>' + event.target.fileName + '</p><p>' + event.target.fileSize + '</p></div></li>';
                jQuery(element).prependTo('ul.dispute-img-preview');
            }
            reader.readAsDataURL(this.files[0]);
            jQuery(this).addClass('hidden');
            let previewImageLength = jQuery(document).find('ul.dispute-img-preview').children().length;
            if (previewImageLength == (maxDisputeImageCount - 1)) {
                jQuery('.disputeImages').removeClass('hidden');
                jQuery('.disputeImages').addClass('hidden');
                jQuery('.dispute-limit-reached-text').text('Upload file limit reached');
            } else {
                jQuery('input[name="dispute_image' + (order + 1) + '"]').removeClass('hidden');
            }
        } else {
            jQuery(this).siblings('p').find('span.dispute-uploaded-file-name').text(fileName);
            jQuery(this).focusout();
        }
    })

    jQuery(document).on('click', 'span.dispute-remove-preview-img', function () {
        let index = jQuery(this).attr('data-index');
        jQuery(this).parent().remove();
        jQuery('.disputeImages').removeClass('hidden');
        jQuery('.disputeImages').addClass('hidden');
        jQuery('#dispute_image' + index + '-error').remove();
        jQuery('#dispute_image' + index + '-error').removeClass('error');
        jQuery('#dispute_image' + index + '-error').removeAttr('aria-invalid');
        if (jQuery(document).find('ul.dispute-img-preview').children().length == 0) {
            jQuery('input[name="dispute_image1"]').removeClass('hidden');
        } else {
            jQuery('input[name="dispute_image' + (index) + '"]').removeClass('hidden');
        }
        jQuery('input[name="dispute_image' + (index) + '"]').removeAttr("value");
        jQuery('.dispute-limit-reached-text').text('Select File to upload...');
    })
    // end of dispute

    // review form validation
    jQuery('form#reviewForm').validate({
        ignore: [],
        errorClass: "error-messages",
        rules: {
            rating: {
                required: true,
                regex: /^[1-5]{1}$/,
            },
            review: {
                required: true,
                maxlength: 1000,
            }
        },
        messages: {
            rating: {
                required: 'Please give rating.',
                regex: 'Rating must be between 1 to 5.',
            },
            review: {
                required: 'Please write a review.',
                maxlength: 'Review should not be more than 1000 characters.'
            }
        },
        errorPlacement: function (label, element) {
            if (element.attr('type') == 'file') {
                label.insertAfter(jQuery(element).parent())
            } else if (element.attr('type') == 'radio') {
                label.insertAfter(jQuery(element).parent())
            } else {
                label.insertAfter(element)
            }
        }
    });

    jQuery('.edit-review').click(function () {
        jQuery('form#reviewForm').toggleClass('d-none');
        jQuery('.given-review').toggleClass('d-none');
    })
});