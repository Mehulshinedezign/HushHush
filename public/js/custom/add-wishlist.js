var currentUrl = new URL(window.location.href);

function addToWishlist(element, productId) {
    // Check if the user is logged in (userId should be defined elsewhere in your code)
    if (!userId || userId === '' || userId === null || userId === false) {
        // Redirect guest user to the login page
        window.location.replace(login_url);
        return;  // Prevent further execution
    }

    // Proceed if the product ID is valid
    if (jQuery.isNumeric(productId) && productId > 0) {
        jQuery.ajax({
            url: url,  // Assuming 'url' is defined elsewhere for the wishlist action
            method: 'post',
            data: {
                productid: productId,
                _token: csrf_token  // Include CSRF token if needed
            },
            success: function(response) {
                // Toggle the active state of the wishlist icon
                jQuery(element).find('span').toggleClass('active');
                let iconElement = jQuery(element).find('i');

                if (response.action === 'added') {
                    // Change to solid heart icon when added to wishlist
                    iconElement.removeClass('fa-regular').addClass('fa-solid');
                    iziToast.success({
                        title: "",
                        message: response.message,
                        class: 'alert-msg',
                        position: 'topRight'
                    });
                } else if (response.action === 'removed') {
                    // Change back to regular heart icon when removed from wishlist
                    iconElement.removeClass('fa-solid').addClass('fa-regular');

                    var currentRoute = APP_URL + '/my-wishlist';
                    // If the user is on the wishlist page, remove the item from the page
                    if (window.location.href === currentRoute) {
                        iconElement.closest('.product-card').remove();  // Remove product card from wishlist view

                        // If the wishlist is now empty, reload the page to show the empty state
                        if ($(".home-product-box").children().length === 0) {
                            window.location.reload();
                        } else {
                            iziToast.success({
                                title: "",
                                message: response.message,
                                class: 'alert-msg',
                                position: 'topRight'
                            });
                        }
                    }
                }
            },
            error: function(errors) {
                // Handle any errors from the server
                let result = errors.responseJSON;
                let message = result.message || 'Something went wrong!';
                iziToast.error({
                    title: errorTitle,
                    message: message,
                    position: 'topRight'
                });
            }
        });
    }
}
