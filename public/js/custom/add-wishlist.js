var currentUrl = new URL(window.location.href);
function addToWishlist(element, productId) {
    if (userId == '' || userId == null || userId == false) {
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
                let iconElement = jQuery(element).find('i');
                if (response.action === 'added') {
                    iconElement.removeClass('fa-regular').addClass('fa-solid');
                    iziToast.success({
                        title: "",
                        message: response.message,
                        class: 'alert-msg',
                        position: 'topRight'
                    });
                } else {
                    iconElement.removeClass('fa-solid').addClass('fa-regular');
                    var currentRoute = APP_URL + '/my-wishlist';
                    if(window.location.href == currentRoute)
                    {

                        iconElement.parent('div').parent('div').parent('div').remove();
                        console.log();
                        if($(".home-product-box").children().length){
                            iziToast.success({
                                title: "",
                                message: response.message,
                                class: 'alert-msg',
                                position: 'topRight'
                            });

                        }else{
                            window.location.reload();

                        }
                    }

                }
            },
            error: function(errors) {
                let result = errors.responseJSON;
                let message = result.message;
                iziToast.error({
                    title: errorTitle,
                    message: message,
                    position: 'topRight'
                });
            }
        });
    }
}
