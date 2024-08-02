@if ($querydatas->isNotEmpty())
    <div class="inquiry-list-main mt-4">
        <div class="db-table">
            <div class="tb-table">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Actual Price</th>
                            <th>Query</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($querydatas as $query)
                            <x-query-row :query="$query" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="list-empty-box">
        <img src="{{ asset('front/images/no-products.svg') }}">
        <h3 class="text-center">Your Query is empty</h3>
    </div>
@endif
