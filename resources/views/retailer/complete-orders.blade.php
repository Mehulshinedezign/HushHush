{{-- <div class="b_req p-0">
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
                    @if ($orderItem->order->status == 'Completed')
                        @include('retailer.order-row')
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @if (check_order_list_paginate_retailer('Completed') > 10)
        {{ $orderItems->links('pagination::product-list') }}
    @endif
</div> --}}


<div class="order-his-card-box">
    <div class="row g-3">
        @forelse ($orders as $order)
            @if ($order->status == 'Completed')
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('retailervieworder', ['order' => $order->id]) }}">
                                    <img src="{{ $order->product->thumbnailImage->file_path ?? 'N/A' }}" alt="profile">
                                </a>
                            </div>
                            <p>{{ $order->product->name }}</p>
                            <div class="pro-desc-prize">
                                <h3>${{ $order->total }}</h3>
                                {{-- <div class="badge day-badge">
                                    Per day
                                </div> --}}

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
                        @if ($order->product->user_id != auth()->id())
                            <div class="order-card-footer">
                                <a href="#" class="button outline-btn full-btn" data-bs-toggle="modal"
                                    data-bs-target="#write-review-Modal">Write
                                    Review</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <div>No data found</div>
        @endforelse

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
