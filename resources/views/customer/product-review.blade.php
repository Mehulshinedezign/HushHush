<div class="pro-tab-image">
    @if (@$product->product->thumbnailImage->url)
        <img src="{{ @$product->product->thumbnailImage->url }}" alt="">
    @else
        <img src="{{ asset('front/images/pro-1.png') }}" alt="">
    @endif
</div>
<div class="pro-tab-title-contant">
    <p>{{ @$product->product->name }}</p>
    <h6>${{ @$product->product->rent }} <span>day</span></h6>
    <ul>
        <li>
            <span>Category :</span>
            <p>{{ @$product->product->category->name }}</p>
        </li>
        <li>
            <span>Size:</span>
            <p>{{ @$product->product->get_size->name }}</p>
        </li>
    </ul>
    <div class="review-ion">
        @if (isset($rating_number))
            @for ($i = 1; $i <= $rating_number; $i++)
                <i class="fa-sharp fa-solid fa-star fill-ratings"></i>
            @endfor
            @for ($i = 1; $i <= 5 - $rating_number; $i++)
                <i class="fa fa-star empty-ratings"></i>
            @endfor
        @endif
        {{-- <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i> --}}
    </div>
</div>
