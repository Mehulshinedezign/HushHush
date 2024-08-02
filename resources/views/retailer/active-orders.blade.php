{{-- <div class="order-management">
    <div class="table_left">
        <div class="wrapper_table">
            <table class="rwd-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price per Day</th>
                        <th>Total</th>
                        <th>Rental Period</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $index => $orderItem)
                        @if ($orderItem->order->status == 'Pending' || $orderItem->order->status == 'Picked Up')
                            @include('retailer.order-row')
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (check_order_list_paginate_retailer('Pending') > 10)
            {{ $orderItems->links('pagination::product-list') }}
        @endif
    </div>
</div> --}}


<div class="order-his-card-box">
    <div class="row g-3">
        @foreach ($orders as $order)
            @if ($order->status == 'Waiting' || ($order->status = 'Picked Up'))
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('retailervieworder', ['order' => $order->id]) }}">
                                    <img src="{{ $order->product->thumbnailImage->file_path }}" alt="profile">
                                </a>
                            </div>
                            <p>{{ $order->product->name }}</p>
                            <div class="pro-desc-prize">
                                <h3>${{ $order->product->rent_day }}</h3>

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
                                data-bs-target="#cancel-order-Modal">Cancel
                                order</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
