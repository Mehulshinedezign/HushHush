@extends('layouts.front')

@section('title', 'Order Success')

@section('content')
<section class="success-page">
    <div class="container">
        <div class="success-box">
            <div class="success-header">
                <p class="success-check"><i class="fas fa-check-circle"></i></p>
                <h3 class="white-text mb-2 w-400">{{ __('order.success') }}!</h3>
                <p class="mb-3">{{ __('order.message') }}</p>
                <a href="{{ route('orderchat', [$order->id]) }}" class="btn primary-btn">{{ __('order.goToMessage') }}</a>
            </div>
            <div class="success-body">
                <h5>{{ __('order.orderDetails') }}</h5>
                <ul class="listing-row">
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.bookingid') }} </span>
                        <span>#{{ $order->id }} </span>
                    </li>
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.productName') }}</span>
                        <span>{{ $order->item->product->name }}</span>
                    </li>
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.gmapAddress') }}</span>
                        <span>{{ $order->location->map_address }}</span>
                    </li>
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.customAddress') }}</span>
                        <span>{{ $order->location->custom_address }}</span>
                    </li>
                    @if(is_null($order->from_hour))
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.pickUpDate') }}</span>
                        <span>{{ date($global_date_format, strtotime($order->from_date)) }} </span>
                    </li>
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.returnDate') }}</span>
                        <span>{{ date($global_date_format, strtotime($order->to_date)) }} </span>
                    </li>
                    @else
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.pickUpDate') }}</span>
                        <span>{{ date($global_product_blade_date_time_format, strtotime($order->from_date . $order->from_hour . ':' . $order->from_minute)) }}</span>
                    </li>
                    <li class="list-col auto-width">
                        <span class="list-item w-500">{{ __('order.returnDate') }}</span>
                        <span>{{ date($global_product_blade_date_time_format, strtotime($order->to_date . $order->to_hour . ':' . $order->to_minute)) }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection