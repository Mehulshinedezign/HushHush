@extends('layouts.customer')

@section('title', 'Checkout')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/owl.theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/owl-carousal.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}"/>
    <script>
        let selectedLocation = '{{ $selectedLocation }}';
        @if(!is_null($product->nonAvailableDates))
            var nonAvailableDates = @php echo json_encode($product->nonAvailableDates->pluck('to_date','from_date')->toArray()); @endphp;
        @endif

        // dateOptions["minDate"] = new Date();
        // dateOptions["isInvalidDate"] = function(date) {
        //     if(typeof nonAvailableDates !== "undefined" && typeof nonAvailableDates !== null){
        //         keys = Object.keys(nonAvailableDates);
        //         values = Object.values(nonAvailableDates);
                
        //         for(var i = 0; i < keys.length; i++) {
        //             if(date.format('YYYY-MM-DD') >= keys[i] && date.format('YYYY-MM-DD') <= values[i] ){
        //                 return true;
        //             }
        //         }
        //     }
        // };



        var calendarFormat = dateFormat;
    @if($product->rentaltype == 'Hour')
        dateOptions["timePicker"] = true;
        dateOptions["timePickerIncrement"] = calendarTimeGap;
        calendarFormat = dateTimeFormat;
    @else
        dateOptions["minDate"] = new Date();
    // dateOptions["isInvalidDate"] = function(date) {
    //     if (typeof nonAvailableDates !== "undefined" && typeof nonAvailableDates !== null) {
        //         keys = Object.keys(nonAvailableDates);
        //         values = Object.values(nonAvailableDates);
        
        //         for (var i = 0; i < keys.length; i++) {
            //             if (date.format('YYYY-MM-DD') >= keys[i] && date.format('YYYY-MM-DD') <= values[i]) {
                //                 return true;
                //             }
                //         }
    //     }
    // };   
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
            <x-alert/>
            <div class="custom-columns">
                <div class="left-side-content pl-0 ">
                    <div class="product-detail-pic">
                        <img src="{{ @$product->thumbnailImage->url }}" alt="product image"/>
                    </div>
                    <div class="product-detail-desc">
                        <div class="product-detail-title">
                            <h4 class="w-600">{{ $product->name }}</h4>
                            @if (@auth()->user()->role->name == 'customer')
                                @if(!is_null($product->favorites))
                                    <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box active"><i class="fas fa-heart"></i></span></div>
                                @else
                                    <div class="wishlist" onclick="addToWishlist(this, {{ $product->id }})"><span class="icon-box"><i class="far fa-heart"></i></span></div>
                                @endif
                            @endif
                        </div>
                        <p>{!! $product->description !!}</p>
                    </div>
                    <div class="product-detail-price-review">
                        <div class="product-detail-price">
                            <h5 class="mb-0 black-text">${{ $product->rent }} <small>/ {{ $product->rentaltype }}</small></h5>
                            <p class="smallFont black-text"><input type="radio" name="security_option" id="security" class="form-control security-option" value="security" @if(request()->get('option') == 'security') checked @endif><label for="security">{{ __('product.security') }}: ${{ $security }} <span class="grey-text">( {{ __('product.refundable') }} )</span></label><span class="icon-box small-icon ml-1" data-toggle="tooltip" data-placement="right" title="Security is refundable once you returned the product."><i class="fas fa-info"></i></span></p>
                            <p class="smallFont black-text"><input type="radio" name="security_option" id="insurance" class="form-control security-option" value="insurance" @if(request()->get('option') == 'insurance') checked @endif><label for="insurance">Insurance: ${{ $insurance }} <span class="grey-text">( non-refundable )</span></label><span class="icon-box small-icon ml-1" data-toggle="tooltip" data-placement="right" title="Insurance will cover your damage and it is non-refundable."><i class="fas fa-info"></i></span></p>
                        </div>
                        <div class="product-rating">
                            <div class="star-ratings">
                                <div class="fill-ratings" style="width: {{ $product->average_rating * 20 }}%;">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                                <div class="empty-ratings">
                                    <span class="d-flex"><i class="fas fa-star"></i><i class="fas fa-star"></i><i  class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                </div>
                            </div>
                            <small><span>{{ $product->average_rating }} </span>({{ $product->ratings()->count() }} Reviews)</small>
                        </div>
                    </div>
                </div>
                <div class="right-side-content pr-0">
                    <h4 class="w-400">Summary</h4>
                    <div class="summary-detail-head">
                        <p class="largeFont w-500 black-text m-0">Rental Details</p>
                        <a href="javascript:void(0);" class="m-0 grey-text" data-toggle="modal" data-target="#bookProductModal">Edit <span class="icon-box small-icon ml-1"><i class="fas fa-pencil-alt"></i></span></a>
                    </div>
                    <form method="post" action="{{ route('checkout', [$product->id]) }}">
                        @csrf
                        <input type="hidden" name="option" value="{{ request()->option == 'insurance' ? 'insurance' : 'security'}}">
                        <input type="hidden" name="product" value="{{ $product->id }}">
                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                        <input type="hidden" name="customer_location" value="{{ $selectedLocation }}">
                        <input type="hidden" name="reservation_date" value="{{ request()->get('reservation_date') }}">
                        <input type="hidden" name="latitude" class="form-control input-bg loc" value="{{ request()->get('latitude') }}">
                        <input type="hidden" name="longitude" class="form-control input-bg loc" value="{{ request()->get('longitude') }}">
                        <ul>
                            <li class="summary-item">
                                <span class="field">Location</span>
                                <span class="value grey-text">{{ $selectedLocation }}</span>
                            </li>
                            <li class="summary-item">
                                <span class="field">Reservation Date</span>
                                @php
                                    $reservationDate = explode($global_date_separator,request()->get('reservation_date'));
                                @endphp
                                <span class="value grey-text">{{ @$reservationDate[0] }} / {{ @$reservationDate[1] }}</span>
                            </li>
                            <li class="summary-item">
                                <span class="field">Rental Days </span>
                                <span class="value grey-text">{{ $rentalDays }} Days</span>
                            </li>
                            <li class="summary-item">
                                <span class="field">Rental Amount</span>
                                <span class="value grey-text">${{ number_format((float)($product->rent * $rentalDays), 2, '.', '') }}</span>
                            </li>
                            @if(request()->get('option') == 'insurance')
                            <li class="summary-item">
                                <span class="field">Insurance</span>
                                <span class="value grey-text">${{ $insurance }}</span>
                            </li>
                            @else
                            <li class="summary-item">
                                <span class="field">Security</span>
                                <span class="value grey-text">${{ $security }}</span>
                            </li>
                            @endif
                            <li class="summary-item">
                                <span class="field">Shipping</span>
                                <span class="value grey-text">Self Pickup</span>
                            </li>

                            <li class="summary-item">
                                <span class="field">Transaction Fee</span>
                                <span class="value grey-text">${{ $transactionFee }}</span>
                            </li>

                            <li class="summary-item summary-total">
                                <h6 class="field">Total</h6>
                                <h6 class="value grey-text">${{ $total }}</h6>
                            </li>
                            <li>
                                <button type="submit" class="btn green-btn large-btn fullwidth">Checkout</button>
                            </li>
                        </ul>
                    </form>
                </div>
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
                                    <input type="hidden" name="option" value="{{ request()->option == 'insurance' ? 'insurance' : 'security'}}">
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
                                        <label>Reservation Date</label>
                                        <input type="text" name="reservation_date" class="form-control modal-date-icon" placeholder="{{ __('product.placeholders.reservationDate') }}" autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ request()->get('reservation_date') }}">
                                        @error('reservation_date')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="selected_location" id="selectedLocation" value="{{ $selectedLocation }}">
                                    <button type="submit" id="filterProduct" class="btn blue-btn med-btn fullwidth">Book Now</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places" async defer></script>
    <script>
        @if($errors->has('latitude') || $errors->has('longitude') || $errors->has('reservation_date') || $errors->has('location'))
            jQuery('a[data-target="#bookProductModal"]').trigger('click');
        @endif

        @if(@auth()->user()->role->name == 'customer')
            const userId = {!! json_encode(auth()->user()->id) !!};
            const url = "{{ route('addfavorite') }}";
            const errorTitle = "{{ __('favorite.error') }}"
        @endif

        var currentUrl = new URL(window.location.href);
        const productLocations = {!! json_encode($product->locations) !!};
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
                currentUrl.searchParams.set("option", jQuery(this).val());
                window.location.replace(currentUrl.href)
            });
        });
    </script>
    <script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
    <script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
    <script src="{{ asset('js/custom/single-product.js') }}"></script>
@endsection