@extends('layouts.customer')

@section('title', 'Payment')

@section('content')
    <section class="payment-section">
        <div class="container">
           <x-alert/>
            <div class="custom-columns">
                <div class="left-side-content pl-0">
                    <form class="payment-detail" id="paymentDetail" autocomplete="off" action="{{ route('charge') }}" method="post">
                        @csrf
                        <div class="payment-form mb-4">
                            <h5 class="green-text w-500 m-0">Billing Details</h5>
                            <p>Let’s start off with the basics...</p>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.name') }}</label>
                                    <input type="text" placeholder="{{ __('user.placeholders.name') }}" name="name" id="name" class="form-control" value="{{ old('name', @$customerBillingDetail->name) }}">
                                    @error('name')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.email') }}</label>
                                    <input type="email" placeholder="{{ __('user.placeholders.email') }}" name="email" id="email" class="form-control" value="{{ old('email', @$customerBillingDetail->email) }}">
                                    @error('email')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.phone') }}</label>
                                    <input type="text" placeholder="{{ __('user.placeholders.phone') }}" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', @$customerBillingDetail->phone_number) }}">
                                    @error('phone_number')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.address1') }}</label>
                                    <input type="text" placeholder="{{ __('user.placeholders.address1') }}" name="address1" id="address1" class="form-control" value="{{ old('address1', @$customerBillingDetail->address1) }}">
                                    @error('address1')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.address2') }}</label>
                                    <input type="text" placeholder="{{ __('user.placeholders.address2') }}" name="address2" id="address2" class="form-control" value="{{ old('address2', @$customerBillingDetail->address2) }}">
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.country') }}</label>
                                    <select name="country" id="country" class="form-control select" autocomplete="off">
                                        <option value="">{{ __('user.placeholders.country') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"  data-iso="{{ $country->iso_code }}" @if($country->id == old('country', @$customerBillingDetail->country_id)) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.state') }}</label>
                                    <select name="state" id="state" class="form-control select" autocomplete="off">
                                        <option value="">{{ __('user.placeholders.state') }}</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" @if($state->id == $customerBillingDetail->state_id) selected @endif>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.city') }}</label>
                                    <select name="city" id="city" class="form-control select" autocomplete="off">
                                        <option value="">{{ __('user.placeholders.city') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" @if($city->id == $customerBillingDetail->city_id) selected @endif>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>{{ __('user.fields.zip') }}</label>
                                    <input type="text" placeholder="{{ __('user.placeholders.zip') }}" name="postcode" id="postcode" class="form-control" value="{{ old('postcode', @$customerBillingDetail->postcode) }}">
                                    @error('postcode')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror 
                                </div>
                            </div>
                        </div>
                        <div class="card-form">
                            <h5 class="green-text w-500 m-0">Payment Details</h5>
                            <p>Fill your card details below</p>                       
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                    <label>Card Number</label>
                                    <div id="cardNumberElement" class="form-control input-bg card"></div>
                                    <label for="card-number" class="stripe-error-messages"></label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>Expiry Date</label>
                                    <div id="cardExpiryElement" class="form-control input-bg expiry"></div>
                                    <label for="card-expiry" class="stripe-error-messages"></label>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                    <label>CVC</label>
                                    <div id="cardCVCElement" class="form-control input-bg cvv"></div>
                                    <label for="card-cvc" class="stripe-error-messages"></label>
                                </div>
                                <div id="card-errors" role="alert" class="text-danger"></div>
                            </div>
                            <button type="submit" class="btn blue-btn large-btn fullwidth" id="payNow">Pay Now </button>
                            <div class="loader d-none" id="loader">
                                <img src="{{ asset('img/spinner.svg') }}"> 
                            </div>
                        </div>
                    </form>
                </div>
                <div class="right-side-content pr-0">
                    <h4 class="w-400">Summary</h4>
                    <p class="largeFont w-500 black-text">Rental Details</p>
                    <ul>
                        <li class="summary-item">
                            <span class="field">Location</span>
                            <span class="value grey-text">{{ $billingDetail->map_location }}</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">Reservation Date</span>
                            <span class="value grey-text">{{ $meta['reservation_date'] }}</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">Rental Days</span>
                            <span class="value grey-text">{{ $meta['rental_days'] }} Days</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">Rental Amount</span>
                            <span class="value grey-text">${{ $meta['rental_amount'] }}</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">{{ $meta['security_option'] }}</span>
                            <span class="value grey-text">${{ $meta['security_option_value'] }}</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">Shipping</span>
                            <span class="value grey-text">Self Pickup</span>
                        </li>
                        <li class="summary-item">
                            <span class="field">Transaction Fee</span>
                            <span class="value grey-text">${{ $meta['transactionFee'] }}</span>
                        </li>
                        <li class="summary-item summary-total">
                            <h6 class="field">Total</h6>
                            <h6 class="value grey-text">${{ $meta['total'] }}</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
    
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/custom/profile.js') }}"></script>
    <script>
        const meta = {!! json_encode($meta) !!};
        const validator = jQuery('form#paymentDetail').validate({
            errorClass : "error-messages",
            rules: {
                name: {
                    required: true,
                    maxlength: 50
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
                address1: {
                    required: true,
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
            },
            messages: {
                name: {
                    required: '{{ __("user.validations.nameRequired") }}',
                    maxlength: '{{ __("user.validations.nameMax") }}',
                },
                email: {
                    required: '{{ __("user.validations.emailRequired") }}',
                    email: '{{ __("user.validations.emailType") }}',
                    regex: '{{ __("user.validations.emailType") }}',
                },
                phone_number: {
                    required: '{{ __("user.validations.phoneRequired") }}',
                    regex: "Invalid phone number"
                },
                address1: {
                    required: '{{ __("user.validations.address1Required") }}',
                },
                country: {
                    required: '{{ __("user.validations.countryRequired") }}',
                },
                state: {
                    required: '{{ __("user.validations.stateRequired") }}',
                },
                city: {
                    required: '{{ __("user.validations.cityRequired") }}',
                },
                postcode: {
                    required: '{{ __("user.validations.zipRequired") }}',
                },
            }
        });

        // Stripe Section
        var stripe = Stripe("{{ env('STRIPE_KEY') }}");
        var elements = stripe.elements();
        var style = {
            base: {
                fontSize: '16px',
            },
        };

        // Customize Stripe Card
        var cardNumberElement = elements.create('cardNumber', {style});
        cardNumberElement.mount('#cardNumberElement');

        var cardExpiryElement = elements.create('cardExpiry', {style});
        cardExpiryElement.mount('#cardExpiryElement');

        var cardCvcElement = elements.create('cardCvc', {style});
        cardCvcElement.mount('#cardCVCElement');

        // End of Stripe Card Customization
        cardNumberElement.on('change', function(event) {
            if (event.error) {
                jQuery('label[for="card-number"]').text(event.error.message);
            } else {
                jQuery('label[for="card-number"]').text('');
            }
        });

        cardExpiryElement.on('change', function(event) {
            if (event.error) {
                jQuery('label[for="card-expiry"]').text(event.error.message);
            } else {
                jQuery('label[for="card-expiry"]').text('');
            }
        });

        cardCvcElement.on('change', function(event) {
            if (event.error) {
                jQuery('label[for="card-cvc"]').text(event.error.message);
            } else {
                jQuery('label[for="card-cvc"]').text('');
            }
        });
        
        // Custom styling can be passed to options when creating an Element.
        var form = document.getElementById('paymentDetail');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            // Validate billing details
            if (jQuery("form#paymentDetail").valid()) {
                var buttonText = jQuery('#payNow').text();
                jQuery('#payNow').prop('disabled', true);
                jQuery('#payNow').html(loaderIcon);
                
                var billingDetails = {
                    owner: {
                        name: jQuery('#name').val(),
                        address: {
                            line1: jQuery('#address1').val(),
                            line2: jQuery('#address2').val(),
                            country: jQuery('#country option:selected').attr('data-iso'),
                            state: jQuery('#state option:selected').text(),
                            city: jQuery('#city option:selected').text(),
                            postal_code: jQuery('#postcode').val(),
                        },
                        email: jQuery('#email').val(),
                        phone: jQuery('#phone_number').val()
                    }
                };

                stripe.createSource(cardNumberElement, billingDetails).then(function(result) {
                    if (result.error) {
                        jQuery('#payNow').prop('disabled', false);
                        jQuery('#payNow').html(buttonText);

                        // Inform the user if there was an error
                        var errorElement = document.getElementById('card-errors');
                        if(
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
                        stripeSourceHandler(result.source);
                    }
                });
            } else {
                validator.focusInvalid();
            }           
        }); 

        function stripeSourceHandler(source) {
            // Insert the source ID into the form so it gets submitted to the server
            // var form = document.getElementById('paymentDetail');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeSource');
            hiddenInput.setAttribute('value', source.id);
            form.appendChild(hiddenInput);

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
    </script>
@endsection