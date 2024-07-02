{{-- <div class="pro-slider-section">
    <div class="container">
        <div class="custom-slider">
            @foreach (getParentCategory() as $k => $category)
            <div class="product-collection-slide @if (isset($filters) && in_array($category->id, $filters['categories'])) active @endif">
                <a href="{{ route('index', ["category[$k]" => $category->id]) }}">
                    @if ($category->category_image_url)
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
</div> --}}

<section class="home-sec">
    <div class="container">
        <div class="home-wrapper">
            <div class="section-heading">
                <p>Rent the Perfect </p>
                <h2>Dress for Every Occasion</h2>
            </div>
            <div class="home-filter-product">
                <div class="home-filter-box">
                    <div class="filter-head">
                        <h3>Filter</h3>
                    </div>
                    <div class="home-filter-inner">
                        <h4>Product category</h4>
                        <div class="filter-categories category-hight-fix">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check1">
                                <label class="form-check-label" for="category-check1">T-Shirts & Tops</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check2">
                                <label class="form-check-label" for="category-check2">Sweaters</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check3">
                                <label class="form-check-label" for="category-check3">Shirts & Blouses</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check4">
                                <label class="form-check-label" for="category-check4">Dresses</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check5">
                                <label class="form-check-label" for="category-check5">Jackets & Coats</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="category-check6">
                                <label class="form-check-label" for="category-check6">T-Shirts & Tops</label>
                            </div>
                        </div>
                    </div>
                    <div class="home-filter-inner">
                        <h4>Price Range</h4>
                        <div class="filter-categories">
                            <div class="">
                                <div class="price-range-box">
                                    <div class="form-group">
                                        <div class="formfield">
                                            <input type="text" name="" id="" placeholder="min"
                                                class="form-control">
                                            <span class="left-form-icon">
                                                <img src="{{ asset('front/images/dollar-icon.svg') }}" alt="img">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="formfield">
                                            <input type="text" name="" id="" placeholder="max"
                                                class="form-control">
                                            <span class="left-form-icon">
                                                <img src="{{ asset('front/images/dollar-icon.svg') }}" alt="img">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4>Status</h4>
                        <div class="filter-categories">
                            <div class="stock-status">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="category-status1">
                                    <label class="form-check-label" for="category-status1">Stock</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="category-status2">
                                    <label class="form-check-label" for="category-status2">Out of Stock</label>
                                </div>
                            </div>
                        </div>
                        <h4>Size</h4>
                        <div class="filter-categories">
                            <div class="product-size-filter">
                                <label for="size-filter1" class="size-filter">
                                    XS
                                    <input type="radio" name="size" id="size-filter1">
                                </label>
                                <label for="size-filter2" class="size-filter">
                                    S
                                    <input type="radio" name="size" id="size-filter2">
                                </label>
                                <label for="size-filter3" class="size-filter">
                                    M
                                    <input type="radio" name="size" id="size-filter3">
                                </label>
                                <label for="size-filter4" class="size-filter">
                                    L
                                    <input type="radio" name="size" id="size-filter4">
                                </label>
                                <label for="size-filter5" class="size-filter">
                                    XL
                                    <input type="radio" name="size" id="size-filter5">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="home-filter-inner">
                        <h4>Rating</h4>
                        <div class="filter-categories">
                            <div class="filter-rating-box">
                                <div class="filter-rating-inner">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="filter-rating-inner">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="filter-rating-inner">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="filter-rating-inner">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="filter-rating-inner active">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <h4>Date range</h4>
                        <div class="form-group">
                            <div class="formfield">
                                <input type="text" name="" id=""
                                    placeholder="07/26/2023 - 08/02/2023" class="form-control">
                                <span class="form-icon">
                                    <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="home-filter-inner">
                        <h4>Locations</h4>
                        <div class="filter-categories">
                            <div class="filter-location-box">
                                <div class="form-group">
                                    <label for=""><img src="{{ asset('front/images/aim-icon.svg') }}"
                                            alt="img"> Use current
                                        Location</label>
                                    <div class="formfield">
                                        <input type="text" placeholder="Your Location" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4>Area Range</h4>
                        <div class="filter-categories">
                            <div class="filter-range-box">
                                <input type="text" class="js-range-slider" name="my_range" value=""
                                    data-skin="round" data-type="double" data-min="0" data-max="1000"
                                    data-grid="false" />
                            </div>
                        </div>
                    </div>
                    <div class="home-filter-fotter">
                        <div class="filter-fotter-btns">
                            <a href="javascript:void(0)" class="button outline-btn">Cancel</a>
                            <a href="javascript:void(0)" class="button primary-btn">Apply Filter</a>
                        </div>
                    </div>
                    <div class="small-slidebar-icon">
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon1.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon2.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon3.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon4.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon5.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon6.svg') }}" alt="img">
                        </span>
                        <span>
                            <img src="{{ asset('front/images/sidebar-icon7.svg') }}" alt="img">
                        </span>
                    </div>
                    <div class="sidebar-expand-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18" viewBox="0 0 26 18"
                            fill="none">
                            <path
                                d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                fill="#1B1B1B" />
                        </svg>
                    </div>
                </div>

                {{-- Product listing here --}}

                {{-- <div class="home-product-main">
                    <div class="home-product-box">
                        @foreach ($products as $product)
                        <div class="product-card">
                            <div class="product-img-box">
                                <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}">
                                    @if (isset($product->thumbnailImage->url))
                                        <img src="{{ $product->thumbnailImage->url }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{asset('front/images/pro-0.png')}}" alt="{{ $product->name }}">
                                    @endif
                                </a>
                                <div class="product-card-like" onclick="addToWishlist(this, {{ $product->id }})">
                                    @if (!is_null($product->favorites))
                                        <i class="fa-solid fa-heart active"></i>
                                    @else
                                        <i class="fa-regular fa-heart"></i>
                                    @endif
                                </div>
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
                </div> --}}
                
                    @if (count($products) > 0)
                    <x-single-product :products="$products" />
                    {{-- <div class="custom-pagination">{{ $products->links('pagination::product-list') }}</div> --}}
                    @else
                    <div class="home-product-main">
                        <div class="home-product-box">
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro-0.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro2.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro3.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro4.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro5.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro6.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro8.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ asset('front/images/pro-0.png') }}" alt="img">
                                    <div class="product-card-like">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <div class="product-card-status">
                                        <p>in Stock</p>
                                    </div>
                                </div>
                                <div class="product-card-detail">
                                    <p>Glamour Affair</p>
                                    <h4>$159/day</h4>
                                </div>
                            </div>
                        </div>
                        <div class="pagination-main">
                            <a href="javascript:void(0)" class="pagination-box">
                                01
                            </a>
                            <a href="javascript:void(0)" class="pagination-box">
                                02
                            </a>
                            <a href="javascript:void(0)" class="pagination-box active">
                                03
                            </a>
                            <a href="javascript:void(0)" class="pagination-box">
                                04
                            </a>
                            <a href="javascript:void(0)" class="pagination-box">
                                05
                            </a>
                        </div>
                    </div>
                    {{-- <h2 class="text-center">We're sorry, no products fit your search criteria.</h2> --}}
                @endif

            </div>

        </div>
    </div>
    </div>
    </div>
</section>


<section class="three-feature-sec">
    <div class="container">
        <div class="three-feature-wrapper">
            <div class="three-feature-box">
                <img src="{{ asset('front/images/easy-return-icon.svg') }}" alt="easy-return" width="52"
                    height="52">
                <h4>Easy Returns</h4>
            </div>
            <div class="three-feature-box">
                <img src="{{ asset('front/images/quality-check-icon.svg') }}" alt="quality-check" width="52"
                    height="52">
                <h4>Quality Check & Hygiene</h4>
            </div>
            <div class="three-feature-box">
                <img src="{{ asset('front/images/secure-payment-icon.svg') }}" alt="secure-payment" width="52"
                    height="52">
                <h4>Secure Payment</h4>
            </div>
        </div>
    </div>
</section>

{{-- Add produc modal here --}}
<div class="modal fade addproduct-Modal" id="addproduct-Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Add New Product</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="add-product-main">
                    <div class="add-pro-form">
                        <form action="{{ route('saveproduct') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input type="hidden" name="rentaltype" value="Day">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Add Product Images (Up to 5)</label>
                                    <label class="img-upload-box">
                                        <p>Upload Images</p>
                                        <input type="file" name="images[]" id="upload-image" multiple
                                            minlength="5" upload-image-count="0">

                                    </label>
                                    <div class="upload-img-preview">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <div class="formfield">
                                            <input type="text" name="" id=""
                                                placeholder="Enter Name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Category/Subcategory</label>
                                        <div class="duel-select-field">
                                            <div class="formfield">
                                                <select name="category" class="parent_category">
                                                    <option value="">Category</option>
                                                    @foreach (getParentCategory() as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                            <div class="formfield">
                                                <select name="subcategory" id="subcategory">
                                                    <option value="">Subcategory</option>
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Size</label>
                                        <div class="formfield">
                                            <select class="form-control" name="size">
                                                <option value="">Size</option>
                                                @foreach (getAllsizes() as $size)
                                                    <option value="{{ $size->id }}">{{ $size->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="form-icon">
                                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                    alt="img">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Brand</label>
                                        <div class="formfield">
                                            <select class="form-control" name="brand">
                                                <option value="">Brand</option>
                                                @foreach (getBrands() as $brand)
                                                    <option value="{{ $brand->id }}">
                                                        {{ $brand->name }}</option>
                                                @endforeach
                                                {{-- <option value="Other">Other</option> --}}
                                            </select>
                                            <span class="form-icon">
                                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                    alt="img">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Color</label>
                                        <div class="formfield">
                                            <select class="form-control" name="color">
                                                <option value="">Color</option>
                                                @foreach (getColors() as $color)
                                                    <option value="{{ $color->id }}">
                                                        {{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="form-icon">
                                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                    alt="img">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Condition</label>
                                        <div class="formfield">
                                            <input type="text" name="" id=""
                                                placeholder="Product Condition" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">City/Neighborhood</label>
                                        <div class="duel-select-field">
                                            <div class="formfield">

                                                <select class="" name="city">
                                                    <option value="">City</option>
                                                    @foreach (headerneighborhoodcity() as $val=> $city)
                                                        <option value="{{ $city->id }}">
                                                            {{ ucwords($city->name) }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                            <div class="formfield">
                                                <select name="" id="">
                                                    <option value="">Neighborhood</option>
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <div class="formfield">
                                            <textarea name="" id="" rows="4" class="form-control" placeholder="Enter Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Pickup Location</label>
                                        <div class="formfield">
                                            <textarea name="" id="" rows="4" class="form-control" placeholder="Text"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Non-Available Dates</label>
                                        <div class="formfield">
                                            <input type="text" name="" id=""
                                                placeholder="Select Dates" class="form-control">
                                            <span class="form-icon">
                                                <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                    alt="img">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Rent Price</label>
                                        <div class="formfield">
                                            <input type="text" name="" id="" placeholder="$85"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="">Rent Price/Day</label>
                                        <div class="formfield">
                                            <input type="text" name="" id="" placeholder="$85"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="right-btn-box">
                                        <button class="button primary-btn ">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // console.log("Document ready!");
        $('.parent_category').change(function() {
            var categoryId = $(this).val();
            var route = '{{ route('get_subcategories') }}';
            // console.log(route, 'route');

            if (categoryId) {
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: {
                        categoryId: categoryId
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#subcategory').empty();
                        $('#subcategory').append('<option value="">Subcategory</option>');

                        $.each(data, function(key, value) {
                            $('#subcategory').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error occurred: " + error);
                    }
                });
            } else {
                $('#subcategory').empty();
                $('#subcategory').append('<option value="">Subcategory</option>');
            }
        });

    });


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

    var $range = $(".js-range-slider"),
        $from = $(".from"),
        $to = $(".to"),
        range,
        min = $range.data('min'),
        max = $range.data('max'),
        from,
        to;

    var updateValues = function() {
        $from.prop("value", from);
        $to.prop("value", to);
    };

    $range.ionRangeSlider({
        onChange: function(data) {
            from = data.from;
            to = data.to;
            updateValues();
        }
    });

    range = $range.data("ionRangeSlider");
    var updateRange = function() {
        range.update({
            from: from,
            to: to
        });
    };

    $from.on("input", function() {
        from = +$(this).prop("value");
        if (from < min) {
            from = min;
        }
        if (from > to) {
            from = to;
        }
        updateValues();
        updateRange();
    });

    $to.on("input", function() {
        to = +$(this).prop("value");
        if (to > max) {
            to = max;
        }
        if (to < from) {
            to = from;
        }
        updateValues();
        updateRange();
    });

    $(document).ready(function() {

        $(function() {
            var previewImages = function(input, imgPreviewPlaceholder) {
                if (input.files) {
                    var filesAmount = input.files.length;
                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var element =
                                '<div class="upload-img-box"><img src="' + event.target.result +
                                '" alt="img"> <div class = "upload-img-cross" > <i class = "fa-regular fa-circle-xmark remove_uploaded"></i></div></div>';
                            // console.log(element);
                            jQuery(element).appendTo(imgPreviewPlaceholder);
                        }
                        reader.readAsDataURL(input.files[i]);
                    }

                    var noimage = $('#upload-image').attr('upload-image-count');
                    totalimg = parseInt(noimage) + filesAmount;
                    $('#upload-image').attr('upload-image-count', totalimg)
                }
            };
            $('#upload-image').on('change', function() {
                previewImages(this, 'div.upload-img-preview');
            });

            $(document).on('click', '.remove_uploaded', function() {
                totalimage = $('#upload-image').attr('upload-image-count');
                remaningimg = parseInt(totalimage) - 1;
                totalimage = $('#upload-image').attr('upload-image-count', remaningimg);

                $(this).parent('div').parent('div').remove();
            });

        });
    });
</script>
