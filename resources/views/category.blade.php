<div class="pro-slider-section">
    <div class="container">
        <div class="custom-slider">
            @foreach(getParentCategory() as $k => $category)
            <div class="product-collection-slide @if(isset($filters) && in_array($category->id, $filters['categories'])) active @endif">
                <a href="{{ route('index', ["category[$k]" => $category->id]) }}">
                    @if($category->category_image_url)
                        <span><img src="{{ $category->category_image_url }}" alt="pro-img" title="{{$category->name}}"></span>
                    @else
                        <span><img src="{{ asset('img/Accessories.svg') }}" alt="pro-img" title="{{$category->name}}"></span>
                    @endif
                    <p>{{$category->name}}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>