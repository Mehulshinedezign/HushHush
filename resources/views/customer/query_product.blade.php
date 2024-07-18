<div class="query-detail-box">
    <button type="button" class="btn-close query_btn_close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h3>Query Product</h3>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="query-detail-img">
                @if ($product)
                    <img src="{{ $product->thumbnailImage->file_path }}" alt="tb-profile" width="26"
                        height="27">
                @else
                    <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile" width="26"
                        height="27">
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="query-detail-info">
                <h4>{{ $product->name ?? '' }}</h4>

                @if ($product)
                    <p>{{ $product->querydat->date_range ?? '' }}</p>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="query-detail-info">
                <h3>Query</h3>
                @if ($product)
                    <p>{{ $product->querydat->query_message ?? '' }}</p>
                @endif
            </div>
        </div>
        <div class="col-md-4 my_query_details">
            <div class="inquiry-actions">
                <a href="{{ route('accept_query', ['id' => $product->querydata->id]) }}" class="button accept-btn full-btn mb-2"><i class="fa-solid fa-circle-check"></i>
                    Accept</a>
            </div>
        </div>
        <div class="col-md-4 my_query_details">
            <div class="inquiry-actions">
                <a href="{{ route('reject_query', ['id' => $product->querydata->id]) }}" class="button reject-btn full-btn mb-2" onclick="confirmReject(event)"><i class="fa-solid fa-ban"></i> Reject</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="inquiry-actions">
                <a href="#" class="button outline-btn full-btn"><i class="fa-solid fa-comments"></i> Chat</a>
            </div>
        </div>

    </div>
</div>
