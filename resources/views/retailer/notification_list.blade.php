@extends('layouts.retailer')

@section('title', 'Notifications')

@section('content')
    <section class="right-content innerpages all-notifi-sec">
        <div class="innerbox-container">
            <div class="heading-outerbox d-block">
                <h5 class="order-heading mb-0">All Notifications</h5>
            </div>
            <hr class="border-0 mb-35">
            <div class="ven-all-notification-section">
                @foreach ($allNotifications as $notification)
                    <div class="recent-notificstionbox ven-all-notifi">
                        <figure>
                            @if (isset($notification->order->item->product->thumbnailImage->url))
                                <img src="{{ @$notification->order->item->product->thumbnailImage->url }}" alt="product-img"
                                    class="img-fluid">
                            @else
                                <img src="{{ asset('img/default-product.png') }}" alt="product-img" class="img-fluid">
                            @endif
                        </figure>
                        <div class="recent-notifi-content">
                            <h5 class="largeFont m-0">Order #{{ $notification->order_id }}</h5>
                            <p>{{ $notification->message }}</p>
                        </div>
                        <span
                            class="timestatus smallFont">{{ str_replace(['hours from now', 'hour from now', 'hours', 'hour', 'minutes'], ['hrs ago', 'hr ago', 'hrs', 'hr', 'min'], $notification->created_at->diffForHumans()) }}</span>
                    </div>
                @endforeach

                {{ $allNotifications->links('pagination::product-list') }}
            </div>
        </div>
    </section>
@endsection
