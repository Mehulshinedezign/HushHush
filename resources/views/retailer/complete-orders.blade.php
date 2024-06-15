<div class="b_req p-0">
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
</div>
