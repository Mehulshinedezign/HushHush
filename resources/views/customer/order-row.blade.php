<div class="pro-tab-contant">
    <div class="pro-tab-contant-row">
        <div class="pro-tab-image">
            @if (@$order->item->product->thumbnailImage->url)
                <img src="{{ $order->item->product->thumbnailImage->url }}" alt="">
            @else
                <img src="{{ asset('img/default-product.png') }}" class="pro-img">
            @endif
        </div>
        <div class="pro-tab-title-contant">
            <p>{{ @$order->item->product->name }}</p>
            <h6>${{ @$order->item->product->rent }} <span>/day</span></h6>
            <ul>
                <li>
                    <span>Category:</span>
                    <p>{{ $order->item->product->category->name }}</p>
                </li>
                <li>
                    <span>Size:</span>
                    <p>{{ @$order->item->product->size }}</p>
                </li>
                <li>
                    <span>Brand:</span>
                    <p>Chic Couture</p>
                </li>

                <li>
                    <span>Color:</span>
                    <p>{{ getColorsName(@$order->item->product->color) }}</p>
                </li>
                <li>
                    <span>Condition:</span>
                    <p>{{ @$order->item->product->condition }}</p>
                </li>
                <li>
                    <span>City:</span>
                    <p>{{ @$order->item->city }}</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="btn-holder-order">
        <a href="{{ route('vieworder', [$order->id]) }}" class="cancel-btn btn-dark btn-view">View</a>
        <a href="{{ route('orderchat', [$order->id]) }}" class="cancel-btn">Chat</a>

        @if (
            'Pending' == $order->status &&
                'No' == $order->dispute_status &&
                $order->cancellation_time_left > 2 &&
                !is_null($order->transaction))
            <button type="button" class="cancel-btn cancel-order" data-url="{{ route('cancel-order', $order->id) }}"
                data-bs-toggle="modal" data-bs-target="#cancellation-note">Cancel order</button>
        @endif

        @if ('Completed' == $order->status)
            <button type="button" class="cancel-btn get-review" data-url="{{ route('addreview', [$order->id]) }}"
                data-orderId={{ $order->id }} data-bs-toggle="modal"
                data-bs-target="#completed-note">Review</button>
        @endif
    </div>
</div>
