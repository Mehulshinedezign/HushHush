@foreach ($products as $product)
    <div class="collection-pro">
        <div class="btnaction doticon">
            <div class="wishlist-icon edit-icon">
                <span class="icon-box"><i class="fa-solid fa-ellipsis-vertical"></i></span>
            </div>
            <div class="holdertoggle">
                <a href="{{ route('editproduct', [jsencode_userdata($product->id)]) }}"
                    class="btn-edit btn open-product-popup">
                    Edit</a>
                <a delete_url={{ route('deleteproduct', [jsencode_userdata($product->id)]) }} data="product"
                    class="btn-del btn delete">
                    Delete</a>
            </div>
        </div>
        <div class="product-img-bx">
            @if (@$product->thumbnailImage->url)
                <img src="{{ @$product->thumbnailImage->url }}">
            @else
                <img src="{{ asset('img/default-product.png') }}" class="pro-img">
            @endif
        </div>
        {{-- <div class="pro_content"> --}}
        <h3 class="mt-2">{{ @$product->name }}</h3>
        <p>${{ @$product->rent }} <span>day</span></p>
        {{-- <div class="bottom d-flex">
                <div class="sku_tgs"> <span> Category:</span> {{ @$product->category->name }} </div>
                @if ($product->size)
                    <div class="sku_tgs"> <span>Size: </span> {{ @$product->size }} </div>
                @endif
                <div class="sku_tgs"> <span>Price : </span> {{ @$product->price }} </div>
                <div class="sku_tgs"> <span>Condition: </span> {{ @$product->condition }} </div>
                <div class="sku_tgs"> <span> Brand: </span> {{ @$product->get_brand->name }} </div>
                <div class="sku_tgs"> <span> Color: </span> {{ @$product->get_color->name }} </div>
            </div>
        </div> --}}

    </div>
@endforeach
