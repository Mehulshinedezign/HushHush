<tr>
    <td>
        <a href="#" class="user-table-profile">
            <div class="table-profile ">
                @if ($query->product)
                    <a href="{{ route('viewproduct', jsencode_userdata($query->product->id)) }}">
                        <img src="{{ $query->product->thumbnailImage->file_path ?? '' }}" alt="tb-profile" width="26"
                            height="27">
                    </a>
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
        <div class="user-table-head">
            <h5>${{ $query->getCalculatedPrice($query->date_range) }}</h5>
        </div>
    </td>
    @if ($query->status != 'PENDING')
        <td>
            <div class="user-table-head">
                <h5>${{ $query->negotiate_price ?? '0' }}</h5>
            </div>
        </td>

        <td>
            <div class="user-table-head">
                <h5>${{ $query->cleaning_charges ?? '0' }}</h5>
            </div>
        </td>
        <td>
            <div class="user-table-head">
                <h5>${{ $query->shipping_charges ?? '0' }}</h5>
            </div>
        </td>
    @endif
    <td>
        <p class="Inquiry-desc">{{ $query->query_message ?? '' }}</p>
    </td>
    <td>{{ $query->date_range ?? '' }}</td>
    <!-- <td>
        @if ($query->status == 'ACCEPTED')
ACCEPTED
@elseif($query->status == 'PENDING')
PENDING
@elseif($query->status == 'REJECTED')
REJECTED
@elseif($query->status == 'COMPLETED')
COMPLETED
@endif
    </td> -->
    <td class="user-active">
        <div class="inquiry-actions">
            @if ($query->status != 'COMPLETED')
                <a href="javascript:void(0)" class="button outline-btn small-btn chat-list-profile"
                    data-senderId="{{ auth()->user()->id }}" data-receverId="{{ @$query->product->user_id }}"
                    data-receverName = "{{ @$query->product->retailer->name }}"
                    data-receverImage="{{ isset($product->retailer->profile_file) ? Storage::url($product->retailer->profile_file) : asset('img/avatar.png') }}"
                    data-profile="{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}"
                    data-name="{{ auth()->user()->name }}"><i class="fa-solid fa-comments"></i>
                    Chat</a>
            @endif
            <a href="{{ route('query_view') }}" class="button primary-btn small-btn single_query_Modal"
                data-bs-toggle="modal" data-query-id="{{ $query->id }}">
                <i class="fa-solid fa-eye"></i> View
            </a>
            @if ($query->status == 'ACCEPTED')
                {{-- @if (is_null($query->negotiate_price)) --}}
                @php
                    $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);
                    $price = $price + $query->shipping_charges + $query->cleaning_charges;
                @endphp
                <a href="{{ route('card.details', ['query' => $query->id, 'price' => $price]) }}"
                    class="button outline-btn small-btn" data-price="{{ $price }}">Accept
                    offer for
                    {{ $price }}$</a>
                {{-- @else
                    <a href="{{ route('card.details', ['query' => $query->id, 'price' => $query->negotiate_price]) }}"
                        class="button outline-btn small-btn" data-price="{{ $query->totalBookPrice() }}">Accept offer for
                        {{ $query->totalBookPrice() }}$</a>
                @endif --}}
            @endif
        </div>
    </td>
</tr>
