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
                            <div class="product-desc-slider">
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
                                        @if ($productImages->isNotEmpty())
                                            @foreach ($productImages as $image)
                                                <div><img src="{{ $image->file_path }}" alt="" loading="lazy"></div>
                                            @endforeach
                                        @else
                                            <div><img src="{{ asset('front/images/pro-description-img.png') }}"
                                                    alt="img"></div>
                                        @endif

                                    </div>
                                </div>
                                <div class="prev-prodec-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18"
                                        viewBox="0 0 26 18" fill="none">
                                        <path
                                            d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                            fill="#1B1B1B"></path>
                                    </svg>
                                </div>
                                <div class="next-prodec-btn">
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
                                            <select name="" id="">
                                                <option value="">Most Recent</option>
                                                <option value="">older</option>
                                                <option value="">unseen</option>
                                            </select>
                                            <span class="form-icon">
                                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}" alt="img">
                                            </span>
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
                                                                        <img src="{{ asset('storage/' . $rating->user->profile_file) }}" alt="img">
                                                                    @else
                                                                        <img src="{{ asset('front/images/pro-1.png') }}" alt="img">
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
                                    <p>{{ $product->get_size->name ?? 'L' }}</p>
                                </div>

                            </div>
                            {{-- <div class="form-group">
                                <label for="">Select your Rental date</label>
                                <div class="formfield">
                                    <input type="text" placeholder="7/31/2023  to  8/01/2023" class="form-control">
                                    <span class="form-icon">
                                        <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img">
                                    </span>
                                </div>
                            </div> --}}
                            {{-- <div class="pro-desc-loc">
                                <h3>Pick up Location</h3>
                                <div class="pro-desc-loc-copy">
                                    <p>Akshya Nagar 1st Block 1st Cross, Rammurthy nagar....</p>
                                    <a href="#" class="copy-add-btn"><img
                                            src="{{ asset('front/images/copy-icon.svg') }}" alt="img">Copy</a>
                                </div>
                            </div> --}}
                            {{-- <a href="#" class="button primary-btn full-btn mt-3" data-bs-toggle="offcanvas"
                                data-bs-target="#bookitem-sidebar" aria-controls="offcanvasRight">Book Now</a> --}}
                            @if (@$product->user_id != auth()->user()->id)
                                <a href="#" class="button primary-btn full-btn mt-3" data-bs-toggle="offcanvas"
                                    data-bs-target="#inquiry-sidebar" aria-controls="offcanvasRight">Ask Query</a>   
                            @endif

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
                                    {{-- <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                Additional information
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                Safer For The Environment: Our denim factory partner recycles 98% of their
                                                water using reverse osmosis filtration and keeps byproducts out of the
                                                environment by mixing them with concrete.
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="lender-profile">
                                <p>Lender</p>
                                <div class="lender-profile-box">
                                    <div class="lender-dp-box">
                                        <a href="{{ route('lenderProfile', jsencode_userdata($product->user_id)) }}">
                                            @if ($user->profile_file)
                                                <img src="{{ asset('storage/' . $product->retailer->profile_file) }}"
                                                    alt="Profile Picture">
                                            @else
                                                <img src="{{ asset('front/images/pro3.png') }}" alt="Default Image">
                                            @endif
                                        </a>
                                    </div>
                                    <h4>{{ $product->retailer->name }}</h4>
                                </div>
                            </div>
                            <div class="pro-dec-rating-main">
                                <div class="pro-rating-head">
                                    <h4>Ratings & Reviews</h4>
                                    <a href="javascript:void(0)" data-toggle="modal" data-rating_product_id="{{$product->id}}"
                                         data-target="#rating_review">Leave Review</a>
                                </div>
                                <div class="pro-rating-body">
                                    <div class="pro-rating-left">
                                        <h3>{{ $product->average_rating }}</h3>
                                        <p>{{$product->ratings()->count()}} & {{ $product->ratings()->count() }} Reviews</p>
                                    </div>
                                    <div class="pro-rating-right">
                                        <ul>
                                            <li>
                                                <p>5</p>
                                                <i class="fa-solid fa-star"></i>
                                                <div class="progress">
                                                    <div class="progress-bar " role="progressbar"
                                                        style="background-color: #517B5D; width:{{ $rating_progress['fivestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>4</p>
                                                <i class="fa-solid fa-star"></i>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="background-color: #517B5D; width:{{ $rating_progress['fourstar'] }}%" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>3</p>
                                                <i class="fa-solid fa-star"></i>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="background-color: #517B5D; width:{{ $rating_progress['threestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>2</p>
                                                <i class="fa-solid fa-star"></i>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="background-color: #517B5D; width:{{ $rating_progress['twostar'] }}%" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>1</p>
                                                <i class="fa-solid fa-star"></i>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="background-color: #517B5D; width:{{ $rating_progress['onestar'] }}%" aria-valuenow="100" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
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
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Ask query</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                                    <div><img src="{{ asset('front/images/pro-description-img.png') }}" alt="img"></div>
                                @endif
                            </div>
                            <div class="book-item-profile-info">
                                <h3>{{ @$product->name}}</h3>
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
                            </div>
                        </div>
                        {{-- <x-alert /> --}}
                        <form id="Sendquery">
                            @csrf
                            <input type="hidden" name="for_user" value="{{ jsencode_userdata($product->user_id) }}">
                            <input type="hidden" name="product_id" value="{{ jsencode_userdata($product->id) }}">
                            <div class="book-item-date">
                                <div class="form-group">
                                    <label for="">Select your Rental date</label>
                                    <div class="formfield">
                                        <input type="text" name="rental_dates"
                                            class="form-control rent_dates form-class @error('rental_dates') is-invalid @enderror"
                                            placeholder="Select rental date">
                                        <span class="form-icon">
                                            <!-- <img src="images/calender-icon.svg" alt="img"> -->
                                            <img src="{{ asset('front/images//calender-icon.svg') }}" alt="img">
                                        </span>
                                    </div>
                                    @error('rental_dates')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
        
                                <div class="form-group my-3">
                                    <label for="">Description</label>
                                    <div class="formfield">
                                        <textarea name="description" id="" cols="30" rows="5"
                                            class="form-control form-class @error('description') is-invalid @enderror" placeholder="Description"></textarea>
                                    </div>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="item-pickup-loc-main">
        
                                    <h4>Pick up Location</h4>
                                    @if(@$product->productCompleteLocation->manul_pickup_location == '1')
                                        <p>{{ $product->productCompleteLocation->pick_up_location ?? '' }}</p>
                                    @endif
        
                                </div>
                            </div>
        
                            <button type="button" class="button primary-btn full-btn mt-3" data-bs-toggle="offcanvas"
                                data-bs-target="#checkout-sidebar" aria-controls="offcanvasRight"
                                id="Askquery">Next</button>
        
                        </form>
                        {{-- <div class="book-item-footer">
                            <a href="#" class="button primary-btn full-btn mt-3" data-bs-toggle="offcanvas"
                                data-bs-target="#checkout-sidebar" aria-controls="offcanvasRight">Next</a>
                        </div> --}}
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
            centerMode: false,
            focusOnSelect: true
        });

        $(document).ready(function() {
            $('#Askquery').on('click', function() {
                let form = $('form#Sendquery')[0];
                let formData = new FormData(form);
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

                        success: function(response) {
                            var modalContent = '';
                            if (response.success) {
                                modalContent =
                                    '<div class="alert alert-success" role="alert">' + response
                                    .message + '</div>';
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

                            // Add click event to close button
                            $('#closeModalBtn').on('click', function() {
                                $('#query_msg').modal('hide');
                            });
                        },
                        error: function(response) {
                            $('#Askquery').prop('disabled', false);
                            $('#query_msg .modal-body').html(
                                '<button type="button" class="close" id="closeModalBtn">&times;</button>' +
                                '<div class="alert alert-danger" role="alert">Please fill all fields or processing your request.</div>'
                            );
                            $('#query_msg').modal('show');

                            // Add click event to close button
                            $('#closeModalBtn').on('click', function() {
                                $('#query_msg').modal('hide');
                            });
                        }
                    });

                }
            })



            // date range jquery
   var queryDates = @json($querydates);


            var disabledDateRanges = queryDates.map(function(query) {
                var dateRange = query.date_range.split(' - ');
                return {
                    start: moment(dateRange[0]),
                    end: moment(dateRange[1])
                };
            });

            $('.rent_dates').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                drops: 'down',
                opens: 'right',
                minDate: moment().startOf('day'),
                isInvalidDate: function(date) {
                    return disabledDateRanges.some(function(range) {
                        return date.isSame(range.start, 'day') || date.isSame(range.end, 'day');
                    });
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('.daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                autoUpdateInput: false,
                minDate: moment().startOf('day'),
                isInvalidDate: function(date) {
                    return disabledDateRanges.some(function(range) {
                        return date.isSame(range.start, 'day') || date.isSame(range.end, 'day');
                    });
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });

        });
    </script>
@endpush
