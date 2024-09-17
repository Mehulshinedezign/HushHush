@if ($querydatas->isNotEmpty())
    <div class="inquiry-list-main mt-4">
        <div class="db-table">
            <div class="tb-table">
                <table >
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Lender Name</th>
                            <th>Actual Price</th>
                            @if($querydatas->first()->status != 'PENDING')
                            <th>Agreed Price</th>
                            <th>Cleaning Price</th>
                            <th>Shipping Price</th>
                            @endif
                            <th>Message</th>
                            <th>Date</th>
                            <!-- <th>Status</th> -->
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
        <img src="{{ asset('front/images/Empty 1.svg') }}">
        <h3 class="text-center">Your Inquiry is empty</h3>
    </div>
@endif
