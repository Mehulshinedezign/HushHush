@foreach ($products as $product)
    <div class="product-card">
        <div class="product-img-box">
            <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}" class="productLink">
                @if ($product->thumbnailImage && $product->thumbnailImage->file_path)
                    <img src="{{ $product->thumbnailImage->file_path }}" alt="{{ $product->name }}">
                @else
                    <img src="{{ asset('front/images/pro-0.png') }}" alt="{{ $product->name }}" >
                @endif
            </a>
            <div class="product-card-like" onclick="addToWishlist(this, {{ $product->id }})">
                @if ($product->favorites && $product->favorites->count() > 0)
                    <i class="fa-solid fa-heart active"></i>
                @else
                    <i class="fa-regular fa-heart"></i>
                @endif
            </div>
        </div>
        <div class="product-card-detail">
            <p>{{ $product->name }}</p>
            <h4>${{ $product->rent_day }}/day</h4>
        </div>
    </div>
@endforeach
