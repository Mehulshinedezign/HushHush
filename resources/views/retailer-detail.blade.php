@extends('layouts.front')
@section('title', 'lender')
@section('content')
    <section>
        <div class="detail-page-profile-sec">
            <div class="container">
                <div class="detail-page-profile-tittle">
                    <h4>Profile</h4>
                </div>
                <div class="detail-page-profile-box">
                    <div class="profile-box-detail">
                        <div class="profile-box-img-box">
                            @if ($retailer->frontend_profile_url)
                                <img src="{{ $retailer->frontend_profile_url }}">
                            @else
                                <img src="{{ asset('front/images/profile1.png') }}">
                            @endif
                        </div>
                        <h5 class="profile-box-name">{{ $retailer->name }}</h5>
                        <div class="profile-detail-location">
                            <img src="images/flag1.png" alt="">
                            <p>
                                @if (isset($retailer->userDetail))
                                    {{ isset($retailer->userDetail->country) ? $retailer->userDetail->country->name : '' }},
                                    {{ isset($retailer->userDetail->state) ? $retailer->userDetail->state->name : '' }},
                                    {{ isset($retailer->userDetail->city) ? $retailer->userDetail->city->name : '' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="profile-box-about-detail">
                        <h6>About</h6>
                        {!! isset($retailer->userDetail) ? $retailer->userDetail->about : '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="slider-section profile-slider-section">
            <div class="container">
                <h4>Products</h4>
                <div class="profile-custom-slider">
                    <x-single-product :products="$products" />
                </div>
            </div>
        </div>
    </section>
@endsection
