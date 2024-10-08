@extends('layouts.front')
@section('title', 'My Profile')


@section('content')
    <section class="profile-banner-sec">
        <div class="container">
            <div class="profile-banner-name">
                <h2>{{ $retailer->name }}'s profile</h2>
            </div>
        </div>
        <img src="{{ asset('front/images/leaf-img.svg') }}" alt="leaf" class="banner-laef-img">
    </section>
    <section class="product-slider-sec product-slider-sec-min-height">
        <div class="container">
            <div class="profile-detail-box">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <div class="profile-personal-detail">
                            <div class="profile-personal-img">
                                @if ($retailer->profile_file)
                                    <img src="{{ asset('storage/' . $retailer->profile_file) }}" alt="Profile Picture">
                                @else
                                    <img src="{{ asset('front/images/pro3.png') }}" alt="Default Image">
                                @endif
                                {{-- <img src="{{asset('front/images/profile.png')}}" alt="img"> --}}
                            </div>
                            <h3>{{ $retailer->name }}</h3>
                            {{-- <p><img src="{{asset('front/images/us-flag.svg')}}" alt="img"> Los Angeles , USA</p> --}}
                        </div>
                    </div>
                    <div class="col-md-5">

                        {{-- @if (!is_null($retailer->userDetail->about)) --}}
                        <div class="profile-about-detail">
                            <h4>About me</h4>

                            @if ($retailer && $retailer->userDetail)
                                <p>{{ $retailer->userDetail->about }}</p>
                            @endif

                            {{-- <p>Safer For The Environment: Our denim factory partner recycles 98% of their water using
                                reverse osmosis filtration and keeps byproducts out of the environment by mixing them with
                                concrete.</p> --}}
                        </div>
                        {{-- @endif --}}
                    </div>
                    <div class="col-md-5">
                        @if (!is_null($retailer->userDetail->complete_address))
                            <div class="profile-address-detail">
                                <div class="profile-address-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="19"
                                        viewBox="0 0 15 19" fill="none">
                                        <path
                                            d="M7.29395 0.46582C5.50446 0.468194 3.78895 1.18011 2.5236 2.44547C1.25824 3.71083 0.546319 5.42634 0.543945 7.21582C0.543945 12.0618 6.8327 18.1311 7.09988 18.3871C7.15185 18.4376 7.22147 18.4658 7.29395 18.4658C7.36642 18.4658 7.43604 18.4376 7.48801 18.3871C7.7552 18.1311 14.0439 12.0618 14.0439 7.21582C14.0416 5.42634 13.3296 3.71083 12.0643 2.44547C10.7989 1.18011 9.08343 0.468194 7.29395 0.46582ZM7.29395 10.3096C6.68206 10.3096 6.08391 10.1281 5.57515 9.78818C5.06639 9.44823 4.66985 8.96506 4.43569 8.39975C4.20153 7.83444 4.14027 7.21239 4.25964 6.61226C4.37901 6.01213 4.67366 5.46088 5.10633 5.02821C5.539 4.59554 6.09026 4.30089 6.69038 4.18152C7.29051 4.06214 7.91256 4.12341 8.47787 4.35757C9.04318 4.59173 9.52636 4.98826 9.8663 5.49703C10.2062 6.00579 10.3877 6.60393 10.3877 7.21582C10.3872 8.03618 10.0611 8.8228 9.48101 9.40289C8.90093 9.98297 8.11431 10.3091 7.29395 10.3096Z"
                                            fill="#1B1B1B" />
                                    </svg>
                                </div>

                                <div class="profile-address-data">
                                    <h3>Address</h3>

                                    @if ($retailer && $retailer->userDetail)
                                        <p>{{ @$retailer->userDetail->city . ' , ' . @$retailer->userDetail->state . ' , ' . @$retailer->userDetail->country }}
                                        </p>
                                    @endif

                                    {{-- <p>Akshya Nagar 1st Block 1st Cross, Rammurthy nagar Los Angeles , USA</p> --}}
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="product-slider-wrapper">
                <div class="product-slider-heading">
                    <h3>Products by {{ $retailer->name }}</h3>
                </div>
                @if ($products->isNotEmpty())
                    <div class="product-slider-main">
                        <div class="product-slider">
                            @foreach ($products as $product)
                                {{-- @dd($product); --}}
                                <div class="product-card">
                                    <div class="product-img-box">
                                        <a href="{{ route('viewproduct', ['id' => jsencode_userdata($product->id)]) }}">
                                            @if (isset($product->thumbnailImage->file_path))
                                                <img src="{{ $product->thumbnailImage->file_path }}" alt=""
                                                    loading="lazy">
                                            @else
                                                <img src="{{ asset('front/images/pro-0.png') }}" alt="img">
                                            @endif
                                        </a>
                                        {{-- <img src="{{ asset('front/images/pro-0.png') }}" alt="img"> --}}
                                        {{-- <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div> --}}
                                        {{-- <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div> --}}
                                    </div>
                                    <div class="product-card-detail">
                                        <p>{{ $product->name }}</p>
                                        <div class="product-prize-rating">
                                            <h4>${{ $product->rent_day }}/day</h4>
                                            <div class="review-profile-rating-box">
                                                <a href="#"><i class="fa-solid fa-star"
                                                        style="color: #DEE0E3;"></i></a>
                                                <a href="#"><i class="fa-solid fa-star"
                                                        style="color: #DEE0E3;"></i></a>
                                                <a href="#"><i class="fa-solid fa-star"
                                                        style="color: #DEE0E3;"></i></a>
                                                <a href="#"><i class="fa-solid fa-star"
                                                        style="color: #DEE0E3;"></i></a>
                                                <a href="#"><i class="fa-solid fa-star"
                                                        style="color: #DEE0E3;"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                        <div class="prev-product-btn">

                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="9" viewBox="0 0 26 18"
                                fill="none">
                                <path
                                    d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                    fill="#1B1B1B"></path>
                            </svg>

                        </div>
                        <div class="next-product-btn">

                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="9" viewBox="0 0 26 18"
                                fill="none">
                                <path
                                    d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                    fill="#1B1B1B"></path>
                            </svg>

                        </div>
                    </div>
                @else
                    <div class="list-empty-box">
                        <img src="{{ asset('front/images/Empty 1.svg') }}">
                        <h3 class="text-center">No Products Available</h3>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $('.navbar-toggler').on('click', function() {
            $(".navbar-toggler").toggleClass('open');
        });
        $('.send-mail-open').on('click', function() {
            $(".invite-member-popup").toggleClass('open');
        });

        $('.sidebar-expand-btn').on('click', function() {
            $(".home-filter-box").addClass('expand');
        });
        $('.filter-fotter-btns').on('click', function() {
            $(".home-filter-box").removeClass('expand');
        });






        $(document).ready(function() {
            $('.product-slider').slick({
                dots: false,
                infinite: false,
                draggable: true,
                arrow: true,
                slidesToShow: 4,
                prevArrow: $('.prev-product-btn'),
                nextArrow: $('.next-product-btn'),
                responsive: [{
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    }

                ]
            });
        });

        $(document).ready(function() {
            $('.host-cal-slider').slick({
                dots: false,
                infinite: true,
                draggable: true,
                arrow: true,
                slidesToShow: 1,
                prevArrow: $('.prev-banner-btn'),
                nextArrow: $('.next-banner-btn'),
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
        });
    </script>
@endpush
