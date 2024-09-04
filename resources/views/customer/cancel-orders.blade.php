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
                                <p>Order Id :</p>
                                <h4>{{ $order->id }}</h4>
                            </div>
                            
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
