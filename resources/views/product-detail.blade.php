@extends('layouts.front')
<style>
    .formfield {
        position: relative;
        /* Ensure the date picker stays relative to the input field */
    }

    .daterangepicker {
        z-index: 9999 !important;
        /* Ensure the calendar stays on top */
        position: absolute !important;
        /* Prevent it from being fixed */
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 999;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black with opacity */
    }

    /* Modal Content (Image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
</style>
@section('title', $product->name)
@php
    $user = auth()->user();
@endphp
@php
    $city = @$product->productCompleteLocation->city;
    $state = @$product->productCompleteLocation->state;
    $country = @$product->productCompleteLocation->country;

    $locationParts = [];

    if ($city) {
        $locationParts[] = $city;
    }
    if ($state) {
        $locationParts[] = $state;
    }
    if ($country) {
        $locationParts[] = $country;
    }

    $selectedValue = implode(', ', $locationParts);
@endphp
@section('content')



    <section class="product-desc-sec">

        <div class="container">
            <div class="breadcrum-main">
                <a href="{{ url('/') }}" class="breadcrum-list">Home</a>
                <a href="#" class="breadcrum-list">{{ $product->categories->name ?? '' }}</a>
                {{-- @dd($product->categories->singlesubcategory); --}}
                @if ($product->categories && !is_null($product->categories->singlesubcategory))
                    <a href="#"
                        class="breadcrum-list active">{{ $product->categories->singlesubcategory->name ?? '' }}</a>
                @endif
            </div>
            <div class="product-desc-main">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="product-desc-box">
                            <div class="product-desc-slider single-img-slider">
                                <div class="container">
                                    <div class="desc-slider-main">
                                        <div class="slider slider-thumb">
                                            @if ($productImages->isNotEmpty() && count($productImages) > 1)
                                                @foreach ($productImages as $image)
                                                    <div class="thumb-slide"><img src="{{ $image->file_path }}"
                                                            alt="" loading="lazy">
                                                    </div>
                                                @endforeach

                                            @endif

                                        </div>
                                        <div class="slider slider-content">
                                            @if ($productImages->isNotEmpty())
                                                @foreach ($productImages as $image)
                                                    {{-- <div><img src="{{ $image->file_path }}" class="zoomout" alt=""
                                                            loading="lazy"></div> --}}

                                                    <!-- Image that opens the modal -->
                                                    <div>
                                                        <img src="{{ $image->file_path }}" alt="" loading="lazy"
                                                            onclick="openModal(this.src)">
                                                    </div>

                                                    <!-- Modal Structure -->
                                                @endforeach
                                            @else
                                                <div><img src="{{ asset('front/images/pro-description-img.png') }}"
                                                        alt="img"></div>
                                            @endif
                                        </div>
                                        <!-- <div class="product-desc-slider-small"> -->

                                    </div>
                                </div>
                                <div class="prev-prodec-btn {{ count($productImages) > 1 ? '' : 'd-none' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="15"
                                        viewBox="0 0 13 18" fill="none">
                                        <path
                                            d="M2.31092 0.000310067C2.04488 -0.00554124 1.78363 0.0715788 1.56349 0.221067C1.34335 0.370556 1.17525 0.585024 1.08257 0.834463C0.989895 1.0839 0.977237 1.35598 1.04636 1.61295C1.11548 1.86991 1.26295 2.09885 1.46827 2.26813L9.30214 8.9793L1.46827 15.6881C1.32624 15.7925 1.2073 15.925 1.11889 16.0775C1.03047 16.23 0.974498 16.3991 0.954463 16.5742C0.934429 16.7494 0.950767 16.9267 1.00246 17.0952C1.05414 17.2637 1.14007 17.4198 1.25485 17.5535C1.36963 17.6873 1.51079 17.7958 1.66949 17.8725C1.8282 17.9492 2.00103 17.9922 2.17716 17.999C2.35328 18.0058 2.52892 17.9763 2.69306 17.9121C2.8572 17.8478 3.00631 17.7504 3.13106 17.6259L12.1001 9.95196C12.241 9.83176 12.3541 9.68257 12.4316 9.51443C12.5092 9.34629 12.5494 9.16328 12.5494 8.97812C12.5494 8.79295 12.5092 8.60995 12.4316 8.4418C12.3541 8.27366 12.241 8.12433 12.1001 8.00413L3.13106 0.322808C2.90449 0.121559 2.61388 0.00744003 2.31092 0.000310067Z"
                                            fill="#272643" />
                                    </svg>
                                </div>
                                <div class="next-prodec-btn {{ count($productImages) > 1 ? '' : 'd-none' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="15"
                                        viewBox="0 0 13 18" fill="none">
                                        <path
                                            d="M2.31092 0.000310067C2.04488 -0.00554124 1.78363 0.0715788 1.56349 0.221067C1.34335 0.370556 1.17525 0.585024 1.08257 0.834463C0.989895 1.0839 0.977237 1.35598 1.04636 1.61295C1.11548 1.86991 1.26295 2.09885 1.46827 2.26813L9.30214 8.9793L1.46827 15.6881C1.32624 15.7925 1.2073 15.925 1.11889 16.0775C1.03047 16.23 0.974498 16.3991 0.954463 16.5742C0.934429 16.7494 0.950767 16.9267 1.00246 17.0952C1.05414 17.2637 1.14007 17.4198 1.25485 17.5535C1.36963 17.6873 1.51079 17.7958 1.66949 17.8725C1.8282 17.9492 2.00103 17.9922 2.17716 17.999C2.35328 18.0058 2.52892 17.9763 2.69306 17.9121C2.8572 17.8478 3.00631 17.7504 3.13106 17.6259L12.1001 9.95196C12.241 9.83176 12.3541 9.68257 12.4316 9.51443C12.5092 9.34629 12.5494 9.16328 12.5494 8.97812C12.5494 8.79295 12.5092 8.60995 12.4316 8.4418C12.3541 8.27366 12.241 8.12433 12.1001 8.00413L3.13106 0.322808C2.90449 0.121559 2.61388 0.00744003 2.31092 0.000310067Z"
                                            fill="#272643" />
                                    </svg>
                                </div>

                            </div>
                            <div class="product-review-main">
                                @if ($product->ratings->contains('user_id', auth()->id()))
                                    <div class="product-review-heading">
                                        <p>{{ $product->ratings_count }} Ratings & Reviews</p>
                                        <div class="form-group">
                                            <div class="formfield">
                                                <p>Most Recent</p>
                                                {{-- <select name="" id="" readonly>
                                                <option value="">Most Recent</option>

                                            </select> --}}
                                                {{-- <span class="form-icon">
                                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}" alt="img">
                                            </span> --}}
                                            </div>
                                        </div>
                                    </div>

                                    @if (count($product->ratings) > 0)
                                        @php $count = 0; @endphp
                                        <div class="review-row-box">
                                            @foreach ($product->ratings as $rating)
                                                {{-- @if ($count < 2) --}}

                                                <div class="product-review-body">
                                                    <ul class="product-review-list">
                                                        <li>
                                                            <div class="product-review-box">
                                                                <div class="product-review-profile">
                                                                    <div class="review-profile-box">
                                                                        <div class="sm-dp-box">
                                                                            @if ($rating->user->profile_file)
                                                                                <img src="{{ asset('storage/' . $rating->user->profile_file) }}"
                                                                                    alt="img">
                                                                            @else
                                                                                <img src="{{ asset('front/images/pro-1.png') }}"
                                                                                    alt="img">
                                                                            @endif
                                                                        </div>
                                                                        <div class="sm-dp-data">
                                                                            <h3>{{ @$rating->user->name }}</h3>
                                                                            <div class="review-profile-rating-box">
                                                                                @for ($i = 0; $i < $rating->rating; $i++)
                                                                                    <i
                                                                                        class="fa-sharp fa-solid fa-star"></i>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <p>{{ @$rating->created_at->format('M d, Y') }}</p>
                                                                </div>
                                                                @if ($rating->review != null)
                                                                    <div class="product-review-message">
                                                                        <p>{{ @$rating->review }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @php $count++; @endphp
                                                {{-- @else --}}
                                                {{-- @break
                                            @endif --}}
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="pro-desc-details-main">
                            <h4 class="pro-desc-name">{{ $product->name }}</h4>
                            <div class="pro-desc-prize-wrapper">
                                <div class="pro-desc-prize">
                                    <h3>${{ $product->rent_day }}</h3>
                                    <div class="badge day-badge">
                                        Per day
                                    </div>

                                </div>
                                <div class="pro-desc-prize">
                                    <h3>${{ $product->rent_week }}</h3>
                                    <div class="badge day-badge">
                                        Per week
                                    </div>

                                </div>
                                <div class="pro-desc-prize">
                                    <h3>${{ $product->rent_month }}</h3>
                                    <div class="badge day-badge">
                                        Per month
                                    </div>

                                </div>
                            </div>
                            <div class="pro-desc-info">
                                <div class="pro-desc-info-box">
                                    <h4>Category :</h4>
                                    <p>{{ $product->categories->name ?? 'N/A' }}</p>
                                </div>
                                <div class="pro-desc-info-box">
                                    <h4>Size:</h4>
                                    <p>{{ @$product->size ?? 'N/A' }}</p>
                                </div>

                            </div>

                            <div class="pro-desc-info">
                                <div class="pro-desc-info-box">
                                    <h4>Brand :</h4>
                                    <p>{{ @$product->brand ?? 'N/A' }}</p>
                                </div>

                                <div class="pro-desc-info-box">
                                    <h4>Color :</h4>
                                    <p>{{ $product->get_color->name ?? 'N/A' }}</p>
                                </div>

                            </div>
                            <div class="pro-desc-info">
                                <div class="pro-desc-info-box">
                                    <h4>Min Rental Days :</h4>
                                    <p>{{ $product->min_days_rent_item }}</p>
                                </div>
                                <!-- <div class="pro-desc-info-box">
                                                                                                <h4>Size :</h4>
                                                                                                <p>{{ $product->size ?? 'N/A' }}</p>
                                                                                            </div> -->


                            </div>
                            @auth
                                @if (@$product->user_id != auth()->user()->id)
                                    @if (is_null($user->userDetail->complete_address))
                                        {{-- <div data-bs-toggle="modal" data-bs-target="#addaddress-Modal">
                                        Send Request
                                    </div> --}}
                                        <a href="#" class="button primary-btn full-btn sendQuery" data-bs-toggle="modal"
                                            data-bs-target="#accountSetting" aria-controls="offcanvasRight">Send Request</a>
                                    @else
                                        <a href="#" class="button primary-btn full-btn " data-bs-toggle="offcanvas"
                                            data-bs-target="#inquiry-sidebar" aria-controls="offcanvasRight">Send Request</a>
                                    @endif
                                @endif
                            @endauth
                            @guest
                                {{-- <a href="{{ route('login') }}" class="button primary-btn full-btn ">Send Request</a> --}}
                                <a href="{{ route('login', ['redirect_url' => url()->current()]) }}"
                                    class="button primary-btn full-btn mt-3">Send Request</a>

                            @endguest
                            <div class="pro-info-accordian">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Description
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                {{ $product->description ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="pro-info-accordian">
                                    <div class="accordion" id="accordionExample1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingtwo">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseTwo" aria-expanded="true"
                                                    aria-controls="collapseTwo">
                                                    Pickup Location
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse show"
                                                aria-labelledby="headingtwo" data-bs-parent="#accordionExample1">
                                                <div class="accordion-body">
                                                    {{ @$selectedValue }}
                                                    {{-- @if (isset($product->productCompleteLocation->pick_up_location))
                                                        {{ @$product->productCompleteLocation->pick_up_location }}
                                                    @else
                                                        {{ @$product->productCompleteLocation->pick_up_location ?? 'N/A' }} --}}

                                                        {{-- {{ @$product->productCompleteLocation->city . '  ' . @$product->productCompleteLocation->state . '  ' . @$product->productCompleteLocation->country }} --}}
                                                    {{-- @endif --}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="lender-profile">
                                    <p>Lender</p>
                                    <div class="lender-profile-box">
                                        <div class="lender-dp-box">
                                            @auth
                                                <a href="{{ route('lenderProfile', jsencode_userdata($product->user_id)) }}">
                                                    @if ($product->retailer->profile_file)
                                                        <img src="{{ asset('storage/' . $product->retailer->profile_file) }}"
                                                            alt="Profile Picture">
                                                    @else
                                                        <img src="{{ asset('front/images/pro3.png') }}" alt="Default Image">
                                                    @endif
                                                </a>
                                            @endauth
                                            @guest
                                                <a href="{{ route('login') }}">
                                                    @if ($product->retailer->profile_file)
                                                        <img src="{{ asset('storage/' . $product->retailer->profile_file) }}"
                                                            alt="Profile Picture">
                                                    @else
                                                        <img src="{{ asset('front/images/pro3.png') }}" alt="Default Image">
                                                    @endif
                                                </a>
                                            @endguest
                                        </div>
                                        <h4>{{ $product->retailer->name }}</h4>
                                        @auth
                                            @if ($product->user_id != auth()->id())
                                                <div><a href="javascript:void(0)"
                                                        class="button outline-btn small-btn chat-list-profile"
                                                        data-senderId="{{ auth()->user()->id }}"
                                                        data-receverId="{{ @$product->user_id }}"
                                                        data-receverName = "{{ @$product->retailer->name }}"
                                                        data-receverImage="{{ isset($product->retailer->profile_file) ? Storage::url($product->retailer->profile_file) : asset('img/avatar.png') }}"
                                                        data-profile="{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}"
                                                        data-name="{{ auth()->user()->name }}"><i
                                                            class="fa-solid fa-comments"></i>
                                                        Chat</a></div>
                                            @endif
                                        @endauth
                                        @guest
                                            <div><a href="{{ route('login') }}" class="button outline-btn small-btn"><i
                                                        class="fa-solid fa-comments"></i>
                                                    Chat</a></div>
                                        @endguest
                                        @php
                                            // Check if the current user has already reported this product
                                            $userHasReported = $product->reportedProducts->contains(function ($report) {
                                                return $report->user_id == auth()->id();
                                            });
                                        @endphp
                                        @auth
                                            @if ($product->user_id != auth()->id())
                                                @if (!$userHasReported)
                                                    <div>
                                                        <button id="report-btn" data-product-id="{{ $product->id }}"
                                                            class="btn btn-danger">Report Product</button>
                                                    </div>
                                                @else
                                                    <div>
                                                        <button class="btn btn-danger">Already Reported Product</button>
                                                    </div>
                                                @endif
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                @if ($product->ratings->contains('user_id', auth()->id()))
                                    @if (count($product->ratings) > 0)
                                        <div class="pro-dec-rating-main mb-0">
                                            <div class="pro-rating-head">
                                                <h4>Ratings & Reviews</h4>

                                            </div>
                                            <div class="pro-rating-body">
                                                <div class="pro-rating-left">
                                                    <h3>{{ $product->average_rating }}</h3>
                                                    <p>{{ $product->ratings()->count() }} &
                                                        {{ $product->ratings()->count() }}
                                                        Reviews</p>
                                                </div>
                                                <div class="pro-rating-right">
                                                    <ul>
                                                        <li>
                                                            <p>5</p>
                                                            <i class="fa-solid fa-star"></i>
                                                            <div class="progress">
                                                                <div class="progress-bar " role="progressbar"
                                                                    style="background-color: #517B5D; width:{{ $rating_progress['fivestar'] }}%"
                                                                    aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <p>4</p>
                                                            <i class="fa-solid fa-star"></i>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="background-color: #517B5D; width:{{ $rating_progress['fourstar'] }}%"
                                                                    aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <p>3</p>
                                                            <i class="fa-solid fa-star"></i>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="background-color: #517B5D; width:{{ $rating_progress['threestar'] }}%"
                                                                    aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <p>2</p>
                                                            <i class="fa-solid fa-star"></i>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="background-color: #517B5D; width:{{ $rating_progress['twostar'] }}%"
                                                                    aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <p>1</p>
                                                            <i class="fa-solid fa-star"></i>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="background-color: #517B5D; width:{{ $rating_progress['onestar'] }}%"
                                                                    aria-valuenow="100" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="imageModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="product-popup-box">
                            <span class="btn-close" onclick="closeModal()"></span>
                            <img class="" id="modalImage">
                        </div>
                    </div>
                </div>
            </div>
            @php
                $authUser = auth()->user();
            @endphp
            <div class="offcanvas offcanvas-end inquiry-sidebar" tabindex="-1" id="inquiry-sidebar"
                aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Send Request</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="book-item-sidebar">
                        <div class="book-item-main">
                            <div class="book-item-profile">
                                <div class="book-item-profile-img">
                                    @if ($productImages->isNotEmpty())
                                        @foreach ($productImages as $image)
                                            <div><img src="{{ $image->file_path }}" alt="" loading="lazy"></div>
                                        @endforeach
                                    @else
                                        <div><img src="{{ asset('front/images/pro-description-img.png') }}"
                                                alt="img"></div>
                                    @endif
                                </div>
                                <div class="book-item-profile-info">
                                    <h3>{{ @$product->name }}</h3>
                                    <div class="pro-desc-prize-wrapper">
                                        <div class="pro-desc-prize">
                                            <h3>${{ @$product->rent_day }}</h3>
                                            <div class="badge day-badge">
                                                Per day
                                            </div>

                                        </div>
                                        <div class="pro-desc-prize">
                                            <h3>${{ @$product->rent_week }}</h3>
                                            <div class="badge day-badge">
                                                Per week
                                            </div>

                                        </div>
                                        <div class="pro-desc-prize">
                                            <h3>${{ @$product->rent_month }}</h3>
                                            <div class="badge day-badge">
                                                Per month
                                            </div>

                                        </div>

                                    </div>
                                    <div class="pro-desc-prize-wrapper">
                                        <label for="">Min Rental days: </label>
                                        <div class="min-rental-date">
                                            <h3>{{ @$product->min_days_rent_item }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <x-alert /> --}}
                            <form id="Sendquery">
                                @csrf
                                <input type="hidden" name="for_user"
                                    value="{{ jsencode_userdata($product->user_id) }}">
                                <input type="hidden" name="product_id" value="{{ jsencode_userdata($product->id) }}">
                                <input type="hidden" id="address_id" name="address_id"
                                    value="{{ @$authUser->userDetail->id }}">
                                <div class="book-item-date">
                                    <div class="form-group">
                                        <label for="">Select your Rental date</label>
                                        <div class="formfield">
                                            <input type="text" name="rental_dates" id="rental_dates"
                                                class="form-control rent_dates @error('rental_dates') is-invalid @enderror"
                                                placeholder="Select rental date" readonly autocomplete="off">
                                            <label for="rental_dates" class="form-icon">
                                                <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img">
                                            </label>
                                        </div>
                                        @error('rental_dates')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group my-3">
                                        <label for="">Message to lender</label>
                                        <div class="formfield">
                                            <textarea name="description" cols="30" rows="3"
                                                class="form-control form-class @error('description') is-invalid @enderror" placeholder="message to lender"></textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="item-pickup-loc-main">
                                        @if (
                                            $product->productCompleteLocation &&
                                                $product->productCompleteLocation->manul_pickup_location == '1' &&
                                                $product->productCompleteLocation->shipment == '1')
                                            <h4>Choose Location</h4>

                                            <input type="radio" id="pick_up" name="delivery_option" value="pick_up"
                                                @if ($product->productCompleteLocation->shipment == '0') checked @endif>
                                            <label for="pick_up">Pick up from lender location</label><br>

                                            <input type="radio" id="ship_to_me" name="delivery_option"
                                                value="ship_to_me" @if ($product->productCompleteLocation->manual_pickup_location == '0') checked @endif>
                                            <label for="ship_to_me">Ship it to me</label><br>

                                            <input type="text" id="selected_value" readonly class="form-control"
                                                placeholder="Selected option will appear here"
                                                value="Please select the value">
                                            @error('delivery_option')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror


                                            <a href="javascript:void(0);" id="manage_address_link"
                                                class="manage-address-link">Change Address</a>
                                        @elseif(
                                            $product->productCompleteLocation &&
                                                $product->productCompleteLocation->manul_pickup_location == '1' &&
                                                $product->productCompleteLocation->shipment == '0')
                                            <h4>Pick up Location</h4>
                                            <input type="radio" id="pick_up" name="delivery_option" value="pick_up"
                                                checked>
                                            <label for="pick_up">Pick up from lender location</label><br>

                                            <input type="text" id="selected_value" readonly class="form-control"
                                                value="{{ @$product->productCompleteLocation->city . ', ' . @$product->productCompleteLocation->state }}">
                                        @elseif(
                                            $product->productCompleteLocation &&
                                                $product->productCompleteLocation->manul_pickup_location == '0' &&
                                                $product->productCompleteLocation->shipment == '1')
                                            <h4>Delivery Location</h4>
                                            <input type="radio" id="ship_to_me" name="delivery_option"
                                                value="ship_to_me" checked>
                                            <label for="ship_to_me">Ship it to me</label><br>
                                            <input type="text" id="selected_value" readonly class="form-control"
                                                value="{{ @$authUser->userDetail->complete_address }}">
                                            <input type="hidden" value="{{ @$authUser->userDetail->id }}"
                                                name='address_id'>

                                            <a href="javascript:void(0);" id="manage_address_link"
                                                class="manage-address-link">Change Address</a>
                                        @else
                                            <input type="text" id="ship" readonly class="form-control"
                                                placeholder="No available pickup or shipment location">
                                        @endif
                                    </div>

                                </div>

                                <button type="button" class="button primary-btn full-btn mt-3 mb-3"
                                    id="Askquery">Next</button>
                            </form>

                        </div>
                        @auth
                            @include('modal.addressModal')
                        @endauth
                    </div>
                </div>
            </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('report-btn').addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to report this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, report it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/report-product/${productId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire(
                                    'Reported!',
                                    data.message,
                                    'success'
                                );
                                // location.reload();
                            } else {
                                Swal.fire(
                                    'Oops!',
                                    data.message,
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'Something went wrong, please try again later.',
                                'error'
                            );
                        });
                }
            });
        });
    </script>
    <script>
        document.getElementById('manage_address_link').addEventListener('click', function() {
            // Open the modal when clicking on "Change Address"
            var myModal = new bootstrap.Modal(document.getElementById('addressModal'));
            myModal.show();

        });

        // Function to handle address selection from the modal
        function selectAddress(id, fullAddress) {
            // Update selected address in the form
            document.getElementById('selected_value').value = fullAddress;

            // Update hidden address_id field
            document.getElementById('address_id').value = id;
        }
    </script>
    <script>
        $('.sendQuery').on('click', function() {
            console.log('herer', $('#saveProfile'))
            //    var url={{ route('Userprofile', ['type' => 'query']) }}
            var url = APP_URL + '/update-profile/query';
            $('#saveProfile').attr('action', url);
        })

        $(document).ready(function() {
            $('.edit-address').on('click', function() {
                $('#addNewAddressBtn').removeClass('d-none');
            })
        })

        // $('.slider-content').slick({
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     arrows: false,
        //     fade: false,
        //     swiping: false;
        //     infinite: true,
        //     speed: 1000,
        //     asNavFor: '.slider-thumb',
        //     prevArrow: $('.prev-prodec-btn'),
        //     nextArrow: $('.next-prodec-btn'),
        //     arrows: true,
        //     responsive: [{
        //             breakpoint: 1400,
        //             settings: {
        //                 slidesToShow: 1,
        //                 slidesToScroll: 1,
        //             }
        //         },
        //         {
        //             breakpoint: 992,
        //             settings: {
        //                 slidesToShow: 1,
        //                 slidesToScroll: 1,
        //             }
        //         },
        //         {
        //             breakpoint: 575,
        //             settings: {
        //                 slidesToShow: 1,
        //                 slidesToScroll: 1,
        //             }
        //         }

        //     ]
        // });
        // $('.slider-thumb').slick({
        //                 slidesToShow: 10,
        //                 slidesToScroll: 1,
        //                 vertical: true,
        //                 asNavFor: '.slider-content',
        //                 dots: false,
        //                 focusOnSelect: true,
        //                 arrows: false,
        // });



        // $('.slider-content').slick({
        //                 slidesToShow: 1,
        //                 slidesToScroll: 1,
        //                 swipe: true,
        //                 arrows: true,
        //                 fade: true,
        //                 asNavFor: '.slider-thumb'
        // });
        // $('.slider-thumb').slick({
        //                 slidesToShow: 10,
        //                 slidesToScroll: 1,
        //                 vertical: true,
        //                 swipe: false,
        //                 asNavFor: '.slider-content',
        //                 dots: false,
        //                 focusOnSelect: true,
        //                 arrows: false,
        //                 verticalSwiping: true

        // });

        $('.slider-content').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: false,
            asNavFor: '.slider-thumb',
            autoplay: false,
            prevArrow: $('.prev-prodec-btn'),
            nextArrow: $('.next-prodec-btn'),
            autoplaySpeed: 2000,
        });
        $('.slider-thumb').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.slider-content',

            focusOnSelect: true,
            vertical: true,
        });



        $(document).ready(function() {
            $(document).ready(function() {
                $('#Askquery').on('click', function(e) {
                    let form = $('form#Sendquery')[0];
                    let formData = new FormData(form);
                    let hasErrors = false;

                    // Check if rental dates are selected
                    if (!$('#rental_dates').val()) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Please select a rental date.',
                            position: 'topRight',
                        });
                        hasErrors = true;
                    }

                    // Determine selected delivery option and get the associated address_id
                    if ($('#ship_to_me').is(':checked')) {
                        // Use the existing address ID for "Ship to Me" option
                        let addressId = $('#address_id').val();
                        formData.append('address_id', addressId);
                        if (!addressId) {
                            iziToast.error({
                                title: 'Error',
                                message: 'Please select an address for shipping.',
                                position: 'topRight',
                            });
                            hasErrors = true;
                        }
                    } else if ($('#pick_up').is(':checked')) {
                        // If "Pick Up" is selected, clear the address_id as it's not needed
                        formData.delete('address_id');
                    }

                    // If there are errors, prevent form submission
                    if (hasErrors) {
                        e.preventDefault();
                        return;
                    }

                    // Proceed if form is valid
                    if ($('#Sendquery').valid()) {
                        var url = `{{ route('query') }}`;
                        $.ajax({
                            type: "post",
                            url: url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function() {
                                $('body').addClass('loading');
                            },
                            complete: function() {
                                $('body').removeClass('loading');
                            },
                            success: function(response) {
                                var modalContent = '';
                                if (response.success) {
                                    modalContent = `<div class="success-text" role="alert">
                                            <img src="{{ asset('front/images/query1.png') }}" style="max-width: 180px;">
                                            ${response.message}
                                        </div>`;
                                } else {
                                    iziToast.error({
                                        title: 'Error',
                                        message: response.message,
                                        position: 'topRight',
                                    });
                                    return;
                                }

                                $('#query_msg .modal-body').html(
                                    `<button type="button" class="btn-close" id="closeModalBtn">&times;</button>` +
                                    modalContent
                                );

                                $('#query_msg').modal('show');
                                $('#Askquery').prop('disabled', false);
                                $("#Sendquery")[0].reset();
                                $('#closeModalBtn').on('click', function() {
                                    $('#query_msg').modal('hide');
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                $('#Askquery').prop('disabled', false);
                                $('#query_msg .modal-body').html(
                                    `<button type="button" class="close" id="closeModalBtn">&times;</button>
                         <div class="alert alert-danger" role="alert">${response.message}</div>`
                                );
                                $('#query_msg').modal('show');
                                $('#closeModalBtn').on('click', function() {
                                    $('#query_msg').modal('hide');
                                    location.reload();
                                });
                            }
                        });
                    } else {
                        e.preventDefault();
                    }
                });
            });



            var queryDates = @json($querydates);
            var disableDates = @json($disable_dates);
            var disabledDateRanges = queryDates.map(function(query) {
                var dateRange = query.date_range.split(' - ');
                return {
                    start: moment(dateRange[0]),
                    end: moment(dateRange[1])
                };
            });

            var noneAvailableDates = disableDates.map(function(dateRange) {
                return {
                    start: moment(dateRange),
                    end: moment(dateRange)
                };
            }).filter(function(range) {
                return range !== null;
            });

            function isDateDisabled(date) {
                var inQueryDates = disabledDateRanges.some(function(range) {
                    return date.isBetween(range.start, range.end, 'day', '[]');
                });

                var inDisableDates = noneAvailableDates.some(function(range) {
                    return date.isSame(range.start, 'day') || date.isSame(range.end, 'day');
                });

                return inQueryDates || inDisableDates;
            }

            $('.rent_dates').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                drops: 'down',
                opens: 'right',
                minDate: moment().startOf('day'),
                isInvalidDate: isDateDisabled,
                parentEl: 'body'
            }).on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate;
                var endDate = picker.endDate;
                var duration = endDate.diff(startDate, 'days');

                var count = {{ $product->min_days_rent_item }};
                if (duration < count - 1) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Please select a date range of at least ' + count +
                            ' days.',
                        position: 'topRight',
                    });
                    $(this).val('');
                } else {
                    $(this).val(startDate.format('YYYY-MM-DD') + ' - ' + endDate.format(
                        'YYYY-MM-DD'));
                }
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="delivery_option"]');
            const selectedValueInput = document.getElementById('selected_value');
            const manageAddressLink = document.getElementById('manage_address_link');

            const updateSelectedValue = () => {
                const checkedRadio = document.querySelector('input[name="delivery_option"]:checked');
                if (checkedRadio) {
                    if (checkedRadio.id === 'pick_up') {
                        selectedValueInput.value = '{{ $selectedValue }}';
                        // "{{ @$product->productCompleteLocation->city . ', ' . @$product->productCompleteLocation->state . ', ' . @$product->productCompleteLocation->country }}";
                        manageAddressLink.style.display =
                            'none';
                    } else if (checkedRadio.id === 'ship_to_me') {
                        selectedValueInput.value =
                            "{{ @$authUser->userDetail->complete_address }}";
                        manageAddressLink.style.display =
                            'block';
                    }
                } else {
                    selectedValueInput.value = "Please select one of the above options";
                    manageAddressLink.style.display = 'none';
                }
            };

            updateSelectedValue();

            radioButtons.forEach(radio => {
                radio.addEventListener('change', updateSelectedValue);
            });

            manageAddressLink.addEventListener('click', function() {
                $('#addressModal').modal('show');
            });

            document.querySelectorAll('input[name="selected_address"]').forEach(addressRadio => {
                addressRadio.addEventListener('change', function() {
                    const selectedAddress = this.nextElementSibling.textContent;
                    selectedValueInput.value = selectedAddress;
                    $('#addressModal').modal('hide');
                });
            });
        });






        function openModal(src) {
            const modal = document.getElementById("imageModal");
            const modalImg = document.getElementById("modalImage");

            modal.style.display = "block";
            modalImg.src = src;
        }

        function closeModal() {
            const modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>

    @include('validation.js_query')
@endpush
