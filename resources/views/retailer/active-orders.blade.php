<div class="order-management">
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
</div>
