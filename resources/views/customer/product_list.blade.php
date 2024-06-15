@extends('layouts.customer')

@section('title', 'Products')

@section('links')
<script>
    dateOptions["minDate"] = new Date();
    let selectedLocation = '{{ $selectedLocation }}';
</script>
@stop

@section('content')
<section class="small-banner product-banner">
    <div class="conatiner">
        <div class="banner-content">
            <h3 class="banner-title white-text w-600 m-0">{{ $global_home_page_title }}</h3>
        </div>
    </div>
</section>

<section class="product-listing-outer section-space">
    <div class="container">
        <x-alert />
        <div class="filter-box">
            <form class="filter-form" id="filterForm" autocomplete="off">
                <div class="row align-items-end">
                    <div class="col-12 col-sm-12 col-md-4 form-group">
                        <label>{{ __('product.fields.location') }}</label>
                        <input type="text" placeholder="{{ __('product.placeholders.location') }}" name="location" id="location" class="form-control input-bg loc" value="{{ $selectedLocation }}" @if(!empty(request()->get('latitude'))) data-lat="{{ request()->get('latitude') }}" @endif @if(!empty(request()->get('longitude'))) data-lng="{{ request()->get('longitude') }}" @endif placeholder="{{ __('product.placeholders.location') }}">
                        <input type="hidden" class="hidden-location location-latitude" name="latitude" id="latitude" value="{{ request()->get('latitude') }}">
                        <input type="hidden" class="hidden-location location-longitude" name="longitude" id="longitude" value="{{ request()->get('longitude') }}">
                        <span class="location-prompt underline d-none" id="geolocate" role="button"><i class="fa fa-map-marker-alt"></i>Use my current location</span>
                        <input type="hidden" name="selected_location" id="selectedLocation" value="{{ $selectedLocation }}">
                        <img src="{{ asset('img/location-spinner.svg') }}" id="locationLoader" class="d-none" alt="loader">
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 form-group">
                        <label>{{ __('product.reservationDate') }}</label>
                        <input type="text" name="reservation_date" id="reservation_date" placeholder="{{ __('product.placeholders.reservationDate') }}" class="form-control input-bg date-icon" onfocus="initDateRangePicker(this, dateOptions)" value="{{ request()->get('reservation_date') }}" />
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 form-group">
                        <div class="sortBy">
                            <button type="submit" id="filterProduct" class="btn blue-btn large-btn fullwidth"><span class="d-inline"><img src="{{ asset('img/filter.svg') }}" alt="filter icon"></span> Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="product-listing-box">
            <div class="product-sidebar">
                <div class="sticky">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6 class="w-600 m-0">{{ __('product.category') }}</h6>
                            <span class="sidebar-toggle" data-toggle="collapse" data-target="#type"></span>
                        </div>
                        <div id="type" class="collapse show">
                            <div class="sidebar-card-body">
                                <ul class="product-type-list">
                                    @foreach($categories as $index => $category)
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input product-category" value="{{ $category->id }}" id="category{{$category->id}}" name="category[{{ $index }}]" form="filterForm" @if(in_array($category->id, $filters['categories'])) checked @endif>
                                            <label class="custom-control-label" for="category{{$category->id}}">{{ $category->name }}</label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6 class="w-600 m-0">{{ __('product.price') }}</h6>
                            <span class="sidebar-toggle" data-toggle="collapse" data-target="#price"></span>
                        </div>

                        <div id="price" class="collapse show">
                            <div class="sidebar-card-body">
                                <div class="range-slider">
                                    <input class="range-slider__range" type="range" value="{{ request()->get('rent') ? request()->get('rent') : $filters['maxprice'] }}" min="{{ $filters['minprice'] }}" max="{{ $filters['maxprice'] }}" name="rent" @if(request()->get('rent')) form="filterForm" @endif>
                                    <p class="text-right black-text medFont w-600">$<span class="range-slider__value">{{ request()->get('rent') ? number_format((float)request()->get('rent'), 2, '.', '') : $filters['maxprice'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6 class="w-600 m-0">Rating</h6>
                            <span class="sidebar-toggle" data-toggle="collapse" data-target="#rating"></span>
                        </div>
                        <div id="rating" class="collapse show">
                            <div class="sidebar-card-body">
                                <ul class="rating-list">
                                    @for($i = 4; $i > 0; $i--)
                                    <li>
                                        <div class="custom-control custom-checkbox px-0">
                                            <input type="checkbox" class="custom-control-input filter-product-rating" id="rating{{ $i }}" value="{{ $i }}" name="rating" form="filterForm" @if(request()->get('rating') == $i) checked @endif>
                                            <label class="custom-control-label px-0" for="rating{{ $i }}">
                                                <ul class="star-rating">
                                                    @for($filledStar = 1; $filledStar <= $i; $filledStar++) <li class="star filled"><i class="fas fa-star"></i>
                                    </li>
                                    @endfor

                                    @for($nonFilledStar = 1; $nonFilledStar <= (5 - $i); $nonFilledStar++) <li class="star"><i class="fas fa-star"></i></li>
                                        @endfor
                                        <li class="rating-text"><span class="black-text">& up (<span>{{ $filters['star'.$i] }}</span>)</span></li>
                                </ul>
                                </label>
                            </div>
                            </li>
                            @endfor
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h6 class="w-600 m-0">{{ __('Day/Hour') }}</h6>
                        <span class="sidebar-toggle" data-toggle="collapse" data-target="#dayHour"></span>
                    </div>

                    <div id="dayHour" class="collapse show">
                        <div class="sidebar-card-body">
                            <ul class="product-rental-type-list">
                                <li>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input product-rental-type" value="day" id="day" name="day" form="filterForm" @if(request()->has('rental_type') && in_array('day', $filters['rental_type'])) checked @endif>
                                        <label class="custom-control-label" for="day">Day</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input product-rental-type" value="hour" id="hour" name="hour" form="filterForm" @if(request()->has('rental_type') && in_array('hour', $filters['rental_type'])) checked @endif>
                                        <label class="custom-control-label" for="hour">Hour</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-listing-content">
            <div class="product-list-header">
                <p class="w-600 medFont black-text m-0">
                    @if($products->firstItem()>0)
                    Displaying <span> {{ $products->firstItem() }} to {{ $products->lastItem() }} </span> of <span>{{ $products->total() 
                         }} </span> Products
                    @else
                    No products found.
                    @endif
                </p>
                <div class="sortBy d-flex">
                    <span class="black-text w-600 medFont">Sort By :</span>
                    <select class="form-control select sort-select" name="sort_by" @if(request()->get('sort_by')) form="filterForm" @endif">
                        <option value=""><small>Select Sort By</small></option>
                        <option value="price_asc" @if(request()->get('sort_by') == 'price_asc') selected @endif>Price <small>(Low to High)</small></option>
                        <option value="price_desc" @if(request()->get('sort_by') == 'price_desc') selected @endif>Price <small>(High to Low)</small></option>
                        <option value="rating_asc" @if(request()->get('sort_by') == 'rating_asc') selected @endif>Rating <small>(Low to High)</small></option>
                        <option value="rating_desc" @if(request()->get('sort_by') == 'rating_desc') selected @endif>Rating <small>(High to Low)</small></option>
                    </select>
                </div>
            </div>

            @php $bookNow = url('product') .'/%d/view?' @endphp

            @if(!is_null(request()->get('reservation_date')))
            @php
            $bookNow .= 'reservation_date=' . request()->get('reservation_date') . '&';
            @endphp
            @endif

            @if(!is_null(request()->get('location')) && !is_null(request()->get('latitude')) && !is_null(request()->get('longitude')))
            @php
            $bookNow .= 'location=' . request()->get('location') . '&latitude='. request()->get('latitude') . '&longitude='. request()->get('longitude');
            @endphp
            @endif

            @php
            $temp = substr($bookNow, -1);
            if ($temp == '&' || $temp == '?') {
            $bookNow = str_replace($temp, '', $bookNow);
            }
            @endphp

            <div class="product-listing">
                <div class="row">
                    @foreach($products as $i => $product)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 p-0 col-margin">
                        <div class="product-card">
                            <div class="product-card-img">
                                <a href="{{ sprintf($bookNow, $product->id) }}">
                                    @if(isset($product->thumbnailImage->url))
                                    <img src="{{ $product->thumbnailImage->url }}">
                                    @else
                                    <img src="{{ asset('img/default-product.png') }}">
                                    @endif
                                </a>
                            </div>
                            <p class="product-card-name"><a href="{{ sprintf($bookNow, $product->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $product->name }}">{{ $product->name }}</a></p>
                            <div class="product-reviews">
                                <div class="product-price">
                                    <p class="largeFont mb-0 black-text">
                                        ${{ $product->rent }}
                                    </p>
                                    @if($product->rentaltype == 'Day')
                                    <small>(Per Day)</small>
                                    @else
                                    <small>(Per Hour)</small>
                                    @endif
                                </div>
                                <div class="product-rating">
                                    <div class="star-ratings">
                                        <div class="fill-ratings" style="width: {{ $product->average_rating * 20 }}%;">
                                            <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                        </div>
                                        <div class="empty-ratings">
                                            <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                        </div>
                                    </div>
                                    <small><span>{{ $product->average_rating }} </span>({{ $product->ratings()->count() }} Reviews)</small>
                                </div>
                            </div>

                            <a href="{{ sprintf($bookNow, $product->id) }}" class="btn blue-btn fullwidth blue-outline">{{ __('buttons.bookNow') }}</a>

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
                </div>
            </div>
            {{ $products->appends($_GET)->links('pagination::product-list') }}
        </div>
    </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places"></script>
<script src="{{ asset('js/custom/product-list.js') }}"></script>
<script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
@if(@auth()->user()->role->name == 'customer')
<script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
<script>
    const userId = {!!json_encode(auth() -> user() -> id) !!};
    const url = "{{ route('addfavorite') }}";
    const errorTitle = "{{ __('favorite.error') }}"
</script>
@endif
@stop