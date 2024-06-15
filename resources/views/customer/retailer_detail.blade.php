@extends('layouts.customer')

@section('title', 'Retailer Detail')

@section('content')
    <section class="vendor-profile-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="vendor-profi-content">
                        <div class="ven-pro-img">
                            @if(isset($retailer->profile_pic_url))
                                <img src="{{ $retailer->profile_pic_url }}" class="img-fluid round-img">
                            @else
                                <img src="{{ asset('img/avatar-small.png') }}" class="img-fluid round-img">
                            @endif
                        </div>
                        <div class="ven-pro-text">
                            <h5 class="w-600">{{ $retailer->name }}</h5>
                            <p class="designation medFont">{{ ucfirst($retailer->role->name) }}</p>
                            <div class="product-rating ">
                                <div class="star-ratings">
                                    <div class="fill-ratings" style="width: {{ $averageRating * 20 }}%;">
                                        <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                    </div>
                                    <div class="empty-ratings">
                                        <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                            <p class="medFont">{!! $retailer->about !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="products-outer">
        <div class="container">
            <div class="product-listing">
                <h6 class="w-600">Related Products</h6>
                <hr>
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 p-0 col-margin">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <img src="{{ @$product->thumbnailImage->url }}" alt="img">
                                </div>
                                <p class="product-card-name">{{ $product->name }}</p>
                                <div class="product-reviews">
                                    <div class="product-price">
                                        <p class="largeFont mb-0 black-text">${{ $product->rent }}</p>
                                        <small>(Per Day)</small>
                                    </div>
                                    <div class="product-rating ">
                                        <div class="star-ratings">
                                            <div class="fill-ratings" style="width: {{ $product->average_rating * 20 }}%;">
                                                <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                            </div>
                                            <div class="empty-ratings">
                                                <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                        <small><span>{{ $product->average_rating }} </span>({{ $product->ratings()->count() }} {{ __('product.reviews') }})</small>
                                    </div>
                                </div>
                                <a href="{{ route('viewproduct', $product->id) }}" class="btn blue-btn fullwidth blue-outline">Book Now</a>
                                @if (@auth()->user()->role->name == 'customer')
                                    @if(!is_null($product->favorites))
                                        <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box active"><i class="fas fa-heart"></i></span></div>
                                    @else
                                        <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box"><i class="far fa-heart"></i></span></div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                    {{ $products->links('pagination::product-list') }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @if(@auth()->user()->role->name == 'customer')
        <script>
            const userId = {!! json_encode(auth()->user()->id) !!};
            const url = "{{ route('addfavorite') }}";
            const errorTitle = "{{ __('favorite.error') }}"
        </script>
        <script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
    @endif
@stop