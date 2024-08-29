@if ($querydatas->isNotEmpty())
    <div class="inquiry-list-main mt-4">
        <div class="db-table">
            <div class="tb-table">
                <table class="inquiry-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Actual Price</th>
                            <th>Shipment Type</th>
                            @if ($querydatas->first()->status != 'PENDING')
                                <th>Negotiation Price</th>
                                <th>Cleaning Price</th>
                                <th>Shipping Price</th>
                            @endif
                            <th>Query</th>
                            <th>Date</th>

                            @if ($accept)
                                <th>Set Price</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($querydatas as $query)
                            <x-receive-query-row :query="$query" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="list-empty-box">
        <img src="{{ asset('front/images/Empty 1.svg') }}">
        <h3 class="text-center">Receive Query is empty</h3>
    </div>
@endif
