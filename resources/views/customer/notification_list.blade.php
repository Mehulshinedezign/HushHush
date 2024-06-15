@extends('layouts.front')

@section('title', 'Notifications')

@section('content')
    <section class="section-space all-notifi-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="innerbox-container p-0">
                        <div class="heading-outerbox d-block">
                            <h5 class="order-heading mb-0">Notifications</h5>
                        </div>
                        <hr class="border-0 mb-35">
                        <div class="ven-all-notification-section">
                            @foreach ($allNotifications as $notification)
                                <div class="recent-notificstionbox ven-all-notifi">
                                    <figure>
                                        @if (isset($notification->order->item->product->thumbnailImage->url))
                                            <img src="{{ @$notification->order->item->product->thumbnailImage->url }}"
                                                alt="product-img" class="img-fluid">
                                        @else
                                            <img src="{{ asset('img/default-product.png') }}" alt="product-img"
                                                class="img-fluid">
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
                            <div class="custom-pagination">{{ $allNotifications->links('pagination::product-list') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
