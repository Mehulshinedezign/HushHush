@extends('layouts.customer')

@section('title', 'Product Detail')

@section('links')
<link rel="stylesheet" href="{{ asset('css/owl.theme.css') }}" />
<link rel="stylesheet" href="{{ asset('css/owl-carousal.css') }}" />
<link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}" />
<link rel="stylesheet" href="{{ asset('css/slick.css') }}" />
<script>
    let selectedLocation = '{{ $selectedLocation }}';
    @if(!is_null($product->nonAvailableDates))
        var nonAvailableDates = @php echo json_encode($product->nonAvailableDates->pluck('to_date', 'from_date')->toArray()); @endphp;
    @endif

    var calendarFormat = dateFormat;
    @if($product->rentaltype == 'Hour')
        dateOptions["timePicker"] = true;
        dateOptions["timePickerIncrement"] = calendarTimeGap;
        calendarFormat = dateTimeFormat;
    @else
        dateOptions["minDate"] = new Date();
    @endif
    dateOptions.locale["format"] = calendarFormat;    
    dateOptions["isInvalidDate"] = function(date) {
        if (typeof nonAvailableDates !== "undefined" && typeof nonAvailableDates !== null) {
            keys = Object.keys(nonAvailableDates);
            values = Object.values(nonAvailableDates);
            for (var i = 0; i < keys.length; i++) {
                if (date.format(calendarFormat) >= keys[i] && date.format(calendarFormat) <= values[i]) {
                    return true;
                }
            }
        }
    };
</script>
@stop

@section('content')
<section class="checkout-section">
    <div class="container">
        <x-alert />
        <div class="custom-columns">
            <div class="left-side-content pl-0 pr-0  border-0">
                <div class="product-detail-slider">
                    <div class="product-big-slider">
                        <div><img src="{{ @$product->thumbnailImage->url }}" alt="product image" /></div>
                        @foreach($product->images as $image)
                        <div><img src="{{ $image->url }}" alt="product image" /></div>
                        @endforeach
                    </div>
                    <div class="product-small-slider">
                        <div><img src="{{ @$product->thumbnailImage->url }}" alt="product image" /></div>
                        @foreach($product->images as $image)
                        <div><img src="{{ $image->url }}" alt="product image" /></div>
                        @endforeach
                    </div>
                </div>
                <div class="profile-form-outer">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#description">{{ __('product.description') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#review">{{ __('product.reviews') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="description" class="profile-tab tab-pane active">
                            <div class="product-desc-text">
                                {!! $product->description !!}
                            </div>
                        </div>
                        <div id="review" class="profile-tab tab-pane fade">
                            <div class="review-desc-text">
                                <h6>{{ __('product.reviews') }} <sup class="green-text">({{ $product->ratings()->count() }})</sup></h6>
                                @foreach($product->ratings as $rating)
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="review-author">
                                            <div class="review-imgbox">
                                                @if(isset($rating->user->profile_pic_url))
                                                <img src="{{ $rating->user->profile_pic_url }}" alt="">
                                                @else
                                                <img src="{{ asset('img/avatar-small.png') }}" alt="">
                                                @endif
                                            </div>
                                            <p class="mb-0 w-500">{{ $rating->user->name }}:</p>
                                        </div>
                                        <div class="product-rating">
                                            <div class="star-ratings">
                                                <div class="fill-ratings" style="width: {{ $rating->rating * 20 }}%;">
                                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                                </div>
                                                <div class="empty-ratings">
                                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-body">
                                        <p class="black-text">{!! $rating->review !!}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-side-content pr-0">
                <form class="filter-form" action="{{ route('book', [$product->id]) }}" method="get">
                    <input type="hidden" name="option" value="security">
                    <div class="product-detail-desc">
                        <div class="product-detail-title">
                            <h5 class="w-600">{{ $product->name }}</h5>
                            @if (@auth()->user()->role->name == 'customer')
                            @if(!is_null($product->favorites))
                            <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box active"><i class="fas fa-heart"></i></span></div>
                            @else
                            <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box"><i class="far fa-heart"></i></span></div>
                            @endif
                            @endif
                        </div>

                        <p class="">Retailer : <a href="{{ route('retailer', $product->retailer->id) }}"><span class="blue-text ml-1">{{ $product->retailer->name }}</span></a></p>
                        @if(!is_null(request()->get('reservation_date')) || (!is_null(request()->get('location')) && !is_null(request()->get('latitude')) && !is_null(request()->get('longitude'))))
                        <div class="pro-booking-info">
                            @if(!is_null(request()->get('reservation_date')))
                            <p class="product-booking-info">
                                <span>Reservation Date : </span><span class="grey-text ml-1">
                                    @php
                                    $reservationDate = explode($global_date_separator, request()->get('reservation_date'));
                                    @endphp

                                    @if(count($reservationDate) == 2)
                                    {{ $reservationDate[0] }} {{ $global_date_separator }} {{ $reservationDate[1] }}
                                    @endif
                                </span>
                            </p>
                            <input type="hidden" name="reservation_date" class="form-control" value="{{ request()->get('reservation_date') }}">
                            @endif

                            @if(!is_null(request()->get('location')) && !is_null(request()->get('latitude')) && !is_null(request()->get('longitude')))
                            <p class="product-booking-info">
                                <span>Location :</span>
                                <span class="grey-text ml-1">{{ request()->location }}</span>
                            </p>
                            <input type="hidden" name="location" class="form-control" value="{{ $selectedLocation }}">
                            <input type="hidden" name="latitude" class="form-control location-latitude" value="{{ request()->get('latitude') }}">
                            <input type="hidden" name="longitude" class="form-control location-longitude" value="{{ request()->get('longitude') }}">
                            @endif
                            <span class="change-icon" data-toggle="modal" data-target="#bookProductModal"><i class="fas fa-pencil-alt"></i></span>
                        </div>
                        @endif
                    </div>
                    <div class="product-detail-price-review ">
                        <div class="product-detail-price">
                            <h5 class="mb-0 black-text">${{ $product->rent }} <small>/ @if($product->rentaltype == 'Day'){{ __('product.day') }} @else{{ __('product.hour')}}@endif</small></h5>
                            <p class="smallFont black-text"><input type="radio" name="security_option" id="security" class="form-control security-option" value="security" checked><label for="security">{{ __('product.security') }}: ${{ $security }} <span class="grey-text">( {{ __('product.refundable') }} )</span></label><span class="icon-box small-icon ml-1" data-toggle="tooltip" data-placement="right" title="Security is refundable once you returned the product."><i class="fas fa-info"></i></span></p>
                            <p class="smallFont black-text"><input type="radio" name="security_option" id="insurance" class="form-control security-option" value="insurance"><label for="insurance">Insurance: ${{ $insurance }} <span class="grey-text">( non-refundable )</span></label><span class="icon-box small-icon ml-1" data-toggle="tooltip" data-placement="right" title="Insurance will cover your damage and it is non-refundable."><i class="fas fa-info"></i></span></p>
                            @error('option')
                            <label class="error-messages">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="product-rating ">
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: {{ $product->average_rating * 20 }}%;">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                                <div class="empty-ratings">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                            </div>

                            <small><span>{{ $product->average_rating }} </span>({{ $product->ratings()->count() }} {{ __('product.reviews') }})</small>
                        </div>
                    </div>

                    @if(!is_null(request()->get('location')) && !is_null(request()->get('latitude')) && !is_null(request()->get('longitude')) && !is_null(request()->get('reservation_date')))
                    <button type="submit" class="btn blue-btn med-btn fullwidth">Book Now</button>
                    @else
                    <a href="javascript:void(0);" class="btn blue-btn large-btn fullwidth" data-toggle="modal" data-target="#bookProductModal">{{ __('buttons.bookNow') }}</a>
                    @endif

                    <div class="product-location">
                        <p class="w-500 black-text largeFont">{{ __('product.productLocation') }} <span class="icon-box small-icon ml-1" data-toggle="tooltip" data-placement="right" title="Exact product location will be shown after booking has been done"><i class="fas fa-info"></i></span></p>
                        <div class="product-location-map" id="productLocationMap"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Related products -->
@php $bookNow = url('product') .'/%d/view?' @endphp

@if(!is_null(request()->get('reservation_date')))
@php
$bookNow .= 'reservation_date=' . request()->get('reservation_date') . '&';
@endphp
@endif

@if(!is_null(request()->get('location')) && !is_null(request()->get('latitude')) && !is_null(request()->get('longitude')))
@php
$bookNow .= 'location=' . $selectedLocation . '&latitude='. request()->get('latitude') . '&longitude='. request()->get('longitude');
@endphp
@endif

@php
$temp = substr($bookNow, -1);
if ($temp == '&' || $temp == '?') {
$bookNow = str_replace($temp, '', $bookNow);
}
@endphp
<section class="related-products">
    <div class="container">
        <h6 class="w-600">Related Products</h6>
        <div class="related-products-slider owl-carousel owl-theme">
            @foreach($relatedProducts as $relatedProduct)
            <div class="item">
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ @$relatedProduct->thumbnailImage->url }}" alt="img">
                    </div>
                    <p class="product-card-name">{{ $relatedProduct->title }}</p>
                    <div class="product-reviews">
                        <div class="product-price">
                            <p class="largeFont mb-0 black-text">${{ $relatedProduct->rent }}</p>
                            @if($relatedProduct->rentaltype == 'Day')
                            <small>(Per Day)</small>
                            @else
                            <small>(Per Hour)</small>
                            @endif
                        </div>
                        <div class="product-rating ">
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: {{ $relatedProduct->average_rating * 20 }}%;">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                                <div class="empty-ratings">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                            </div>

                            <small><span>{{ $relatedProduct->average_rating }} </span>({{ $relatedProduct->ratings()->count() }} {{ __('product.reviews') }})</small>
                        </div>
                    </div>
                    <a href="{{ sprintf($bookNow, $relatedProduct->id) }}" class="btn blue-btn fullwidth blue-outline">Book Now</a>
                    @if (@auth()->user()->role->name == 'customer')
                    @if(!is_null($relatedProduct->favorites))
                    <div class="wishlist" onclick="addToWishlist(this, {{ $relatedProduct->id }})"><span class="icon-box active"><i class="fas fa-heart"></i></span></div>
                    @else
                    <div class="wishlist" onclick="addToWishlist(this, {{ $relatedProduct->id }})"><span class="icon-box"><i class="far fa-heart"></i></span></div>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@section('scripts')
<!-- Reserve product pop up -->
<div class="modal fade book-product-modal" id="bookProductModal" tabindex="-1" role="dialog" aria-labelledby="bookProductModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-12 col-sm-5 col-md-5">
                        <div class="book-product-modal-img">
                            <img src="{{ asset('img/popup-img.svg') }}" alt="" />
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-md-7">
                        <div class="modal-body-content">
                            <div class="modal-head popup-head">
                                <h5 class="mb-0">Reserve Your Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                </button>
                            </div>
                            <form class="filter-form" action="{{ route('book', [$product->id]) }}" method="get" id="bookForm">
                                <input type="hidden" name="option" value="security">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" id="location" class="form-control input-bg loc" value="{{ $selectedLocation }}" @if(!empty(request()->get('latitude'))) data-lat="{{ request()->get('latitude') }}" @endif @if(!empty(request()->get('longitude'))) data-lng="{{ request()->get('longitude') }}" @endif placeholder="{{ __('product.placeholders.location') }}">
                                    <input type="hidden" name="latitude" id="latitude" class="form-control input-bg loc location-latitude" value="{{ request()->get('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude" class="form-control input-bg loc location-longitude" value="{{ request()->get('longitude') }}">
                                    @if($errors->has('location'))
                                    <label class="error-messages">{{ $errors->first('location') }}</label>
                                    @elseif($errors->has('latitude'))
                                    <label class="error-messages">{{ $errors->first('latitude') }}</label>
                                    @elseif($errors->has('longitude'))
                                    <label class="error-messages">{{ $errors->first('longitude') }}</label>
                                    @endif
                                    <span class="location-prompt d-none underline" id="geolocate" role="button"><i class="fa fa-map-marker-alt"></i>Use my current location</span>
                                    <img src="{{ asset('img/location-spinner.svg') }}" id="locationLoader" class="d-none" alt="loader">
                                </div>

                                <div class="form-group">
                                    <label>@if($product->rentaltype == 'Day') Reservation Date @else Reservation Date And Time @endif</label>
                                    <input type="text" name="reservation_date" class="form-control modal-date-icon" @if($product->rentaltype == 'Day') placeholder="{{ __('product.placeholders.reservationDate') }}" @else placeholder="{{ __('product.placeholders.reservationDateAndTime') }}" @endif autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ request()->get('reservation_date') }}">
                                    @error('reservation_date')
                                    <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>

                                <input type="hidden" name="selected_location" id="selectedLocation" value="{{ $selectedLocation }}">
                                <button type="submit" class="btn blue-btn med-btn fullwidth">Book Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/owl-carousal.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places"></script>
<script>
    @if($errors -> has('latitude') || $errors -> has('longitude') || $errors -> has('reservation_date') || $errors -> has('location'))
    jQuery('a[data-target="#bookProductModal"]').trigger('click');
    @endif

    @if(@auth() -> user() -> role -> name == 'customer')
    const userId = {!!json_encode(auth() -> user() -> id) !!};
    const url = "{{ route('addfavorite') }}";
    const errorTitle = "{{ __('favorite.error') }}"
    @endif

    const productLocations = {!!json_encode($product -> locations)!!};
    const reservationDateRequired = '{{ __("product.validations.reservationDateRequired") }}';
    const locationRequired = '{{ __("product.validations.locationRequired") }}';
    // get the user lat and lng
    var defaultLat = '{{ request()->get("latitude") }}';
    var defaultLng = '{{ request()->get("longitude") }}';

    if (defaultLat == '' || defaultLng == '') {
        defaultLat = productLocations[0].latitude
        defaultLng = productLocations[0].longitude
    }

    jQuery(document).ready(function() {
        jQuery('.security-option').change(function() {
            jQuery('input[name="option"]').val(jQuery(this).val());
        });
    });
</script>
<script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
<script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
<script src="{{ asset('js/custom/single-product.js') }}"></script>
@stop