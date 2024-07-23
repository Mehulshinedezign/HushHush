@extends('layouts.front')
@section('content')
    <section class="page-content-sec section-padding">
        <div class="container">
            <div class="page-content-wrapper">
                <div class="row g-3">
                    <div class="col-xl-8 col-lg-12 col-md-12 payment-page">
                        <div class="order-summary cstm-card">
                            <h3>Payment</h3>
                            <p>All transactions are secure and encrypted.</p>
                            <form id="paymentform" action="{{ route('charge') }}" method="POST">
                                @csrf
                                <label for="security">Security</label>
                                <input type="radio" name="security_option" checked
                                    value="{{ $security->value }}">${{ $security->value }}
                                <br>
                                <label for="insurance">Insurance</label>
                                <input type="radio" name="security_option"
                                    value="{{ $insurance->value }}">${{ $insurance->value }}
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Card Number</label>
                                                <div class="form-field" id="cardNumberElement">
                                                    <input type="text" class="form-control" placeholder="Card-number">
                                                    <label for="card-number" class="stripe-error-messages"></label>
                                                </div>
                                                <div class="is-invalid stripe-error" id="cardNumberError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Card Expiration Date</label>
                                                <div class="form-field" id="cardExpiryElement">
                                                    <input type="text" class="form-control" placeholder="MM / YY">
                                                    <label for="card-expiry" class="stripe-error-messages"></label>
                                                </div>
                                                <div class="is-invalid stripe-error" id="cardExpiryError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Card Security Code</label>
                                                <div class="form-field" id="cardCVCElement" class="form-control">
                                                    <input type="password" class="form-control" placeholder="****">
                                                </div>
                                                <div class="is-invalid stripe-error" id="cardCVVError"></div>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Card Holder Name</label>
                                                <div class="form-field">
                                                    <input type="text" name="cardname" id="cardName"
                                                        class="form-control" placeholder="John Doe">
                                                    <label class="cardName-error" for="card-name" id="cardname"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="{{ $price + $security->value }}" id="total_payment">
                                <button type="submit" id="payNow" class="btn full-btn">Pay Now
                                    ${{ $price + $security->value }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        jQuery(document).on("click", 'input[name="security_option"]', function() {
            $('input[name="security_option"]').attr("checked", false);
            $(this).attr("checked", true);
            var amount = parseFloat($("input[name='security_option']:checked").val());
            var price = {{ $price }};
            var totalamount = price + amount;
            $("#payNow").text('Pay Now ' + '$' + totalamount);
            $('#total_payment').val(totalamount);

        });
    </script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}')
        const client_secret = "{{ $intent->client_secret }}"
        const cardButton = document.getElementById('payNow');
        const elements = stripe.elements()

        var style = {
            base: {
                iconColor: '#666EE8',
                color: '#000',
                border: '1px solid #000',
                lineHeight: '40px',
                fontWeight: 300,
                fontFamily: 'Helvetica Neue',
                fontSize: '15px',
                '::placeholder': {
                    color: '#CFD7E0',
                },
                iconStyle: 'solid',
            },
            invalid: {
                color: "#000",
                fontSize: "20px",
            },
        };

        // Customize Stripe Card
        var cardNumberElement = elements.create('cardNumber', {
            style: style,
            showIcon: true,
            placeholder: '1234 1234 1234 1234',
        });
        cardNumberElement.mount('#cardNumberElement');


        var cardExpiryElement = elements.create('cardExpiry', {
            style: style,

        });
        cardExpiryElement.mount('#cardExpiryElement');

        var cardCvcElement = elements.create('cardCvc', {
            style: style,
            showIcon: true,
            placeholder: '123',
        });
        cardCvcElement.mount('#cardCVCElement');

        cardNumberElement.on('change', function(event) {
            if (event.error) {
                jQuery('#cardNumberError').text(event.error.message);
            } else {
                jQuery('#cardNumberError').text('');
            }
        });
        cardExpiryElement.on('change', function(event) {
            if (event.error) {
                jQuery('#cardExpiryError').text(event.error.message);
            } else {
                jQuery('#cardExpiryError').text('');
            }
        });
        cardCvcElement.on('change', function(event) {
            if (event.error) {
                jQuery('#cardCVVError').text(event.error.message);
            } else {
                jQuery('#cardCVVError').text('');
            }
        });


        const form = document.getElementById('paymentform')
        const cardBtn = document.getElementById('payNow')
        const cardHolderName = document.getElementById('cardName')
        const totalPayment = document.getElementById('total_payment').value;


        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            if (!validateTitle()) {
                cardBtn.disabled = false;
                return; // Stop further processing if validation fails
            }
            const {
                setupIntent,
                error
            } = await stripe.confirmCardSetup(
                client_secret, {
                    payment_method: {
                        card: cardNumberElement,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            )
            if (error) {
                cardBtn.disable = false
            } else {
                cardButton.disabled = true;
                cardButton.innerHTML =
                    '<div class="d-flex align-items-center"><span class="spinner-grow spinner-grow-sm mr-2" role="status" aria-hidden="true"></span><p class="mb-0">  Paying...</p></div>'
                jQuery('#cardError').removeClass('is-invalid');
                jQuery('#cardError').html('');
                stripeTokenHandler(setupIntent);
            }
        })

        function stripeTokenHandler(setupIntent) {
            let token = document.createElement('input')
            token.setAttribute('type', 'hidden')
            token.setAttribute('name', 'token')
            token.setAttribute('value', setupIntent.payment_method)
            form.appendChild(token)
            let paymentElement = document.createElement('input')
            paymentElement.setAttribute('type', 'hidden')
            paymentElement.setAttribute('name', 'total_payment')
            paymentElement.setAttribute('value', totalPayment)
            form.appendChild(paymentElement)
            form.submit();
            jQuery('.page-loader').removeClass('d-none'); //for loader
        }

        function validateTitle() {
            var form = $("#paymentform");
            form.validate({
                rules: {
                    cardname: {
                        required: true,
                        // minlength: nameMinLength,
                        // maxlength: nameMaxLength,
                        // regex: nameRegex,
                    },
                },
                messages: {
                    cardname: {
                        required: `{{ __('customvalidation.user.name.required') }}`,
                        // regex: `{{ __('customvalidation.payment.cardholdername.regex') }}`,
                        // minlength: `{{ __('customvalidation.payment.cardholdername.min') }}`,
                        // maxlength: `{{ __('customvalidation.payment.cardholdername.max') }}`,
                    },
                },
                errorClass: "errors",
                success: function(label, element) {
                    $('#title').removeClass('errors');
                },
            });
            if (form.valid() === true) {
                console.log('hlo');
                return true;
            } else {
                return false;
            }
        }
    </script>
@endpush
