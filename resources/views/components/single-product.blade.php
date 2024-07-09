{{-- @foreach ($products as $product)
    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="collection-pro">
            <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}">
                @if (isset($product->thumbnailImage->url))
                    <div class="product-img-bx">
                        <img src="{{ $product->thumbnailImage->url }}">
                    </div>
                @else
                    <img src="{{ asset('img/default-product.png') }}" class="pro-img">
                @endif
            </a>
            @if (!is_null($product->favorites))
                <div class="wishlist-icon" onclick="addToWishlist(this, {{ $product->id }})">
                    <span class="icon-box active"><i class="fa-regular fa-heart"></i></span>
                </div>
            @else
                <div class="wishlist-icon" onclick="addToWishlist(this, {{ $product->id }})">
                    <span class="icon-box"><i class="fa-regular fa-heart"></i></span>
                </div>
            @endif
            <p>{{ $product->name }}</p>
            <h6>${{ $product->rent }} <span>/day</span></h6>
        </div>
    </div>
@endforeach --}}
<div class="home-product-main">
    <div class="home-product-box">
        @foreach ($products as $product)
            <div class="product-card">
                <div class="product-img-box">
                    <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}" class="productLink">
                        @if (isset($product->thumbnailImage->url))
                            <img src="{{ $product->thumbnailImage->url }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <img src="{{ asset('front/images/pro-0.png') }}" alt="{{ $product->name }}"
                                loading="lazy">
                        @endif
                    </a>
                    @if (!is_null($product->favorites))
                        <div class="product-card-like" onclick="addToWishlist(this, {{ $product->id }})">
                            <span><i class="fa-solid fa-heart active"></i></span>
                        </div>
                    @else
                        <div class="product-card-like" onclick="addToWishlist(this, {{ $product->id }})">
                            <span><i class="fa-regular fa-heart"></i><span>
                        </div>
                    @endif
                    <div class="product-card-status">
                        <p>In Stock</p>
                    </div>
                </div>
                <div class="product-card-detail">
                    <p>{{ $product->name }}</p>
                    <h4>${{ $product->rent }}/day</h4>
                </div>
            </div>
        @endforeach
    </div>
</div>
