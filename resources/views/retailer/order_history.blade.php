@extends('layouts.front')
@section('title', 'My Orders')
@section('content')
    <section class="rental-request-bx">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>Receive Order History</h2>
                </div>

                <div class="rental-request-tb mb-4">
                    <div class="order-his-tab-head">
                        <ul class="nav nav-pills " id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">
                                    <div class="rental-history-badge ">
                                        <div class="rental-badge-dot"></div>
                                        <p>Active</p>
                                    </div>

                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-complete" type="button" role="tab"
                                    aria-controls="pills-complete" aria-selected="false">
                                    <div class="rental-history-badge active">
                                        <div class="rental-badge-dot"></div>
                                        <p>Completed</p>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="pills-canceled-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-canceled" type="button" role="tab"
                                    aria-controls="pills-canceled" aria-selected="false">
                                    <div class="rental-history-badge danger">
                                        <div class="rental-badge-dot"></div>
                                        <p>Canceled</p>
                                    </div>
                                </button>
                            </li>
                        </ul>
                        {{-- <div class="form-group m-0">
                            <div class="formfield">
                                <select name="" id="" class="form">
                                    <option value="">Past 2 Months</option>
                                    <option value="">Past 5 Months</option>
                                    <option value="">Past 1 Year</option>
                                </select>
                                <span class="form-icon">
                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}" alt="dorpdown" width="13">
                                </span>
                            </div>
                        </div> --}}
                    </div>


                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            @include('retailer.active-orders')
                        </div>
                        <div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab"
                            tabindex="0">
                            @include('retailer.complete-orders')

                        </div>
                        <div class="tab-pane fade  " id="pills-canceled" role="tabpanel"
                            aria-labelledby="pills-canceled-tab" tabindex="0">
                            @include('retailer.cancel-orders')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- @includeFirst(['validation'])
    @includeFirst(['validation.js_cancel_order'])
    @includeFirst(['validation.js_product_review']) --}}
@endpush
