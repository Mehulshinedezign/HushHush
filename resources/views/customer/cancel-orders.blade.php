{{-- @if (count($orders) > 0)
    @foreach ($orders as $order)
        @if ($order->status == 'Cancelled')
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
@if (check_order_list_paginate('Cancelled') > 10)
    <div class="custom-pagination">
        {{ $orders->links('pagination::product-list') }}
    </div>
@endif --}}
<div class="row g-3">
    @php $empty = true; @endphp

    @foreach ($orders as $order)
        @if ($order->status == 'Cancelled')
        @php $empty = false; @endphp

            <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="order-his-card">
                    <div class="order-card-top">
                        <div class="order-card-img">
                            <a href="{{ route('vieworder', ['order' => $order->id]) }}">
                                <img src="{{ $order->product->thumbnailImage->file_path ?? 'N/A' }}" alt="profile">
                            </a>
                        </div>
                       <div class="name-with-status">
                            <p>{{ $order->product->name }}</p>
                            <p class="cancelled-txt">Cancelled</p>
                       </div>
                        <div class="pro-desc-prize">
                            <h3>${{ $order->total }}</h3>
                            <div class="badge day-badge">
                                Per day
                            </div>

                        </div>
                        <div class="order-pro-details">
                            <div class="order-details-list">
                                <p>Cancelation Note:</p>
                                <h4>{{ $order->cancellation_note ??''}}</h4>
                            </div>
                            {{-- <div class="order-details-list">
                                <p>Category :</p>
                                <h4>{{ $order->product->category->name }}</h4>
                            </div> --}}
                            {{-- <div class="order-details-list">
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
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @if($empty)
    <div class="list-empty-box">
        <img src="{{ asset('front/images/Empty 1.svg') }}" alt="No orders available">
        <h3 class="text-center">No orders Available</h3>
    </div>
    @endif

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
