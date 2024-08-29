@extends('layouts.front')
@section('title', 'My Wishlist')
@section('content')
    <section class="my-wishlist-sec   fill-hight">
        <div class="slider-section profile-slider-section mt-0">
            <div class="container">
                {{-- <h4>Favorites</h4> --}}
                @if ($products->isNotEmpty())
                    <div class="home-product-main">
                        <div class="home-product-box">
                            @foreach ($products as $favrait)
                                <div class="product-card">
                                    <div class="product-img-box">
                                        @if ($favrait->product)
                                            <a href="{{ route('viewproduct', ['id' => jsencode_userdata($favrait->product->id)]) }}"
                                                class="productLink">
                                                @if ($favrait->product->thumbnailImage && $favrait->product->thumbnailImage->file_path)
                                                    <img
                                                        src="{{ $favrait->product->thumbnailImage->file_path }}"alt="{{ $favrait->product->name }}">
                                                @else
                                                    <img
                                                        src="{{ asset('front/images/pro-0.png') }}"alt="{{ $favrait->product->name }}">
                                                @endif
                                            </a>
                                        @else
                                            <p>Product not found</p>
                                        @endif
                                        <div class="product-card-like"
                                            onclick="addToWishlist(this, {{ $favrait->product->id ?? 'null' }})">
                                            @if ($favrait->product && !is_null($favrait->product->favorites))
                                                <i class="fa-solid fa-heart active"></i>
                                            @else
                                                <i class="fa-regular fa-heart"></i>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($favrait->product)
                                        <p>{{ $favrait->product->name ?: 'Product Name Not Available' }}</p>
                                        <h4>${{ $favrait->product->rent_day ?: '0' }}/day</h4>
                                    @else
                                        <p>Product Not Available</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="list-empty-box">
                        <img src="{{ asset('front/images/Empty 1.svg') }}">
                        <h3 class="text-center">Your wishlist is empty</h3>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
