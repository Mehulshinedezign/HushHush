@extends('layouts.front')
@section('title', 'My Orders')
@section('content')
    <section>
        {{-- @dd($orders); --}}
        <div class="payment-transaction-history-section order-history-section section-space">
            <div class="container">
                <div class="payment-transaction-history-title">
                    <h4>Outbound Requests</h4>
                </div>
                {{-- <x-alert /> --}}
                <div class="order-history-tab">
                    <div class="history-tab-select-option mb-2 pb-2">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link order-list active" id="active-tab" data-bs-toggle="tab" data="active"
                                    data-bs-target="#active" type="button" role="tab" aria-controls="active"
                                    aria-selected="true"><i class="fa-solid fa-circle"></i>Active</button>
                                <button class="nav-link order-list" id="completed-tab" data-bs-toggle="tab"
                                    data-bs-target="#completed" type="button" role="tab" aria-controls="completed"
                                    aria-selected="false"><i class="fa-solid fa-circle"></i>Completed</button>
                                <button class="nav-link order-list" id="canceled-tab" data-bs-toggle="tab"
                                    data-bs-target="#canceled" type="button" role="tab" aria-controls="canceled"
                                    aria-selected="false"><i class="fa-solid fa-circle"></i>Cancelled</button>
                            </div>
                        </nav>
                        {{-- <div class="select-option">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Past 2 Months</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div> --}}
                        <form action="{{ route('orders') }}" method="post">
                            {{-- <div class="formfield"> --}}
                            @csrf
                            <div class="d-flex">
                                <div class="formfield">
                                    <input type="text" readonly class="form-control reservation_date"
                                        name="reservation_date" placeholder="Rental Period"
                                        onfocus="initDateRangePicker(this)">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20"
                                            viewBox="0 0 19 20" fill="none">
                                            <path
                                                d="M5.64531 1.4418C5.64531 0.956175 5.25164 0.5625 4.76602 0.5625C4.28039 0.5625 3.88672 0.956175 3.88672 1.4418V2.32109C3.88672 2.80672 4.28039 3.20039 4.76602 3.20039C5.25164 3.20039 5.64531 2.80672 5.64531 2.32109V1.4418Z"
                                                fill="#DEE0E3" />
                                            <path
                                                d="M15.3191 1.4418C15.3191 0.956175 14.9255 0.5625 14.4398 0.5625C13.9542 0.5625 13.5605 0.956175 13.5605 1.4418V2.32109C13.5605 2.80672 13.9542 3.20039 14.4398 3.20039C14.9255 3.20039 15.3191 2.80672 15.3191 2.32109V1.4418Z"
                                                fill="#DEE0E3" />
                                            <path
                                                d="M0.810547 7.15625V17.2682C0.810547 18.2393 1.59796 19.0268 2.56914 19.0268H16.6379C17.6091 19.0268 18.3965 18.2393 18.3965 17.2682V7.15625H0.810547ZM6.08633 15.9492C6.08633 16.435 5.69284 16.8285 5.20703 16.8285H4.32773C3.84192 16.8285 3.44844 16.435 3.44844 15.9492V15.0699C3.44844 14.5841 3.84192 14.1906 4.32773 14.1906H5.20703C5.69284 14.1906 6.08633 14.5841 6.08633 15.0699V15.9492ZM6.08633 11.1131C6.08633 11.5989 5.69284 11.9924 5.20703 11.9924H4.32773C3.84192 11.9924 3.44844 11.5989 3.44844 11.1131V10.2338C3.44844 9.74798 3.84192 9.35449 4.32773 9.35449H5.20703C5.69284 9.35449 6.08633 9.74798 6.08633 10.2338V11.1131ZM10.9225 15.9492C10.9225 16.435 10.529 16.8285 10.0432 16.8285H9.16387C8.67806 16.8285 8.28457 16.435 8.28457 15.9492V15.0699C8.28457 14.5841 8.67806 14.1906 9.16387 14.1906H10.0432C10.529 14.1906 10.9225 14.5841 10.9225 15.0699V15.9492ZM10.9225 11.1131C10.9225 11.5989 10.529 11.9924 10.0432 11.9924H9.16387C8.67806 11.9924 8.28457 11.5989 8.28457 11.1131V10.2338C8.28457 9.74798 8.67806 9.35449 9.16387 9.35449H10.0432C10.529 9.35449 10.9225 9.74798 10.9225 10.2338V11.1131ZM15.7586 15.9492C15.7586 16.435 15.3651 16.8285 14.8793 16.8285H14C13.5142 16.8285 13.1207 16.435 13.1207 15.9492V15.0699C13.1207 14.5841 13.5142 14.1906 14 14.1906H14.8793C15.3651 14.1906 15.7586 14.5841 15.7586 15.0699V15.9492ZM15.7586 11.1131C15.7586 11.5989 15.3651 11.9924 14.8793 11.9924H14C13.5142 11.9924 13.1207 11.5989 13.1207 11.1131V10.2338C13.1207 9.74798 13.5142 9.35449 14 9.35449H14.8793C15.3651 9.35449 15.7586 9.74798 15.7586 10.2338V11.1131Z"
                                                fill="#DEE0E3" />
                                            <path
                                                d="M18.3965 6.2793V3.64141C18.3965 2.67022 17.6091 1.88281 16.6379 1.88281H16.1982V2.32246C16.1982 3.29233 15.4095 4.08105 14.4396 4.08105C13.4698 4.08105 12.6811 3.29233 12.6811 2.32246V1.88281H6.52598V2.32246C6.52598 3.29233 5.73725 4.08105 4.76738 4.08105C3.79752 4.08105 3.00879 3.29233 3.00879 2.32246V1.88281H2.56914C1.59796 1.88281 0.810547 2.67022 0.810547 3.64141V6.2793H18.3965Z"
                                                fill="#DEE0E3" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="dark btn-holder">
                                    <button type="submit" class="primary-btn">Apply</button>
                                </div>
                            </div>
                            {{-- </div> --}}
                        </form>

                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade orders active show" id="active" role="tabpanel"
                            aria-labelledby="active-tab">
                            @include('customer.active-orders')
                        </div>
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            @include('customer.complete-orders')
                        </div>
                        <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                            @include('customer.cancel-orders')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="cancellation-note" tabindex="-1" aria-labelledby="cancellation-noteLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="ajax-response"></div>
                            <form method="post" id="cancel-order">
                                @csrf
                                <div class="cancellation-popup-sec">
                                    <div class="popup-head">
                                        <h6>Cancellation Note</h6>
                                        <button type="button" class="close" data-bs-dismiss="modal"><i
                                                class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <textarea class="form-control" name="cancellation_note" rows="5"
                                        placeholder="Please write cancellation note here"></textarea>
                                    <button type="submit" class="primary-btn width-full submit">Submit&nbsp;<i
                                            class="fa-solid fa-circle-notch fa-spin show-loader"
                                            style="display:none;"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Modal -->
            @include('review-modal')
        </div>
    </section>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_cancel_order'])
    @includeFirst(['validation.js_product_review'])
@endpush
