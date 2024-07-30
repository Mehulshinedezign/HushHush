<tr class="user_query-{{ $query->id }}">
    <td>
        <a href="#" class="user-table-profile">
            <div class="table-profile">
                @if ($query->product)
                    <img src="{{ $query->product->thumbnailImage->file_path ?? '' }}"
                        alt="tb-profile" width="26" height="27">
                @else
                    <img src="{{ asset('front/images/table-profile1.png') }}"
                        alt="tb-profile" width="26" height="27">
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

    @if ($query->status == 'PENDING')
        <td>
            <input type="text" id="negotiate_price_{{ $query->id }}"
                placeholder="Enter negotiate price" class="negotiation_price_{{ $query->id }}">
        </td>
    @endif
    <td class="user-active">
        <div class="inquiry-actions">
            @if ($query->status == 'PENDING')
                <a href="javascript:void(0)" class="button accept-btn small-btn"
                    onclick="confirmAccept(event, '{{ $query->id }}','{{$query->date_range}}','{{$query->product->rent_day}}','{{$query->product->rent_week}}','{{$query->product->rent_month}}')">
                    <i class="fa-solid fa-circle-check"></i> Accept
                </a>
                <a href="javascript:void(0)" class="button reject-btn small-btn"
                    onclick="confirmReject(event, '{{ $query->id }}')">
                    <i class="fa-solid fa-circle-xmark"></i> Reject
                </a>  
            @endif

            <a href="javascript:void(0)"
                class="button outline-btn small-btn chat-list-profile"
                data-senderId="{{ auth()->user()->id }}"
                data-receverId="{{ $query->user_id }}"
                data-adminName="{{ auth()->user()->name }}"
                data-adminimage="{{ Storage::url(auth()->user()->profile_file) }}">
                <i class="fa-solid fa-comments"></i> Chat
            </a>
            <a href="{{ route('query_view') }}"
                class="button primary-btn small-btn single_query"
                data-bs-toggle="modal"
                data-query-id="{{ $query->id }}">
                <i class="fa-solid fa-eye"></i> View
            </a>
        </div>
    </td>
</tr>