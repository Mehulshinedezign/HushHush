const cardButton = document.getElementById('payButton');
const cardHolderName = document.getElementById('cardHolderName');
const form = document.getElementById('addCardForm');
const stripe = Stripe(stripeKey);
const selements = stripe.elements();

var cardNumberElement = selements.create('cardNumber');
cardNumberElement.mount('#cardNumberElement');

var cardExpiryDate = selements.create('cardExpiry');
cardExpiryDate.mount('#cardExpiryElement');

var cardCvcElement = selements.create('cardCvc');
cardCvcElement.mount('#cardCVCElement');

cardNumberElement.on('change', function (event) {
    if (event.error) {
        const spanElement = `<span class="invalid-feedback" role="alert">
                                <strong>${event.error.message}</strong>
                            </span>`;
        jQuery('#cardNumberElement').parent().addClass('is-invalid');
        jQuery(spanElement).insertAfter(jQuery('#cardNumberElement').parent());
    } else {
        jQuery('#cardNumberElement').parent().removeClass('is-invalid');
        jQuery('#cardNumberElement').parent().next('span.invalid-feedback').remove();
    }
});

cardExpiryDate.on('change', function (event) {
    if (event.error) {
        const spanElement = `<span class="invalid-feedback" role="alert">
                                <strong>${event.error.message}</strong>
                            </span>`;
        jQuery('#cardExpiryElement').parent().addClass('is-invalid');
        jQuery(spanElement).insertAfter(jQuery('#cardExpiryElement').parent());
    } else {
        jQuery('#cardExpiryElement').parent().removeClass('is-invalid');
        jQuery('#cardExpiryElement').parent().next('span.invalid-feedback').remove();
    }
});

cardCvcElement.on('change', function (event) {
    if (event.error) {
        const spanElement = `<span class="invalid-feedback" role="alert">
                                <strong>${event.error.message}</strong>
                            </span>`;
        jQuery('#cardCVCElement').parent().addClass('is-invalid');
        jQuery(spanElement).insertAfter(jQuery('#cardCVCElement').parent());
    } else {
        jQuery('#cardCVCElement').parent().removeClass('is-invalid');
        jQuery('#cardCVCElement').parent().next('span.invalid-feedback').remove();
    }
});

const rules = {
    card_holder_name: {
        required: true,
    }
};

const messages = {
    card_holder_name: {
        required: `{{__('messages.buyBackupPlanForm.card_holder_name.required') }}`,
    }
};

jQuery(window).on("load", function () {
    cardButton.removeAttribute('disabled');
    handleValidation('addCardForm', rules, messages);
});

form.addEventListener('submit', async function (event) {
    event.preventDefault();
    cardButton.setAttribute('disabled', true);
    if (jQuery("#addCardForm").valid()) {
        const {
            setupIntent,
            error
        } = await stripe.confirmCardSetup(
            clientSecret, {
            payment_method: {
                card: cardNumberElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
        }
        );

        if (error) {
            cardButton.removeAttribute('disabled');
            jQuery('span.card-errors').text(error.message);
            jQuery('span.card-errors').parent().addClass('show');
            jQuery('#cardErrors').removeClass('d-none');
        } else {
            cardButton.innerHTML = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Adding...';
            jQuery("body").addClass("loading");
            jQuery('span.card-errors').text('');
            jQuery('span.card-errors').parent().removeClass('show');
            jQuery('#cardErrors').addClass('d-none');
            stripeTokenHandler(setupIntent);
        }

        return;
    }

    cardButton.removeAttribute('disabled');
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'payment_method');
    hiddenInput.setAttribute('value', token.payment_method);
    form.appendChild(hiddenInput);
    // Submit the form
    form.submit();
}


// card profile

jQuery(document).ready(function () {
    jQuery('.delete-card').click(function () {
        var thisRef = this;
        if (jQuery(this).hasClass("default")) {
            swal({
                title: 'Info',
                text: 'Please select or add another card as default card',
                icon: 'warning',
                button: "Ok",
                dangerMode: false,
            });
        } else {
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you cannot get it back',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        const cardId = jQuery(this).attr("data-value");
                        jQuery.ajax({
                            type: "POST",
                            url: deleteCardRoute,
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                card_id: cardId
                            },
                            beforeSend: function () {
                                jQuery('.page-loader').removeClass('d-none');
                            },
                            success: function (result) {
                                if ('success' == result['status']) {
                                    jQuery(thisRef).parents('tr').remove();
                                    swal(result['message'], {
                                        icon: 'success',
                                    });
                                } else {
                                    swal(result['message'], {
                                        icon: 'error',
                                    });
                                }
                            },
                            error: function (error) {
                                swal(error.responseJSON.message, {
                                    icon: 'error',
                                });
                            },
                            complete: function () {
                                jQuery('.page-loader').addClass('d-none');
                            }
                        });
                    } else {
                        swal('Your card is safe!');
                    }
                });
        }
    })

    jQuery('input.user-card').change(function () {
        swal({
            title: 'Are you sure?',
            text: 'To set this card as default card',
            icon: 'info',
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    const cardId = jQuery(this).val();
                    jQuery.ajax({
                        type: "POST",
                        url: defaultCardRoute,
                        data: {
                            // _token: csrf,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            card_id: cardId
                        },
                        beforeSend: function () {
                            jQuery('.page-loader').removeClass('d-none');
                        },
                        success: function (result) {
                            if ('success' == result['status']) {
                                defaultCardId = cardId;
                                jQuery(`input.user-card`).removeClass('default');
                                jQuery(`#card${defaultCardId}`).addClass('default');
                                // swal(result['message'], {
                                //     icon: 'success',
                                //     buttons: false,
                                // });
                                setTimeout(function () {
                                    // location.reload();
                                }, 1500);
                            } else {
                                swal(result['message'], {
                                    icon: 'error',
                                });
                                jQuery(`input.user-card`).prop('checked', false);
                                jQuery(`#card${defaultCardId}`).prop('checked', true);
                            }
                        },
                        error: function (error) {
                            jQuery(`input.user-card`).prop('checked', false);
                            jQuery(`#card${defaultCardId}`).prop('checked', true);
                            swal(error.responseJSON.message, {
                                icon: 'error',
                            });
                        },
                        complete: function () {
                            jQuery('.page-loader').addClass('d-none');
                        },
                    });

                } else {
                    jQuery(`input.user-card`).prop('checked', false);
                    jQuery(`#card${defaultCardId}`).prop('checked', true);
                    swal('Your card is safe!');
                }
            })
    });

    jQuery('.admin-add-card').click(function () {
        jQuery('.admin-add-card-container').removeClass('d-none');
        jQuery('.user-payment-detail-content').addClass('d-none');
    });

    jQuery('.cancel-admin-add-card').click(function () {
        jQuery('.admin-add-card-container').addClass('d-none');
        jQuery('.user-payment-detail-content').removeClass('d-none');
    });
})
