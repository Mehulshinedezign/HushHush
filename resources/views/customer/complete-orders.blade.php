{{-- @if (count($orders) > 0)
    @foreach ($orders as $order)
        @if ($order->status == 'Completed')
            @include('customer.order-row')
        @endif
    @endforeach
@else
    <div class="pro-tab-contant">
        <div class="pro-tab-contant-row">
            <h3></h3>
        </div>
    </div>
@endif
@if (check_order_list_paginate('Completed') > 10)
    <div class="custom-pagination">
        {{ $orders->links('pagination::product-list') }}
    </div>
@endif --}}
<div class="order-his-card-box">
    <div class="row g-3">
        @foreach ($orders as $order)
            @if ($order->status == 'Completed')
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <img src="{{ $order->product->thumbnailImage->file_path }}" alt="profile">
                            </div>
                            <p>{{ $order->product->name }}</p>
                            <div class="pro-desc-prize">
                                <h3>${{ $order->product->rent_day }}</h3>
                                <div class="badge day-badge">
                                    Per day
                                </div>

                            </div>
                            <div class="order-pro-details">
                                <div class="order-details-list">
                                    <p>Category :</p>
                                    <h4>{{ $order->product->category->name }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Size:</p>
                                    <h4>{{ $order->product->size }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Rental date:</p>
                                    <h4>{{ $order->from_date }} to {{ $order->to_date }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Lender:</p>
                                    <h4>{{ $order->product->retailer->name }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Payment:</p>
                                    <h4>Paid</h4>
                                </div>
                            </div>
                        </div>
                        <div class="order-card-footer">
                            <a href="#" class="button outline-btn full-btn" data-bs-toggle="modal"
                                data-bs-target="#write-review-Modal">Write
                                Review</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
    {{-- <div class="pagination-main">
        <a href="javascript:void(0)" class="pagination-box">
            01
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            02
        </a>
        <a href="javascript:void(0)" class="pagination-box active">
            03
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            04
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            05
        </a>
    </div> --}}
</div>
