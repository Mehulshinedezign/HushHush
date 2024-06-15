var currentUrl = new URL(window.location.href);
function addToWishlist(element, productId) {
    if(userId == '' || userId == null || userId == false) {
        window.location.replace(login_url);
    }
    if (jQuery.isNumeric(productId) && productId > 0) {
        jQuery.ajax({
            url: url,
            method: 'post',
            data: {
                productid: productId,
            },
            success: function(response) {
                jQuery(element).find('span').toggleClass('active');
                let iconElement = jQuery(element).find('span').find('i');
                // if (response.action == 'added') {
                //     iconElement.removeClass('far');
                //     iconElement.addClass('fas');
                // } else {
                //     iconElement.removeClass('fas');
                //     iconElement.addClass('far');
                // }
                
                // iziToast.success({
                //     title: "",
                //     message: response.message,
                //     class: 'alert-msg',
                //     position: 'topRight'
                // });
            },
            error: function(errors) {
                let result = errors.responseJSON;
                let message = result.message
                iziToast.error({
                    title: errorTitle,
                    message: message,
                    position: 'topRight'
                });
            }
        })
    }
}

function removeFromWishlist(element, productId) {
    if(userId == '' || userId == null || userId == false) {
        window.location.replace(login_url);
    }
    if (jQuery.isNumeric(productId) && productId > 0) {
        jQuery.ajax({
            url: url,
            method: 'post',
            data: {
                productid: productId,
            },
            success: function(response) {
                currentUrl.searchParams.delete("page");
                window.location.replace(currentUrl.href)
            },
            error: function(errors) {
                let result = errors.responseJSON;
                let message = result.message
                iziToast.error({
                    title: errorTitle,
                    message: message,
                    position: 'topRight'
                });
            }
        })
    }
}

