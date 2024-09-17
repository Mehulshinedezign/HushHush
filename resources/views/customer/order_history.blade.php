@extends('layouts.front')
@section('title', 'My Orders')
@section('content')
    <section class="rental-request-bx min-height-100">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>My Order History</h2>
                </div>
                <div class="rental-request-tb mb-4">
                    <div class="order-his-tab-head">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(request()->tab !='cancelled' && request()->tab !='dispute') active @endif" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true" data-tab="active">
                                    <div class="rental-history-badge">
                                        <div class="rental-badge-dot"></div>
                                        <p>Active</p>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-complete" type="button" role="tab"
                                    aria-controls="pills-complete" aria-selected="false" data-tab="completed">
                                    <div class="rental-history-badge active">
                                        <div class="rental-badge-dot"></div>
                                        <p>Completed</p>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(request()->tab=='cancelled') active @endif" id="pills-canceled-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-canceled" type="button" role="tab"
                                    aria-controls="pills-canceled" aria-selected="false" data-tab="cancelled">
                                    <div class="rental-history-badge danger">
                                        <div class="rental-badge-dot"></div>
                                        <p>Canceled</p>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(request()->tab=='dispute')active @endif" id="pills-dispute-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-dispute" type="button" role="tab"
                                    aria-controls="pills-dispute" aria-selected="false" data-tab="dispute">
                                    <div class="rental-history-badge warning">
                                        <div class="rental-badge-dot"></div>
                                        <p>Dispute</p>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if(request()->tab !='cancelled' && request()->tab !='dispute') show active @endif" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            @include('customer.active-orders')
                        </div>
                        <div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab"
                            tabindex="0">
                            @include('customer.complete-orders')
                        </div>
                        <div class="tab-pane fade @if(request()->tab=='cancelled') show active @endif" id="pills-canceled" role="tabpanel"
                            aria-labelledby="pills-canceled-tab" tabindex="0">
                            @include('customer.cancel-orders')
                        </div>
                        <div class="tab-pane fade @if(request()->tab=='dispute') show active @endif" id="pills-dispute" role="tabpanel"
                            aria-labelledby="pills-dispute-tab" tabindex="0">
                            @include('customer.dispute-order')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- @includeFirst(['validation']) --}}
    @includeFirst(['validation.js_cancel_order'])
    @includeFirst(['validation.js_dispute_order'])
    @includeFirst(['validation.js_product_review'])

    <script>
        $(document).ready(function() {
    // Check URL for 'tab' parameter and activate the corresponding tab
    var urlParams = new URLSearchParams(window.location.search);
    var selectedTab = urlParams.get('tab');

    // Function to trigger tab click for proper data load
    function triggerTab(tabId) {
        $(tabId).trigger('click');
    }

    if (selectedTab) {
        // Remove the 'active' class from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');

        // Activate the corresponding tab based on 'tab' parameter
        if (selectedTab === 'cancelled') {
            $('#pills-canceled-tab').addClass('active');
            $('#pills-canceled').addClass('show active');
            triggerTab('#pills-canceled-tab');
        } else if (selectedTab === 'completed') {
            $('#pills-complete-tab').addClass('active');
            $('#pills-complete').addClass('show active');
            triggerTab('#pills-complete-tab');
        } else if (selectedTab === 'dispute') {
            $('#pills-dispute-tab').addClass('active');
            $('#pills-dispute').addClass('show active');
            triggerTab('#pills-dispute-tab');
        } else {
            // Default to 'active' tab
            $('#pills-home-tab').addClass('active');
            $('#pills-home').addClass('show active');
            triggerTab('#pills-home-tab');
        }
    } else {
        // Default to the first tab if no 'tab' parameter is present
        $('#pills-home-tab').addClass('active');
        $('#pills-home').addClass('show active');
    }

    // Update URL when tab is clicked to persist state
    $('.nav-link').click(function() {
        var tabName = $(this).data('tab');
        var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
        window.history.pushState({ path: newUrl }, '', newUrl);
    });
});


    </script>
@endpush
