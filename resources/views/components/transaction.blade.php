{{-- @if ($orders->isNotEmpty()) --}}
<div class="inquiry-list-main mt-4">
    <div class="db-table">
        <div class="tb-table">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name</th>
                        @if ($earnings == 'totalearning')
                            <th>Earning</th>
                        @else
                            <th>spent</th>
                        @endif
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr class="user_query-{{ $order->id }}">
                            <td>
                                <a href="#" class="user-table-profile">
                                    <div class="table-profile">
                                        @if ($order->product->name)
                                            <img src="{{ $order->product->thumbnailImage->file_path ?? '' }}"
                                                alt="tb-profile" width="26" height="27">
                                        @else
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        @endif
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="user-table-head">
                                    <h5>{{ $order->product->name ?? '' }}</h5>
                                </div>
                            </td>
                            <td>
                                <div class="user-table-head">
                                    <h5>
                                        @if ($earnings == 'totalearning')
                                            ${{ $order->retailer->vendorPayout->amount ?? '0' }}
                                        @else
                                            ${{ $order->transaction->total ?? '0' }}
                                        @endif
                                    </h5>
                                </div>
                            </td>
                            <td>
                                <div class="user-table-head">
                                    <h5>{{ $order->transaction->date }}</h5>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- @else
    <div class="list-empty-box">
        <img src="{{ asset('front/images/no-products.svg') }}">
        <h3 class="text-center">Transaction empty</h3>
    </div>
@endif --}}
