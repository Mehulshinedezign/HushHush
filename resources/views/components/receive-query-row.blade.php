<tr class="user_query-{{ $query->id }}">
    <td>
        <a href="#" class="user-table-profile">
            {{-- @dd($query); --}}
            <div class="table-profile">
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

            <a href="{{ route('lenderProfile',[jsencode_userdata($query->user_id)]) }}"><h5>{{ $query->user->name }}</h5></a>
        </div>
    </td>
    <td>
        <div class="user-table-head">
            <h5>${{ $query->getCalculatedPrice($query->date_range) }}</h5>
        </div>
    </td>
    <td>
        <div class="user-table-head">
            <h5>{{ $query->delivery_method  }}</h5>
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

    @if ($query->status == 'PENDING')
        <td class = "negotiation-price-td">
            <input type="text" id="negotiate_price_{{ $query->id }}" value="{{ $query->getCalculatedPrice($query->date_range) }}" placeholder="Enter agreed price"
                min='0' class="negotiation_price_{{ $query->id }}" name="negotiate_price">
            <input type="text" id="cleaning_charges_{{ $query->id }}" placeholder="Enter cleaning charges"
                min='0' class="cleaning_charges_{{ $query->id }}" name="cleaning_price">
                @if($query->delivery_option =='ship_to_me')
            <input type="text" id="shipping_charges_{{ $query->id }}" placeholder="Enter shipping charges"
                min='0' class="shipping_charges_{{ $query->id }}" name="shipping_price">
            @endif
        </td>
    @endif
    {{-- @dd($query->id ); --}}
    <td class="user-active">
        <div class="inquiry-actions">
            @if ($query->status == 'PENDING')
                <a href="javascript:void(0)" class="button accept-btn small-btn"
                    onclick="confirmAccept(event, '{{ $query->id }}','{{ $query->date_range }}','{{ $query->product->rent_day }}','{{ $query->product->rent_week }}','{{ $query->product->rent_month }}','{{ $query->delivery_option }}')">
                    <i class="fa-solid fa-circle-check"></i> Send Offer
                </a>
                <a href="javascript:void(0)" class="button reject-btn small-btn"
                    onclick="confirmReject(event, '{{ $query->id }}')">
                    <i class="fa-solid fa-circle-xmark"></i> Reject
                </a>
            @endif

            @if ($query->status != 'COMPLETED')
                <a href="javascript:void(0)" class="button outline-btn small-btn chat-list-profile"
                    data-senderId="{{ auth()->user()->id }}" data-receverId="{{ $query->user_id }}"
                    data-receverName = "{{ $query->user->name }}"
                    data-receverImage="{{ isset($query->user->profile_file) ? Storage::url($query->user->profile_file) : asset('img/avatar.png') }}"
                    data-profile="{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}"
                    data-name="{{ auth()->user()->name }}"><i class="fa-solid fa-comments"></i>
                    Chat</a>
            @endif
            <a href="{{ route('query_view') }}" class="button primary-btn small-btn single_query"
                data-bs-toggle="modal" data-query-id="{{ $query->id }}" data-query-ship ="{{ $query->delivery_option }}">
                <i class="fa-solid fa-eye"></i> View
            </a>
        </div>
    </td>
</tr>
