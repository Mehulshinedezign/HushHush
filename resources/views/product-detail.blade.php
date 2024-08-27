@extends('layouts.front')
@section('title', $product->name)
@php
    $user = auth()->user();
@endphp
@section('content')



    <section class="product-desc-sec">

        <div class="container">
            <div class="breadcrum-main">
                <a href="{{ url('/') }}" class="breadcrum-list">Home</a>
                <a href="#" class="breadcrum-list">{{ $product->categories->name ?? '' }}</a>
                <a href="#" class="breadcrum-list active">{{ $product->categories->singlesubcategory->name ?? '' }}</a>
            </div>
            <div class="product-desc-main">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="product-desc-box">
                            <div class="product-desc-slider single-img-slider">
                                <div class="container">

                                    <div class="slider slider-content">
                                        @if ($productImages->isNotEmpty())
                                            @foreach ($productImages as $image)
                                                <div><img src="{{ $image->file_path }}" alt="" loading="lazy"></div>
                                            @endforeach
                                        @else
                                            <div><img src="{{ asset('front/images/pro-description-img.png') }}"
                                                    alt="img"></div>
                                        @endif
                                    </div>
                                    <!-- <div class="product-desc-slider-small"> -->
                                    <div class="slider slider-thumb">
                                        @if ($productImages->isNotEmpty() && count($productImages) > 1)
                                            @foreach ($productImages as $image)
                                                <div><img src="{{ $image->file_path }}" alt="" loading="lazy"></div>
                                            @endforeach

                                        @endif

                                    </div>
                                </div>
                                <div class="prev-prodec-btn {{ count($productImages) > 1 ? '' : 'd-none' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18"
                                        viewBox="0 0 26 18" fill="none">
                                        <path
                                            d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                            fill="#1B1B1B"></path>
                                    </svg>
                                </div>
                                <div class="next-prodec-btn {{ count($productImages) > 1 ? '' : 'd-none' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18"
                                        viewBox="0 0 26 18" fill="none">
                                        <path
                                            d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                            fill="#1B1B1B"></path>
                                    </svg>
                                </div>

                            </div>
                            <div class="product-review-main">
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
                                    @foreach ($product->ratings as $rating)
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
                                                                            <i class="fa-sharp fa-solid fa-star"></i>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p>{{ @$rating->created_at->format('M d, Y') }}</p>
                                                        </div>
                                                        <div class="product-review-message">
                                                            <p>{{ @$rating->review }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    @endforeach

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
                                    <p>{{ $product->categories->name ?? '' }}</p>
                                </div>
                                <div class="pro-desc-info-box">
                                    <h4>Size:</h4>
                                    <p>{{ @$product->size ?? 'L' }}</p>
                                </div>

                            </div>

                            <div class="pro-desc-info">
                                <div class="pro-desc-info-box">
                                    <h4>Brand :</h4>
                                    <p>{{ $product->get_brand->name ?? 'N/A' }}</p>
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
                                        <a href="#" class="button primary-btn full-btn mt-3" data-bs-toggle="modal"
                                            data-bs-target="#addaddress-Modal" aria-controls="offcanvasRight">Send Request</a>
                                    @else
                                        <a href="#" class="button primary-btn full-btn mt-3" data-bs-toggle="offcanvas"
                                            data-bs-target="#inquiry-sidebar" aria-controls="offcanvasRight">Send Request</a>
                                    @endif
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('login') }}" class="button primary-btn full-btn mt-3">Send Request</a>
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
                                                {{ $product->description }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="pro-info-accordian">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseTwo" aria-expanded="true"
                                                    aria-controls="collapseTwo">
                                                    Pickup Location
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    {{ $product->productCompleteLocation->city ." ,". $product->productCompleteLocation->country }}
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
                                        @endauth
                                        @guest
                                            <div><a href="{{ route('login') }}" class="button outline-btn small-btn"><i
                                                        class="fa-solid fa-comments"></i>
                                                    Chat</a></div>
                                        @endguest
                                    </div>
                                </div>
                                <div class="pro-dec-rating-main">
                                    <div class="pro-rating-head">
                                        <h4>Ratings & Reviews</h4>

                                    </div>
                                    <div class="pro-rating-body">
                                        <div class="pro-rating-left">
                                            <h3>{{ $product->average_rating }}</h3>
                                            <p>{{ $product->ratings()->count() }} & {{ $product->ratings()->count() }}
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
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p>4</p>
                                                    <i class="fa-solid fa-star"></i>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="background-color: #517B5D; width:{{ $rating_progress['fourstar'] }}%"
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p>3</p>
                                                    <i class="fa-solid fa-star"></i>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="background-color: #517B5D; width:{{ $rating_progress['threestar'] }}%"
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p>2</p>
                                                    <i class="fa-solid fa-star"></i>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="background-color: #517B5D; width:{{ $rating_progress['twostar'] }}%"
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p>1</p>
                                                    <i class="fa-solid fa-star"></i>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="background-color: #517B5D; width:{{ $rating_progress['onestar'] }}%"
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <!-- <img src="images/style-single2.png" alt="img"> -->
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
                                <div class="book-item-date">
                                    <div class="form-group">
                                        <label for="">Select your Rental date</label>
                                        <div class="formfield">
                                            <input type="text" name="rental_dates" id="rental_dates"
                                                class="form-control rent_dates form-class @error('rental_dates') is-invalid @enderror"
                                                placeholder="Select rental date">
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
                                            <textarea name="description" cols="30" rows="5"
                                                class="form-control form-class @error('description') is-invalid @enderror" placeholder="message to lender"></textarea>
                                        </div>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="item-pickup-loc-main">
                                        <h4>Pick up Location</h4>

                                        <input type="radio" id="pick_up" name="delivery_option"
                                            value="{{ $product->productCompleteLocation->pick_up_location }}">
                                        <label for="pick_up">Pick up from lender location</label><br>

                                        <input type="radio" id="ship_to_me" name="delivery_option"
                                            value="{{ @$user->userDetail->complete_address }}">
                                        <label for="ship_to_me">Ship it to me</label><br>

                                        {{-- <input type="text" id="selected_value" readonly class="form-control"
                                            placeholder="Selected option will appear here">
                                        <input type="text" id="profile_message" class="message"
                                            style="display: none;"
                                            value="Please complete your profile to enable this option." readonly> --}}
                                            @error('delivery_option')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>


                                </div>

                                <button type="button" class="button primary-btn full-btn mt-3"
                                    id="Askquery">Next</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('.slider-content').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: false,
            infinite: true,
            speed: 1000,
            asNavFor: '.slider-thumb',
            prevArrow: $('.prev-prodec-btn'),
            nextArrow: $('.next-prodec-btn'),
            arrows: true,
            responsive: [{
                    breakpoint: 1400,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }

            ]
        });
        $('.slider-thumb').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.slider-content',
            dots: false,
            focusOnSelect: true
        });

        $(document).ready(function() {
            $('#Askquery').on('click', function(e) {
                let form = $('form#Sendquery')[0];
                let formData = new FormData(form);
                let hasErrors = false;

                // Validate Rental Date
                if (!$('#rental_dates').val()) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Please select a rental date.',
                        position: 'topRight',
                    });
                    hasErrors = true;
                }

                // // Validate Delivery Option
                // if (!$('input[name="delivery_option"]:checked').val()) {
                //     iziToast.error({
                //         title: 'Error',
                //         message: 'Please select a delivery option.',
                //         position: 'topRight',
                //     });
                //     hasErrors = true;
                // }

                // Check for incomplete profile if "Ship it to me" is selected
                if ($('#ship_to_me').is(':checked') &&
                    {{ is_null(@$user->userDetail->complete_address) ? 'true' : 'false' }}) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Please complete your profile to enable this option.',
                        position: 'topRight',
                    });
                    hasErrors = true;
                }

                if (hasErrors) {
                    e.preventDefault(); // Prevent form submission if there are errors
                    return;
                }

                if ($('#Sendquery').valid()) {
                    $('#Askquery').prop('disabled', true);
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
                                modalContent =
                                    `<div class="success-text" role="alert"><img src="` +
                                    "{{ asset('front/images/query1.png') }}" +
                                    `" style="max-width: 180px;">` + response.message +
                                    `</div>`;
                            } else {
                                modalContent = '<div class="alert alert-danger" role="alert">' +
                                    response.message + '</div>';
                            }

                            $('#query_msg .modal-body').html(
                                '<button type="button" class="close" id="closeModalBtn">&times;</button>' +
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
                                '<button type="button" class="close" id="closeModalBtn">&times;</button>' +
                                '<div class="alert alert-danger" role="alert">' + response
                                .message + '</div>'
                            );
                            $('#query_msg').modal('show');
                            $('#closeModalBtn').on('click', function() {
                                $('#query_msg').modal('hide');
                                location.reload();
                            });
                        }
                    });
                } else {
                    e.preventDefault(); // Prevent the default action of the button click
                }
            });

            // Date range picker setup
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
                isInvalidDate: isDateDisabled
            }).on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate;
                var endDate = picker.endDate;
                var duration = endDate.diff(startDate, 'days');

                // Correctly assigning the value from the Blade template
                var count = {{ $product->min_days_rent_item }};

                // console.log(count);

                if (duration < count-1) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Please select a date range of at least ' + count + ' days.',
                        position: 'topRight',
                    });
                    $(this).val('');
                } else {
                    $(this).val(startDate.format('YYYY-MM-DD') + ' - ' + endDate.format('YYYY-MM-DD'));
                }
            });

            $('.daterange-btn').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                drops: 'down',
                opens: 'right',
                minDate: moment().startOf('day'),
                isInvalidDate: isDateDisabled,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format(
                    'MMMM D, YYYY'));
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get the radio buttons and message element
            const radioButtons = document.querySelectorAll('input[name="delivery_option"]');
            const profileMessage = document.getElementById('profile_message');
            const selectedValueInput = document.getElementById('selected_value');

            // Server-side flag for address completeness
            const isAddressComplete = {{ is_null(@$user->userDetail->complete_address) ? 'false' : 'true' }};

            // Add an event listener to each radio button
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const selectedValue = this.value;
                        selectedValueInput.value =
                            selectedValue; // Set the value of the readonly input field

                        // Check if the 'Ship it to me' option is selected and the profile is not complete
                        if (this.id === 'ship_to_me' && !isAddressComplete) {
                            iziToast.error({
                                title: 'Error',
                                message: 'Please complete your profile to enable this option.',
                                position: 'topRight',
                            });
                            profileMessage.style.display =
                                'block'; // Show profile completion message
                        } else {
                            profileMessage.style.display = 'none'; // Hide message
                        }
                    }
                });
            });
        });
    </script>

    {{-- @include('validation') --}}
    @include('validation.js_query')
@endpush
