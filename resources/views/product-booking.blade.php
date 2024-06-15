<form method="post" action="{{ route('checkout', [$product->id]) }}" id="checkout">
    @csrf
    <div class="book-fixed-section">
        <div class="massage-box">
            <div class="bookitem notifications-heading">
                {{-- <h5>Book an Item</h5> --}}
                <button type="button"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="bookfwrap">
                <div class="pro-tab-image">
                    @if (isset($product->thumbnailImage->url))
                        <img src="{{ $product->thumbnailImage->url }}">
                    @else
                        <img src="{{ asset('front/images/pro1.png') }}">
                    @endif
                </div>
                <div class="ptext-book">
                    <h3>{{ $product->name }}</h3>
                    <p>${{ $product->rent }} <span>/day</span></p>
                </div>
            </div>
            <div class="select_date">
                <div class="form-group">
                    <label>Rental Period</label>
                    <div class="formfield">
                        <input type="text" readonly class="form-control reservation_date" name="reservation_date"
                            placeholder="Reservation Date" onfocus="initDateRangePicker(this, dateOptions)">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20"
                                fill="none">
                                <path
                                    d="M5.64531 1.4418C5.64531 0.956175 5.25164 0.5625 4.76602 0.5625C4.28039 0.5625 3.88672 0.956175 3.88672 1.4418V2.32109C3.88672 2.80672 4.28039 3.20039 4.76602 3.20039C5.25164 3.20039 5.64531 2.80672 5.64531 2.32109V1.4418Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M15.3191 1.4418C15.3191 0.956175 14.9255 0.5625 14.4398 0.5625C13.9542 0.5625 13.5605 0.956175 13.5605 1.4418V2.32109C13.5605 2.80672 13.9542 3.20039 14.4398 3.20039C14.9255 3.20039 15.3191 2.80672 15.3191 2.32109V1.4418Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M0.810547 7.15625V17.2682C0.810547 18.2393 1.59796 19.0268 2.56914 19.0268H16.6379C17.6091 19.0268 18.3965 18.2393 18.3965 17.2682V7.15625H0.810547ZM6.08633 15.9492C6.08633 16.435 5.69284 16.8285 5.20703 16.8285H4.32773C3.84192 16.8285 3.44844 16.435 3.44844 15.9492V15.0699C3.44844 14.5841 3.84192 14.1906 4.32773 14.1906H5.20703C5.69284 14.1906 6.08633 14.5841 6.08633 15.0699V15.9492ZM6.08633 11.1131C6.08633 11.5989 5.69284 11.9924 5.20703 11.9924H4.32773C3.84192 11.9924 3.44844 11.5989 3.44844 11.1131V10.2338C3.44844 9.74798 3.84192 9.35449 4.32773 9.35449H5.20703C5.69284 9.35449 6.08633 9.74798 6.08633 10.2338V11.1131ZM10.9225 15.9492C10.9225 16.435 10.529 16.8285 10.0432 16.8285H9.16387C8.67806 16.8285 8.28457 16.435 8.28457 15.9492V15.0699C8.28457 14.5841 8.67806 14.1906 9.16387 14.1906H10.0432C10.529 14.1906 10.9225 14.5841 10.9225 15.0699V15.9492ZM10.9225 11.1131C10.9225 11.5989 10.529 11.9924 10.0432 11.9924H9.16387C8.67806 11.9924 8.28457 11.5989 8.28457 11.1131V10.2338C8.28457 9.74798 8.67806 9.35449 9.16387 9.35449H10.0432C10.529 9.35449 10.9225 9.74798 10.9225 10.2338V11.1131ZM15.7586 15.9492C15.7586 16.435 15.3651 16.8285 14.8793 16.8285H14C13.5142 16.8285 13.1207 16.435 13.1207 15.9492V15.0699C13.1207 14.5841 13.5142 14.1906 14 14.1906H14.8793C15.3651 14.1906 15.7586 14.5841 15.7586 15.0699V15.9492ZM15.7586 11.1131C15.7586 11.5989 15.3651 11.9924 14.8793 11.9924H14C13.5142 11.9924 13.1207 11.5989 13.1207 11.1131V10.2338C13.1207 9.74798 13.5142 9.35449 14 9.35449H14.8793C15.3651 9.35449 15.7586 9.74798 15.7586 10.2338V11.1131Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M18.3965 6.2793V3.64141C18.3965 2.67022 17.6091 1.88281 16.6379 1.88281H16.1982V2.32246C16.1982 3.29233 15.4095 4.08105 14.4396 4.08105C13.4698 4.08105 12.6811 3.29233 12.6811 2.32246V1.88281H6.52598V2.32246C6.52598 3.29233 5.73725 4.08105 4.76738 4.08105C3.79752 4.08105 3.00879 3.29233 3.00879 2.32246V1.88281H2.56914C1.59796 1.88281 0.810547 2.67022 0.810547 3.64141V6.2793H18.3965Z"
                                    fill="#DEE0E3"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="select_date">
                <div class="form-group">
                    <label>Proposed Pick-Up Time</label>
                    <div class="formfield">
                        <input type="text" class="form-control datetimePicker" name="pickup_datetime" placeholder="">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20"
                                fill="none">
                                <path
                                    d="M5.64531 1.4418C5.64531 0.956175 5.25164 0.5625 4.76602 0.5625C4.28039 0.5625 3.88672 0.956175 3.88672 1.4418V2.32109C3.88672 2.80672 4.28039 3.20039 4.76602 3.20039C5.25164 3.20039 5.64531 2.80672 5.64531 2.32109V1.4418Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M15.3191 1.4418C15.3191 0.956175 14.9255 0.5625 14.4398 0.5625C13.9542 0.5625 13.5605 0.956175 13.5605 1.4418V2.32109C13.5605 2.80672 13.9542 3.20039 14.4398 3.20039C14.9255 3.20039 15.3191 2.80672 15.3191 2.32109V1.4418Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M0.810547 7.15625V17.2682C0.810547 18.2393 1.59796 19.0268 2.56914 19.0268H16.6379C17.6091 19.0268 18.3965 18.2393 18.3965 17.2682V7.15625H0.810547ZM6.08633 15.9492C6.08633 16.435 5.69284 16.8285 5.20703 16.8285H4.32773C3.84192 16.8285 3.44844 16.435 3.44844 15.9492V15.0699C3.44844 14.5841 3.84192 14.1906 4.32773 14.1906H5.20703C5.69284 14.1906 6.08633 14.5841 6.08633 15.0699V15.9492ZM6.08633 11.1131C6.08633 11.5989 5.69284 11.9924 5.20703 11.9924H4.32773C3.84192 11.9924 3.44844 11.5989 3.44844 11.1131V10.2338C3.44844 9.74798 3.84192 9.35449 4.32773 9.35449H5.20703C5.69284 9.35449 6.08633 9.74798 6.08633 10.2338V11.1131ZM10.9225 15.9492C10.9225 16.435 10.529 16.8285 10.0432 16.8285H9.16387C8.67806 16.8285 8.28457 16.435 8.28457 15.9492V15.0699C8.28457 14.5841 8.67806 14.1906 9.16387 14.1906H10.0432C10.529 14.1906 10.9225 14.5841 10.9225 15.0699V15.9492ZM10.9225 11.1131C10.9225 11.5989 10.529 11.9924 10.0432 11.9924H9.16387C8.67806 11.9924 8.28457 11.5989 8.28457 11.1131V10.2338C8.28457 9.74798 8.67806 9.35449 9.16387 9.35449H10.0432C10.529 9.35449 10.9225 9.74798 10.9225 10.2338V11.1131ZM15.7586 15.9492C15.7586 16.435 15.3651 16.8285 14.8793 16.8285H14C13.5142 16.8285 13.1207 16.435 13.1207 15.9492V15.0699C13.1207 14.5841 13.5142 14.1906 14 14.1906H14.8793C15.3651 14.1906 15.7586 14.5841 15.7586 15.0699V15.9492ZM15.7586 11.1131C15.7586 11.5989 15.3651 11.9924 14.8793 11.9924H14C13.5142 11.9924 13.1207 11.5989 13.1207 11.1131V10.2338C13.1207 9.74798 13.5142 9.35449 14 9.35449H14.8793C15.3651 9.35449 15.7586 9.74798 15.7586 10.2338V11.1131Z"
                                    fill="#DEE0E3"></path>
                                <path
                                    d="M18.3965 6.2793V3.64141C18.3965 2.67022 17.6091 1.88281 16.6379 1.88281H16.1982V2.32246C16.1982 3.29233 15.4095 4.08105 14.4396 4.08105C13.4698 4.08105 12.6811 3.29233 12.6811 2.32246V1.88281H6.52598V2.32246C6.52598 3.29233 5.73725 4.08105 4.76738 4.08105C3.79752 4.08105 3.00879 3.29233 3.00879 2.32246V1.88281H2.56914C1.59796 1.88281 0.810547 2.67022 0.810547 3.64141V6.2793H18.3965Z"
                                    fill="#DEE0E3"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            {{-- <div class="select_date mt-0">
                <div class="form-group">
                    <label>Pick up Location</label>
                    <div class="formfield">
                        <p class="px-0">{{ $product->locations[0]->map_address ?? '' }}</p>
                    </div>
                </div>
            </div> --}}
            <div class="select_date mt-0">
                <div class="form-group">
                    <label></label>
                    <div class="formfield">
                        <p class="px-0">Please select one of the following to protect your rental:</p>
                    </div>
                </div>
            </div>
            <div class="book_checkbox radio_holder">
                <p class="mb-3">
                    <input type="radio" id="checkbox_pickup" name="security_option" value="{{ $security }}"
                        checked data="Security">
                    <label for="checkbox_pickup" class="mb-0">${{ $security }} Security
                        <span>(refundable)</span></label>
                </p>
                <p>
                    <input type="radio" id="checkbox_delivery" name="security_option" value="{{ $insurance }}"
                        data="Insurance">
                    <label for="checkbox_delivery" class="mb-0">${{ $insurance }} Insurance
                        <span>(non-refundable)</span></label>
                </p>
            </div>
            <hr>
            <div class="price_book">
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="amount_days">Subtotal</span>
                    <p class="mb-0 total_amount">${{ $product->rent }}</p>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="set_security_text">Security Deposit</span>
                    <p class="mb-0 set_security_amount">${{ $security }}</p>
                </div>
                {{-- <div class="d-flex align-items-center justify-content-between mt-2">
                    <span>Commission Fee</span>
                    <p class="mb-0 trans_fee">$0</p>
                </div> --}}
                <hr>
                <div class="totlprice">
                    <div class="d-flex align-items-end justify-content-between">
                        <h3>Amount</h3>
                        <div class="price_book text-end">
                            {{-- <span class="tax">all taxes included</span> --}}
                            <p class="final-p text-end total_pay_amount">
                                ${{ number_format($security + $product->rent, 2) }}</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="option" value="security">
                <input type="hidden" name="days" value="">
                <input type="hidden" name="latitude" value="{{ $product->locations[0]->latitude }}" />
                <input type="hidden" name="longitude" value="{{ $product->locations[0]->longitude }}" />
                <input type="hidden" name="product_id" value="{{ jsencode_userdata($product->id) }}" />
                <input type="hidden" name="customer_location" value="{{ $product->locations[0]->id }}" />
                <button type="submit" class="btn btn_black ch_out">Next&nbsp;&nbsp;<i
                        class="fa-solid fa-circle-notch fa-spin show-loader" style="display:none;"></i></button>
            </div>
        </div>
    </div>
</form>
