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
                    <p class="table-pro-name">{{ $query->product->name ?? '' }}</p>

                @else
                    <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile" width="26"
                        height="27">
                @endif
            </div>
        </a>
    </td>
    {{-- <td>
        <div class="user-table-head">
        </div>
    </td> --}}
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
                min='0' class="negotiation_price_{{ $query->id }} charge" name="negotiate_price">
            <input type="text" id="cleaning_charges_{{ $query->id }}" placeholder="Enter cleaning charges"
                min='0' class="cleaning_charges_{{ $query->id }} charge" name="cleaning_price">
                @if($query->delivery_option =='ship_to_me')
            <input type="text" id="shipping_charges_{{ $query->id }}" placeholder="Enter shipping charges"
                min='0' class="shipping_charges_{{ $query->id }} charge" name="shipping_price">
            @endif
        </td>
    @endif
    {{-- @dd($query->id ); --}}
    <td class="user-active">
        {{-- <div class="inquiry-actions ">
            <div class="btn-group dropstart">
                <div type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </div>
                <ul class="dropdown-menu" style="">
                    
                </ul>
              </div> --}}
            {{-- <div class="dropdown">
                <div class=" dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    
                </div>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <div class="align-dropdown">
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
                </ul>
            </div> --}}
            
        {{-- </div> --}}
        <div class="align-dropdown-row">
            @if ($query->status == 'PENDING')
                <a href="javascript:void(0)" class="accept-btn"  data-bs-toggle="tooltip" data-bs-placement="top" title="Send Offer"
                    onclick="confirmAccept(event, '{{ $query->id }}','{{ $query->date_range }}','{{ $query->product->rent_day }}','{{ $query->product->rent_week }}','{{ $query->product->rent_month }}','{{ $query->delivery_option }}')">
                    <i class="fa-solid fa-circle-check"></i>
                </a>
                <a href="javascript:void(0)" class="reject-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Reject"
                    onclick="confirmReject(event, '{{ $query->id }}')">
                    <i class="fa-solid fa-circle-xmark"></i>
                </a>
            @endif

            @if ($query->status != 'COMPLETED')
                <a href="javascript:void(0)" class="chat-list-profile no-btn"data-bs-toggle="tooltip" data-bs-placement="top" title="Chat"
                    data-senderId="{{ auth()->user()->id }}" data-receverId="{{ $query->user_id }}"
                    data-receverName = "{{ $query->user->name }}"
                    data-receverImage="{{ isset($query->user->profile_file) ? Storage::url($query->user->profile_file) : asset('img/avatar.png') }}"
                    data-profile="{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}"
                    data-name="{{ auth()->user()->name }}"><i class="fa-solid fa-comments"></i>
                    </a>
            @endif
            <a href="{{ route('query_view') }}" class="single_query" data-bs-toggle="tooltip" data-bs-placement="top" title="View"
                data-bs-toggle="modal" data-query-id="{{ $query->id }}" data-query-ship ="{{ $query->delivery_option }}">
                <i class="fa-solid fa-eye"></i>
            </a>
        </div>
    </td>
</tr>
