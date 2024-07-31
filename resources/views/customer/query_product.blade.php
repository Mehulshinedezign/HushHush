<div class="query-detail-box">
    <button type="button" class="btn-close query_btn_close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h3>Query Product</h3>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="query-detail-img">
                @if ($query)
                    <img src="{{ $query->product->thumbnailImage->file_path }}" alt="tb-profile" width="26"
                        height="27">
                @else
                    <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile" width="26"
                        height="27">
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="query-detail-info">
                <h4>{{ $query->product->name ?? '' }}</h4>

                @if ($query)
                    <p>{{ $query->date_range ?? '' }}</p>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="query-detail-info">
                <h3>Query</h3>
                @if ($query)
                    <p>{{ $query->query_message ?? '' }}</p>
                @endif
            </div>
        </div>
        @if (@$query->user_id != auth()->user()->id)
        <div class="col-md-12">
            <div class="query-detail-info">
                <h3>Customer</h3>
                @if ($query)
                    <p>{{ $query->user->name ?? '' }}</p>
                @endif
            </div>
        </div>
        @endif

        @if (@$query->status == 'PENDING')
            @if (@$query->user_id != auth()->user()->id)
                <div class="col-md-4 my_query_details">
                    <div class="inquiry-actions">
                        <a href="javascript:void(0)" class="button accept-btn full-btn mb-2"
                        onclick="confirmAccept(event, '{{ $query->id }}','{{$query->date_range}}','{{$query->product->rent_day}}','{{$query->product->rent_week}}','{{$query->product->rent_month}}')">
                        <i class="fa-solid fa-circle-check"></i> Accept
                    </a>
                    </div>
                </div>
                <div class="col-md-4 my_query_details">
                    <div class="inquiry-actions">
                        {{-- <a href="{{ route('reject_query', ['id' => $product->querydata->id]) }}" class="button reject-btn full-btn mb-2" onclick="confirmReject(event)"><i class="fa-solid fa-ban"></i> Reject</a> --}}
                        <a href="javascript:void(0)" class="button reject-btn full-btn mb-2"
                            onclick="confirmReject(event, '{{ $query->id }}')"><i class="fa-solid fa-circle-check"></i> Reject</a>
                    </div>
                </div>
            @endif
            
        @endif

        @if (@$query->status != 'COMPLETED')
        <div class="col-md-4">
            <div class="inquiry-actions">
                <a href="#" class="button outline-btn full-btn"><i class="fa-solid fa-comments"></i> Chat</a>
            </div>
        </div>
        @endif

    </div>
</div>
