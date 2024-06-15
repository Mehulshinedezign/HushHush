@extends('layouts.front')
@section('title', 'My Wishlist')
@section('content')
    <section class="my-wishlist">
        <div class="slider-section profile-slider-section mt-0">
            <div class="container">
                <h4>Favorites</h4>
                <div class="wishlist-bx">
                    <div class="row justify-content-center">
                        @if (count($products) > 0)
                            @foreach ($products as $i => $product)
                                @if (isset($product->product))
                                    <div class="col-lg-3 col-md-4">
                                        <div class="collection-pro">
                                            <a href="{{ route('viewproduct', jsencode_userdata($product->product->id)) }}">
                                                @if (isset($product->product->thumbnailImage->url))
                                                    <img src="{{ $product->product->thumbnailImage->url }}">
                                                @else
                                                    <img src="{{ asset('img/default-product.png') }}">
                                                @endif
                                            </a>
                                            <p>{{ $product->product->name }}</p>
                                            <h6>${{ $product->product->rent }} <span>day</span></h6>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            {{-- @else
                                    <div class="box-wishlist">
                                        <span class="wishlist-empty">
                                        <img src="{{ asset('front/images/package.png') }}">
                                        </span> 
                                            <h3 class="text-center">Your wishlist is empty</h3>
                                    </div>
                                    @endif --}}
                    </div>
                </div>
                {{ $products->links('pagination::product-list') }}
            </div>
        @else
            <div class="box-wishlist">
                <span class="wishlist-empty">
                    <img src="{{ asset('front/images/package.png') }}">
                </span>
                <h3 class="text-center">Your wishlist is empty</h3>
            </div>
            @endif
        </div>
    </section>
@endsection
