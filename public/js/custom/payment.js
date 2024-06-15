// Stripe Section
var elements = stripe.elements();
var style = {
    base: {
        fontSize: '16px',
    },
};

// Customize Stripe Card
var cardNumberElement = elements.create('cardNumber', { style });
cardNumberElement.mount('#cardNumberElement');

var cardExpiryElement = elements.create('cardExpiry', { style });
cardExpiryElement.mount('#cardExpiryElement');

var cardCvcElement = elements.create('cardCvc', { style });
cardCvcElement.mount('#cardCVCElement');

// End of Stripe Card Customization
cardNumberElement.on('change', function (event) {
    if (event.error) {
        jQuery('label[for="card-number"]').text(event.error.message);
    } else {
        jQuery('label[for="card-number"]').text('');
    }
});

cardExpiryElement.on('change', function (event) {
    if (event.error) {
        jQuery('label[for="card-expiry"]').text(event.error.message);
    } else {
        jQuery('label[for="card-expiry"]').text('');
    }
});

cardCvcElement.on('change', function (event) {
    if (event.error) {
        jQuery('label[for="card-cvc"]').text(event.error.message);
    } else {
        jQuery('label[for="card-cvc"]').text('');
    }
});

// Custom styling can be passed to options when creating an Element.
// jQuery('.my-payment-form').addClass('d-none');
jQuery('.selected-payment').on('click', function () {
    var checkdata = jQuery(this).val();
});

var check = jQuery('.selected-payment').hasClass('list');
if (!check) {
    console.log('Please check');
    jQuery('#svecard').attr('checked', true);
    jQuery('#card-form').prop('checked', true);
    jQuery('.my-payment-form').removeClass('d-none');
    jQuery('#label').addClass('d-none');
}

jQuery('.selected-payment').on('click', function () {
    if (jQuery(this).val() == "other_card") {
        jQuery('.my-payment-form').removeClass('d-none');
        // jQuery('#svecard').addClass('checked', true);
        jQuery('#svecard').attr('checked', true);
    } else {
        jQuery('#label').removeClass('d-none');
        // jQuery('#svecard').removeClass('checked');
        jQuery('#svecard').removeAttr('checked', false);
        jQuery('.my-payment-form').addClass('d-none');
    }
});

var form = document.getElementById('paymentDetail');
form.addEventListener('submit', function (event) {
    event.preventDefault();
    if ($("input[name='selected_payment']:checked").val() == 'other_card') {
        // Validate billing details
        jQuery('#payNow').prop('disabled', true);
        jQuery('#spinner-loader').show();

        stripe.confirmCardSetup(intent, {
            payment_method: {
                card: cardNumberElement,
                billing_details: { name: Auth }
            }
        }).then(function (result) {
            if (result.error) {
                jQuery('#payNow').prop('disabled', false);
                jQuery('#spinner-loader').hide();

                // Inform the user if there was an error
                var errorElement = document.getElementById('card-errors');
                if (
                    result.error.code != "incomplete_number" &&
                    result.error.code != "invalid_number" &&
                    result.error.code != "incomplete_expiry" &&
                    result.error.code != "invalid_expiry_year_past" &&
                    result.error.code != "incomplete_cvc"
                ) {
                    errorElement.textContent = result.error.message;
                }
            } else {
                // Send the source to your server
                stripeSourceHandler(result.setupIntent.payment_method, 0);
            }
        });
    } else {
        jQuery('#payNow').prop('disabled', true);
        jQuery('#spinner-loader').show();
        stripeSourceHandler($("input[name='selected_payment']:checked").val(), 1);
    }
});

function stripeSourceHandler(payment_method, get_card_type) {
    // Insert the source ID into the form so it gets submitted to the server
    // var form = document.getElementById('paymentDetail');
    var hiddenInput = document.createElement('input');
    var hiddenInputcard_type = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'payment_method');
    hiddenInput.setAttribute('value', payment_method);
    hiddenInputcard_type.setAttribute('type', 'hidden');
    hiddenInputcard_type.setAttribute('name', 'get_card_type');
    hiddenInputcard_type.setAttribute('value', get_card_type);
    form.appendChild(hiddenInput);
    form.appendChild(hiddenInputcard_type);

    for (const key in meta) {
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', key);
        hiddenInput.setAttribute('value', meta[key]);
        form.appendChild(hiddenInput);
    }

    // Submit the form
    form.submit();
}
// End of Stripe Section