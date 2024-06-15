<div class="rreview">
    <div class="d-flex align-items-center justify-content-between">
        <div class="riewleft">
            <h4>Ratings & Reviews</h4>
        </div>
        @if (leaveReview($product->id))
            <div class="riewright">
                <span><a class="get-review" href="javascript:;"
                        data-url="{{ route('addreview', [leaveReview($product->id)['order_id']]) }}"
                        data-orderId={{ leaveReview($product->id)['order_id'] }} data-bs-toggle="modal"
                        data-bs-target="#completed-note">Leave Review</a></span>
            </div>
        @endif
    </div>
    <div class="data_review">
        <div class="d-flex align-items-start">
            <div class="ratingleft">
                <h3>{{ $product->average_rating }}</h3>
                <span>{{ $product->ratings_count }} Ratings & Reviews</span>
            </div>
            <div class="ratingright">
                <div class="d-flex align-items-center mb-2">
                    <span>5</span>
                    <i class="fa fa-star"></i>
                    <div class="progress">
                        <div class="progress-bar green_prgress" role="progressbar"
                            style="width: {{ $rating_progress['fivestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span>4</span>
                    <i class="fa fa-star"></i>
                    <div class="progress">
                        <div class="progress-bar green_prgress" role="progressbar"
                            style="width: {{ $rating_progress['fourstar'] }}%" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span>3</span>
                    <i class="fa fa-star"></i>
                    <div class="progress">
                        <div class="progress-bar green_prgress" role="progressbar"
                            style="width: {{ $rating_progress['threestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span>2</span>
                    <i class="fa fa-star"></i>
                    <div class="progress">
                        <div class="progress-bar o_prgress" role="progressbar"
                            style="width: {{ $rating_progress['twostar'] }}%" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span>1</span>
                    <i class="fa fa-star"></i>
                    <div class="progress">
                        <div class="progress-bar red_prgress" role="progressbar"
                            style="width: {{ $rating_progress['onestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="review_comment">
    <div class="d-flex align-items-center justify-content-between cmt_head">
        <h6 class="mb-0">{{ $product->ratings_count }} Reviews</h6>
        <div class="select-option">
            <select class="form-select" aria-label="Default select example">
                <option selected="">Most Recent</option>
                <option value="1">One Star</option>
                <option value="2">Two Star</option>
                <option value="3">Three Star</option>
                <option value="4">Four Star</option>
                <option value="5">Five Star</option>
            </select>
        </div>
    </div>
    <div class="review_hold">
        @if (count($product->ratings) > 0)
            @foreach ($product->ratings as $rating)
                <div class="review_side">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="profile_review">
                            <div class="review_pro">
                                @if (@$rating->user->frontend_profile_url)
                                    <img src="{{ @$rating->user->frontend_profile_url }}">
                                @else
                                    <img src="{{ asset('front/images/profile1.png') }}">
                                @endif
                            </div>
                            <div class="img_review">
                                <h3>{{ @$rating->user->name }}</h3>
                                <div class="star-ratings">
                                    <div class="fill-ratings" style="width: 100%;">
                                        <span class="d-flex">
                                            @for ($i = 0; $i < $rating->rating; $i++)
                                                <i class="fa-sharp fa-solid fa-star"></i>
                                            @endfor
                                        </span>
                                    </div>
                                    <div class="empty-ratings">
                                        <span class="d-flex">
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="date_review">
                            <p>{{ @$rating->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <p>{{ @$rating->review }}</p>
                </div>
                <div class="date_review">
                    <p>{{ @$rating->created_at->format('M d, Y') }}</p>
                </div>
    </div>
    <p>{{ @$rating->review }}</p>
</div>
@endforeach
@else
<div class="review_side">
    {{-- <h5 class="text-center reviewheading">Reviews not found</h5> --}}
</div>
@endif
{{-- <div class="view_more"><button class="btn_review">View More</button></div> --}}
</div>
</div>

@include('review-modal')
