<tr>
    <td data-th="item">
        <div class="d-flex align-items-center">
            <div class="img-holder">
                @if (isset($orderItem->product->thumbnailImage->url))
                    <img src="{{ $orderItem->product->thumbnailImage->url }}">
                @else
                    <img src="{{ asset('img/default-product.png') }}" class="pro-img">
                @endif
            </div>
            {{ $orderItem->product->name }}
        </div>
    </td>
    <td data-th="Price">
        ${{ $orderItem->product->rent }}/day
    </td>
    <td data-th="Total">${{ $orderItem->total }}</td>
    <td data-th="Rental date">
        {{ date('m-d-Y', strtotime($orderItem->order->from_date)) }} {{ $global_date_separator }}
        {{ date('m-d-Y', strtotime($orderItem->order->to_date)) }}
    </td>
    <td data-th="Pickup date">
        {{ date('m-d-Y', strtotime($orderItem->date)) }}
    </td>
    <td data-th="status">
        <span class="waiting">{{ $orderItem->order->status }}</span>
    </td>
    <td data-th="Cancellation">
        <a href="{{ route('retailervieworder', [$orderItem->order_id]) }}" class="edit-btn tabl-circle icon"
            title="View Order"><i class="fas fa-eye"></i></a>
        <a href="{{ route('retalerorderchat', [$orderItem->order_id]) }}" class="remove-btn tabl-circle icon"
            title="Chat With Customer"><i class="far fa-comment-alt"></i></a>
    </td>
</tr>
