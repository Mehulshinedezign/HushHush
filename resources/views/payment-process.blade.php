<form class="payment-detail" id="paymentDetail" autocomplete="off" action="{{ route('charge') }}" method="post">
    @csrf
    <div class="massage-box">
        <div class="paymentitem notifications-heading">
            {{-- <h5>Choose Payment Method</h5> --}}
            <button type="button"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="select-method">
            <label for="">Select added payment method</label>
            <ul>
                @foreach ($cardlist as $list)
                    <li>
                        <p class="radio_holder">
                            <input type="radio" name="selected_payment"
                                class="form-check-input selected-payment @if (jsdecode_userdata($list->card_token) != 'other_card') list @endif"
                                @if ($list->is_default == 1) checked @endif value="{{ $list->card_token }}"
                                id="card{{ $list->id }}">
                            <label class="form-check-label"
                                for="card{{ $list->id }}">************{{ jsdecode_userdata($list->last_digits) }}</label>
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="default-wrap">
            <p class="radio_holder">
                <input type="radio" name="selected_payment" class="form-check-input selected-payment"
                    value="other_card" id="card-form">
                <label class="form-check-label" for="card-form"> Use other payment method</label>
            </p>
        </div>
        <div class="my-payment-form d-none ">
            {{-- <h4>CREDIT/ DEBIT CARD</h4> --}}
            {{-- <p>Fill your card details below</p> --}}
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                    <label>Card Number</label>
                    <div class="formfield">
                        <div id="cardNumberElement" class="form-control input-bg"></div>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="16" viewBox="0 0 21 16"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.595703 2.38703V13.0762C0.595703 13.608 0.80686 14.1176 1.18296 14.4931C1.55845 14.8692 2.06817 15.0804 2.59992 15.0804H18.6336C19.1654 15.0804 19.6751 14.8692 20.0506 14.4931C20.4267 14.1176 20.6378 13.6079 20.6378 13.0762V2.38703C20.6378 1.85524 20.4267 1.34556 20.0506 0.970067C19.6751 0.593977 19.1654 0.382812 18.6336 0.382812H2.59992C2.06813 0.382812 1.55845 0.593969 1.18296 0.970067C0.806867 1.34556 0.595703 1.85528 0.595703 2.38703ZM19.3017 7.06353V13.0762C19.3017 13.2532 19.2316 13.4236 19.1059 13.5484C18.9809 13.674 18.8106 13.7442 18.6336 13.7442H2.59992C2.42291 13.7442 2.25246 13.6742 2.12765 13.5484C2.00209 13.4235 1.93185 13.2532 1.93185 13.0762V7.06353H19.3017ZM19.3017 3.0551H1.93185V2.38703C1.93185 2.21002 2.00193 2.03957 2.12764 1.91476C2.25261 1.78919 2.42291 1.71896 2.59991 1.71896H18.6336C18.8106 1.71896 18.9811 1.78905 19.1059 1.91476C19.2315 2.03972 19.3017 2.21002 19.3017 2.38703L19.3017 3.0551Z"
                                    fill="#DEE0E3"></path>
                            </svg>
                        </span>
                    </div>
                    <label for="card-number" class="stripe-error-messages"></label>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                    <label>Expiry Date</label>
                    <div class="formfield">
                        <div id="cardExpiryElement" class="form-control input-bg"></div>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                fill="none">
                                <path
                                    d="M14.5634 2.4375H13.2442V2.81287C13.2442 3.22536 13.0242 3.60653 12.667 3.81277C12.3096 4.01901 11.8695 4.01901 11.5123 3.81277C11.1551 3.60653 10.9351 3.22536 10.9351 2.81287V2.43769L5.494 2.4375V2.81287C5.494 3.22536 5.27403 3.60653 4.91683 3.81277C4.55943 4.01901 4.11932 4.01901 3.7621 3.81277C3.4049 3.60653 3.18494 3.22536 3.18494 2.81287V2.43769L1.81664 2.4375C1.4403 2.43692 1.07905 2.58576 0.812683 2.85173C0.546328 3.1175 0.396524 3.47857 0.396524 3.85489V5.98517H15.9836V3.85489C15.9836 3.47855 15.8338 3.1175 15.5674 2.85173C15.3011 2.58576 14.9397 2.43692 14.5634 2.4375ZM0.396484 7.1397V15.2855C0.396484 15.6619 0.546284 16.0227 0.812644 16.2887C1.079 16.5545 1.44028 16.7035 1.8166 16.7027H14.5634C14.9397 16.7035 15.3009 16.5545 15.5673 16.2887C15.8337 16.0227 15.9835 15.6619 15.9835 15.2855V7.1397H0.396484Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M12.6586 1.02658V2.92875C12.6586 3.24749 12.4 3.50592 12.0813 3.50592C11.7623 3.50592 11.5039 3.24748 11.5039 2.92875V1.02658C11.5039 0.707652 11.7623 0.449219 12.0813 0.449219C12.4 0.449219 12.6586 0.707652 12.6586 1.02658Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M4.87739 1.02658V2.92875C4.87739 3.24749 4.61876 3.50592 4.30002 3.50592C3.98109 3.50592 3.72266 3.24748 3.72266 2.92875V1.02658C3.72266 0.707652 3.98109 0.449219 4.30002 0.449219C4.61876 0.449219 4.87739 0.707652 4.87739 1.02658Z"
                                    fill="#DEE0E3"></path>
                            </svg>
                        </span>
                    </div>
                    <label for="card-expiry" class="stripe-error-messages"></label>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                    <label>CVC</label>
                    <div class="formfield">
                        <div id="cardCVCElement" class="form-control input-bg"></div>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                fill="none">
                                <path
                                    d="M14.5634 2.4375H13.2442V2.81287C13.2442 3.22536 13.0242 3.60653 12.667 3.81277C12.3096 4.01901 11.8695 4.01901 11.5123 3.81277C11.1551 3.60653 10.9351 3.22536 10.9351 2.81287V2.43769L5.494 2.4375V2.81287C5.494 3.22536 5.27403 3.60653 4.91683 3.81277C4.55943 4.01901 4.11932 4.01901 3.7621 3.81277C3.4049 3.60653 3.18494 3.22536 3.18494 2.81287V2.43769L1.81664 2.4375C1.4403 2.43692 1.07905 2.58576 0.812683 2.85173C0.546328 3.1175 0.396524 3.47857 0.396524 3.85489V5.98517H15.9836V3.85489C15.9836 3.47855 15.8338 3.1175 15.5674 2.85173C15.3011 2.58576 14.9397 2.43692 14.5634 2.4375ZM0.396484 7.1397V15.2855C0.396484 15.6619 0.546284 16.0227 0.812644 16.2887C1.079 16.5545 1.44028 16.7035 1.8166 16.7027H14.5634C14.9397 16.7035 15.3009 16.5545 15.5673 16.2887C15.8337 16.0227 15.9835 15.6619 15.9835 15.2855V7.1397H0.396484Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M12.6586 1.02658V2.92875C12.6586 3.24749 12.4 3.50592 12.0813 3.50592C11.7623 3.50592 11.5039 3.24748 11.5039 2.92875V1.02658C11.5039 0.707652 11.7623 0.449219 12.0813 0.449219C12.4 0.449219 12.6586 0.707652 12.6586 1.02658Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M4.87739 1.02658V2.92875C4.87739 3.24749 4.61876 3.50592 4.30002 3.50592C3.98109 3.50592 3.72266 3.24748 3.72266 2.92875V1.02658C3.72266 0.707652 3.98109 0.449219 4.30002 0.449219C4.61876 0.449219 4.87739 0.707652 4.87739 1.02658Z"
                                    fill="#DEE0E3"></path>
                            </svg>
                        </span>
                    </div>
                    <label for="card-cvc" class="stripe-error-messages"></label>
                </div>
                {{--  --}}
                <div class="checkbox_cstm">
                    <input class="styled-checkbox" name="savecard" id="svecard" type="checkbox" value="savecard">
                    <label for="svecard">Save your credit card details for faster checkout</label>
                </div>
                <div id="card-errors" role="alert" class="text-danger"></div>
            </div>
        </div>
        <hr>
        <div class="price_book">
            <div class="d-flex align-items-center justify-content-between">
                <span class="set_security_text">Security</span>
                <p class="mb-0 set_security_amount">${{ $billingDetail->security_option_amount }}</p>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="amount_days">Subtotal ({{ $meta['rental_days'] }} days)</span>
                <p class="mb-0 total_amount">${{ $billingDetail->rent * $meta['rental_days'] }}</p>
            </div>
            {{-- <div class="d-flex align-items-center justify-content-between mt-2">
                <span>Commission Fee</span>
                <p class="mb-0 trans_fee">${{ $billingDetail->customer_transaction_fee_amount }}</p>
            </div> --}}
            <hr>
            <div class="totlprice">
                <div class="d-flex align-items-end justify-content-between">
                    <h3>Amount</h3>
                    <?php $total_pay_amount = $billingDetail->security_option_amount + $billingDetail->rent * $meta['rental_days'] + $billingDetail->customer_transaction_fee_amount; ?>
                    <div class="price_book text-end">
                        {{-- <span class="tax">all taxes included</span> --}}
                        <p class="final-p text-end total_pay_amount">${{ $total_pay_amount }}</p>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn_black btn-loader" id="payNow">Send Rental Request <div
                    class="spinner-loader" id="spinner-loader"><span></span></div></button>
        </div>
    </div>
    <p style="height: 12px">You will not be charged until your rental request is accepted.</p>
</form>
<script>
    const meta = {!! json_encode($meta) !!};
</script>
