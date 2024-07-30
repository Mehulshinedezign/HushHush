<tr>
    <td>
        <a href="#" class="user-table-profile">
            <div class="table-profile ">
                @if ($query->product)
                    <img src="{{ $query->product->thumbnailImage->file_path ?? '' }}" alt="tb-profile" width="26"
                        height="27">
                @else
                    <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile" width="26"
                        height="27">
                @endif
            </div>
        </a>
    </td>
    <td>
        <div class="user-table-head">
            <h5>{{ $query->product->name ?? '' }}</h5>
        </div>
    </td>
    <td>
        <p class="Inquiry-desc">{{ $query->query_message ?? '' }}</p>
    </td>
    <td>{{ $query->date_range ?? '' }}</td>
    <td>
        @if ($query->status == 'ACCEPTED')
            ACCEPTED
        @elseif($query->status == 'PENDING')
            PENDING
        @else
            REJECTED
        @endif
    </td>
    <td class="user-active">
        <div class="inquiry-actions">
            <a href="{{ route('common.chat') }}" class="button outline-btn small-btn"><i
                    class="fa-solid fa-comments"></i> Chat</a>
            <a href="{{ route('query_view') }}" class="button primary-btn small-btn single_query_Modal"
                data-bs-toggle="modal" data-product-id="{{ $query->product_id }}">
                <i class="fa-solid fa-eye"></i> View
            </a>
            @if ($query->status == 'PENDING')
                <a href="#" class="button outline-btn small-btn">Status
                    Pending...</a>
            @elseif ($query->status == 'REJECTED')
                <a href="#" class="button outline-btn small-btn">Status
                    Rejected...</a>
            @else
                @if (is_null($query->negotiate_price))
                    @php
                        $price = $query->getCalculatedPrice($query->date_range);
                    @endphp
                    <a href="{{ route('card.details', ['query' => $query->id, 'price' => $price]) }}"
                        class="button outline-btn small-btn" data-toggle="modal" data-price="{{ $price }}">Book
                        now for
                        {{ $price }}$</a>
                @else
                    <a href="{{ route('card.details', ['query' => $query->id, 'price' => $query->negotiate_price]) }}"
                        class="button outline-btn small-btn" data-price="{{ $query->negotiate_price }}">Book now for
                        {{ $query->negotiate_price }}$</a>
                @endif
            @endif
        </div>
    </td>
</tr>
