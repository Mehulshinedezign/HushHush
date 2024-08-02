<div class="order-his-card-box">
    <div class="row g-3">
        @foreach ($orders as $order)
            @if ($order->status == 'Waiting')
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <img src="{{ $order->product->thumbnailImage->file_path }}" alt="profile">
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
</div>
