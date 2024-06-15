// jQuery('form#filterForm,  form#bookForm').on('keyup keypress', function(e) {
//     var keyCode = e.keyCode || e.which;
//     if (keyCode === 13) { 
//       e.preventDefault();

//       return false;
//     }
// });

// jQuery('input[name="location"]').on('keyup keypress',function(e) {
//     let keyCode = e.keyCode || e.which;
//     if (8 == keyCode || 46 == keyCode) {
//         if (jQuery('#bookProductModal').length) {
//             jQuery('#bookProductModal').find('input[name="latitude"]').val('');
//             jQuery('#bookProductModal').find('input[name="longitude"]').val('');
//         } else {
//             jQuery('input[name="latitude"]').val('');
//             jQuery('input[name="longitude"]').val('');
//         }
//     }
// });

// /*SHOW CURRECT LOCATION OPTION*/
// jQuery(document).on("click focus","#location",function(){
//     jQuery("#geolocate").show();
//  });

//  /*HIDE CURRENT LOCATION OPTION*/
//  jQuery(document).on("keyup keypress","#location",function(){
//     jQuery("#geolocate,#locationLoader").addClass('d-none');
//  });


//  /*GET CURRENT LOCATION FROM BROWSER*/
//  jQuery('#geolocate').on('click',function(e) {    
//     jQuery('#locationLoader').removeClass('d-none');
//     e.preventDefault();
//     if (navigator.geolocation) {
//        navigator.geolocation.getCurrentPosition(showPosition);

//         // if (position == undefined) {
//         //    alert("Please allow your location");
//         // }
//     } else {
//        alert("Geolocation is not supported by this browser.");
//     }
// });

// /*GEOCODER FUNCTION*/
// function showPosition(position) {
//     var latitude = position.coords.latitude;
//     var longitude = position.coords.longitude;

//     var geocoder = new google.maps.Geocoder();
//     var latLng = new google.maps.LatLng(latitude, longitude);

//     if (geocoder) {
//         geocoder.geocode({ 'latLng': latLng}, function (results, status) {
//             if (status == google.maps.GeocoderStatus.OK) {
//                 jQuery('#location').val(results[0].formatted_address);
//                 jQuery("#latitude").val(latitude);
//                 jQuery("#longitude").val(longitude); 
//                 jQuery('#geolocate, #locationLoader').hide();
//             } else {
//                 jQuery('#location').val('Geocoding failed: '+status);
//             }
//         }); 
//     }
// }

// var default_lat = 30.67486499999999, default_lng = 76.717968;
// const options = {
//     // componentRestrictions: { country: "in" },
//     fields: ["address_components", "geometry", "icon", "name"],
//     strictBounds: false,
//     types: ["establishment"],
// };

// function show_geolocation_error(map) {
//     console.log('unable to get current location')
    
//     return new google.maps.Marker({
//         position: { lat: default_lat, lng:  default_lng },
//         map
//     });
// }

// function initAutocomplete(elem, mapId) {
//     jQuery("#geolocate").removeClass('d-none');
//     let marker;
//     let autocomplete;
//     var lat = elem.getAttribute("data-lat");
//     var lng = elem.getAttribute("data-lng");
//     var getCurrentLocation = false;
//     if (lat == null || lat == undefined || lng == null || lng == undefined) {
//         getCurrentLocation = true
//     }

//     //load map
//     const map = new google.maps.Map(document.getElementById(mapId), {
//         center: { lat: default_lat, lng: default_lng },
//         zoom: 17,
//         mapTypeControl: false,
//     });

//     //Set current location in center
//     if (navigator.geolocation && getCurrentLocation) {
//         navigator.geolocation.getCurrentPosition(function (position) {
//             let currentLatitude = position.coords.latitude;
//             let currentLongitude = position.coords.longitude;
//             initialLocation = new google.maps.LatLng(currentLatitude, currentLongitude);
//             marker = new google.maps.Marker({
//                 position: { lat: currentLatitude, lng:  currentLongitude },
//                 map,
//             });
//             map.setCenter(initialLocation);

//         }, function() {
//             marker = show_geolocation_error(map);
//         });
//     } else if (navigator.geolocation && !getCurrentLocation) {
//         lat = parseFloat(lat)
//         lng = parseFloat(lng)
//         initialLocation = new google.maps.LatLng(lat, lng);
//         marker = new google.maps.Marker({
//             position: { lat: lat, lng:  lng },
//             map,
//         });
//         marker.setPosition(initialLocation);
//         map.setCenter(initialLocation);
//         map.setZoom(17);
//     } else {
//         marker = show_geolocation_error(map);
//     }

//     //Autocomplete location
//     jQuery("#"+mapId).css({ width: '100%', height: '100%', 'font-size': '8pt' }),
//     autocomplete = new google.maps.places.Autocomplete(elem, options);
   
//     autocomplete.addListener("place_changed", () => {
//         var place = autocomplete.getPlace();
//         if (!place.geometry || !place.geometry.location) {
//             window.alert("No details available for input: '" + place.name + "'");
        
//             return;
//         }

//         let changedlat =  place.geometry.location.lat();
//         let changedlng =  place.geometry.location.lng();
//         elem.setAttribute('data-lat', changedlat)
//         elem.setAttribute('data-lng', changedlng)

//         elem.parentElement.querySelector('.location-latitude').value = changedlat;
//         elem.parentElement.querySelector('.location-longitude').value = changedlng;

//         if (!getCurrentLocation && (lat != changedlat || lng != changedlng)) {
//             return initAutocomplete(elem);
//         }

//         for (var i = 0; i < place.address_components.length; i++) {
//             let addressComponent = place.address_components[i];
//             let addressType = addressComponent.types[0];
//             let addressComponentName = addressComponent.long_name;
            
//             // get country
//             if (addressType == "country" && elem.parentElement.getElementsByClassName('location-country').length > 0) {
//                 elem.parentElement.querySelector('.location-country').value = addressComponentName;
//             }

//             // get state
//             if (addressType == "administrative_area_level_1" && elem.parentElement.getElementsByClassName('location-state').length > 0) {
//                 elem.parentElement.querySelector('.location-state').value = addressComponentName;
//             }

//             // get city
//             if (elem.parentElement.getElementsByClassName('location-city').length > 0 && (addressType == "locality" || addressType == "administrative_area_level_2" || addressType == "colloquial_area")) {
//                 elem.parentElement.querySelector('.location-city').value = addressComponentName;
//             }

//             // get postal code
//             if (elem.parentElement.getElementsByClassName('location-postal-code').length > 0 && addressType == "postal_code") {
//                 elem.parentElement.querySelector('.location-postal-code').value = addressComponentName;
//             }
//         }

//         // If the place has a geometry, then present it on a map.
//         if (place.geometry.viewport) {
//             map.fitBounds(place.geometry.viewport);
//         } else {
//             map.setCenter(place.geometry.location);
//             map.setZoom(17);
//         }

//         marker.setPosition(place.geometry.location);
//         marker.setVisible(true);
//     });
    
// }

// /*AUTOCOMPLETE FUNCTION*/
// // let placeSearch;
// // let autocomplete;

// // autocomplete = new google.maps.places.Autocomplete(document.getElementById("autocomplete"));

// //  autocomplete.addListener('place_changed', function() {
// //     var place = autocomplete.getPlace();
// //     var address = place.formatted_address;
// //     var latitude = place.geometry.location.lat();
// //     var longitude = place.geometry.location.lng();

// //     for (const component of place.address_components) {
// //        const addressType = component.types[0];
// //        if(addressType == "locality"){
// //           jQuery("#txtCity").val(component.long_name);
// //        }
// //        if(addressType == "administrative_area_level_1"){
// //           jQuery("#txtState").val(component.long_name);
// //           jQuery("#txtStateCode").val(component.short_name);
// //        }
// //        if(addressType == "country"){
// //           jQuery("#txtCountry").val(component.long_name);
// //           jQuery("#txtCountryCode").val(component.short_name);
// //        }
// //        if(addressType == "postal_code"){
// //           jQuery("#txtZipcode").val(component.long_name);
// //        }
// //     }
// //     jQuery("#txtLat").val(latitude);
// //     jQuery("#txtLong").val(longitude);
// //  });


// // });

jQuery('form#filterForm,  form#bookForm').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();

      return false;
    }
});

jQuery(document).ready(function() {
    /*SHOW CURRECT LOCATION OPTION*/
    jQuery(document).on("click focus","#location",function(){
        jQuery("#geolocate").removeClass('d-none');
    });

    /*HIDE CURRENT LOCATION OPTION*/
    jQuery(document).on("keyup keypress","#location",function(){
        jQuery("#geolocate,#fileupload_spinner").hide();
    });


    /*GET CURRENT LOCATION FROM BROWSER*/
    jQuery('#geolocate').on('click', function(){
        jQuery('#fileupload_spinner').removeClass('d-none');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });

    /*GEOCODER FUNCTION*/
    function showPosition(position){ 
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        var geocoder = new google.maps.Geocoder();
        var latLng = new google.maps.LatLng(latitude, longitude);

        if (geocoder) {
            geocoder.geocode({ 'latLng': latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    jQuery('#location').val(results[0].formatted_address);
                    jQuery(".location-latitude").val(latitude);
                    jQuery(".location-longitude").val(longitude);
                    jQuery('#geolocate, #fileupload_spinner').addClass('d-none');
                } else {
                    jQuery('#location').val('Geocoding failed: '+status);
                }
            }); 
        }      
    } 

    /*AUTOCOMPLETE FUNCTION*/
    let autocomplete;

    autocomplete = new google.maps.places.Autocomplete(document.getElementById("location"));
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        var address = place.formatted_address;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        console.log(place, address)
        // for (const component of place.address_components) {
        //     const addressType = component.types[0];
        //     if(addressType == "locality"){
        //         jQuery("#txtCity").val(component.long_name);
        //     }
        //     if(addressType == "administrative_area_level_1"){
        //         jQuery("#txtState").val(component.long_name);
        //         jQuery("#txtStateCode").val(component.short_name);
        //     }
        //     if(addressType == "country"){
        //         jQuery("#txtCountry").val(component.long_name);
        //         jQuery("#txtCountryCode").val(component.short_name);
        //     }
        //     if(addressType == "postal_code"){
        //         jQuery("#txtZipcode").val(component.long_name);
        //     }
        // }
        jQuery(".location-latitude").val(latitude);
        jQuery(".location-longitude").val(longitude);
    });
});




// backup
jQuery('form#filterForm,  form#bookForm').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();

      return false;
    }
});

jQuery('input[name="location"]').on('keyup keypress',function(e) {
    let keyCode = e.keyCode || e.which;
    if (8 == keyCode || 46 == keyCode) {
        if (jQuery('#bookProductModal').length) {
            jQuery('#bookProductModal').find('input[name="latitude"]').val('');
            jQuery('#bookProductModal').find('input[name="longitude"]').val('');
        } else {
            jQuery('input[name="latitude"]').val('');
            jQuery('input[name="longitude"]').val('');
        }
    }
});

var default_lat = 30.67486499999999, default_lng = 76.717968;
const options = {
    // componentRestrictions: { country: "in" },
    fields: ["address_components", "geometry", "icon", "name"],
    strictBounds: false,
    types: ["establishment"],
};

function show_geolocation_error(map) {
    console.log('unable to get current location')
    
    return new google.maps.Marker({
        position: { lat: default_lat, lng:  default_lng },
        map
    });
}

function hideMap(elem) {
    // document.querySelector('.map-container').classList.add('d-none');
}

function initAutocomplete(elem, mapId) {
    // jQuery('#geolocate').removeClass('d-none');
    // document.querySelector('.map-container').classList.remove('d-none');
    let marker;
    let autocomplete;
    var lat = elem.getAttribute("data-lat");
    var lng = elem.getAttribute("data-lng");
    var getCurrentLocation = false;
    if (lat == null || lat == undefined || lng == null || lng == undefined) {
        getCurrentLocation = true
    }

    //load map
    const map = new google.maps.Map(document.getElementById(mapId), {
        center: { lat: default_lat, lng: default_lng },
        zoom: 17,
        mapTypeControl: false,
    });


    //Set current location in center
    if (navigator.geolocation && getCurrentLocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let currentLatitude = position.coords.latitude;
            let currentLongitude = position.coords.longitude;
            var geocoder = new google.maps.Geocoder();
            initialLocation = new google.maps.LatLng(currentLatitude, currentLongitude);
            // if (geocoder) {
            //     geocoder.geocode({ 'latLng': initialLocation}, function (results, status) {
            //         if (status == google.maps.GeocoderStatus.OK) {
                        // jQuery('#location').val(results[0].formatted_address);
                        // jQuery("#latitude").val(currentLatitude);
                        // jQuery("#longitude").val(currentLongitude); 
 
            //             jQuery('#geolocate, #fileupload_spinner').hide();
            //         }
            //         else {
            //             jQuery('#location').val('Geocoding failed: '+status);
            //         }
            //     }); 
            // }
            marker = new google.maps.Marker({
                position: { lat: currentLatitude, lng:  currentLongitude },
                map,
            });
            map.setCenter(initialLocation);

        }, function() {
            marker = show_geolocation_error(map);
        });
    } else if (navigator.geolocation && !getCurrentLocation) {
        lat = parseFloat(lat)
        lng = parseFloat(lng)
        initialLocation = new google.maps.LatLng(lat, lng);
        marker = new google.maps.Marker({
            position: { lat: lat, lng:  lng },
            map,
        });
        marker.setPosition(initialLocation);
        map.setCenter(initialLocation);
        map.setZoom(17);
    } else {
        marker = show_geolocation_error(map);
    }

    //Autocomplete location
    jQuery("#"+mapId).css({ width: '100%', height: '100%', 'font-size': '8pt' }),
    autocomplete = new google.maps.places.Autocomplete(elem, options);
   
    autocomplete.addListener("place_changed", () => {
        var place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) {
            window.alert("No details available for input: '" + place.name + "'");
        
            return;
        }

        let changedlat =  place.geometry.location.lat();
        let changedlng =  place.geometry.location.lng();
        elem.setAttribute('data-lat', changedlat)
        elem.setAttribute('data-lng', changedlng)

        elem.parentElement.querySelector('.location-latitude').value = changedlat;
        elem.parentElement.querySelector('.location-longitude').value = changedlng;

        if (!getCurrentLocation && (lat != changedlat || lng != changedlng)) {
            return initAutocomplete(elem);
        }

        for (var i = 0; i < place.address_components.length; i++) {
            let addressComponent = place.address_components[i];
            let addressType = addressComponent.types[0];
            let addressComponentName = addressComponent.long_name;
            
            // get country
            if (addressType == "country" && elem.parentElement.getElementsByClassName('location-country').length > 0) {
                elem.parentElement.querySelector('.location-country').value = addressComponentName;
            }

            // get state
            if (addressType == "administrative_area_level_1" && elem.parentElement.getElementsByClassName('location-state').length > 0) {
                elem.parentElement.querySelector('.location-state').value = addressComponentName;
            }

            // get city
            if (elem.parentElement.getElementsByClassName('location-city').length > 0 && (addressType == "locality" || addressType == "administrative_area_level_2" || addressType == "colloquial_area")) {
                elem.parentElement.querySelector('.location-city').value = addressComponentName;
            }

            // get postal code
            if (elem.parentElement.getElementsByClassName('location-postal-code').length > 0 && addressType == "postal_code") {
                elem.parentElement.querySelector('.location-postal-code').value = addressComponentName;
            }
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    });
    
}


/*
@extends('layouts.retailer')

@section('title', 'Add Product')

@section('links')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <script src="https://cdn.tiny.cloud/1/06agag1yx3npks6ywk9719m46fcigbm4k2wed3dwy99ul721/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
        });
        dateOptions["minDate"] = new Date();
    </script>
@stop

@section('content')

    <section class="section-space add-product-sec">
        <div class="innerbox-container">
            <h5 class="order-heading mb-0">Add Product</h5>
            <hr class="h-border">
            <form action="{{route('retailer.saveproduct')}}" method="post" id="addProduct" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <input type="hidden" name="location_count" value="{{ old('location_count', 1) }}">
                <input type="hidden" name="non_available_date_count" value="{{ old('non_available_date_count', 1) }}">
                <div class="add-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control required" value="{{ old('name') }}" placeholder="{{ __('product.placeholders.productName') }}">
                                    @error('name')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="cus-multi-select">
                                        <label>Category</label>
                                        <select id="category" name="category" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if(old('category') == $category->id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label>{{ __('product.fields.description') }}</label>
                                    <textarea name="description" id="description" class="required">{{ old('description') }}</textarea>
                                    @error('description')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Rent <small>(per day)</small></label>
                                    <input type="text" name="rent" class="form-control amount-limit" value="{{ old('rent') }}" placeholder="{{ __('product.placeholders.rentPerDay') }}">
                                    @error('rent')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Product Estimated Value</label>
                                    <input type="text" name="price" class="form-control amount-limit" value="{{ old('price') }}" placeholder="{{ __('product.placeholders.productPrice') }}">
                                    @error('price')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="">
                                        <h6 class="largeFont w-600 mb-3">Upload Product Images<small class=" smallFont w-400 grey-text"> (Allowed File Extensions: {{ $global_php_image_extension }})</small></h6>
                                        <div class="product-pic-gallery gallery-upload">
                                            <div class="multi-file-upload">
                                                @for($i = 1; $i <= $global_max_product_image_count; $i++)
                                                    <input type="file" name="image{{ $i }}" class="product-images upload-pending {{ $i == 1 ? '' : 'hidden' }}" data-order="{{ $i }}" accept="{{ str_replace(["[", "'", "]"],["","",""], $global_js_image_extension) }}">
                                                @endfor
                                                <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi"> </span>
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">Select File to upload...</span> 
                                                    <span class="d-block smallFont">(Min upload: {{ $global_min_product_image_count }}, Max upload: {{ $global_max_product_image_count }}, Max file size: {{ $global_php_file_size / 1000 }}MB)</span>
                                                    <span class="uploaded-file-name"></span>
                                                </p>
                                            </div>
                                            <input type="hidden" name="uploaded_image" id="uploadedImage">
                                            @for($i = 1; $i <= $global_max_product_image_count; $i++)
                                                @if($errors->has('image.'.$i))
                                                    <label class="error-messages">{{ $errors->first('image.'.$i) }}</label>
                                                @endif 
                                            @endfor

                                            @error('error')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror

                                            @error('thumbnail_image')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                            <ul class="product-img-preview"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <p class="largeFont black-text w-500 mb-2">Location</p>
                            <div class="map-location form-group">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Google Map Location</label>
                                        <span class="addlocation float-right add-more-location green-text"> <span><i class="fas fa-plus"></i></span> Add Location</span>
                                        <input type="text" name="locations[value][]" class="form-control location-required" value="{{ old('locations.value.0') }}" @if(old('locations.longitude.0')) data-lat="old('locations.latitude.0')" data-lng="old('locations.longitude.0')" @endif placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this, 'map')">
                                        <input type="hidden" class="hidden-location location-country" name="locations[country][]" value="{{ old('locations.country.0') }}">
                                        <input type="hidden" class="hidden-location location-state" name="locations[state][]" value="{{ old('locations.state.0') }}">
                                        <input type="hidden" class="hidden-location location-city" name="locations[city][]" value="{{ old('locations.city.0') }}">
                                        <input type="hidden" class="hidden-location location-latitude" name="locations[latitude][]" value="{{ old('locations.latitude.0') }}">
                                        <input type="hidden" class="hidden-location location-longitude" name="locations[longitude][]" value="{{ old('locations.longitude.0') }}">
                                        <input type="hidden" class="hidden-location location-postal-code" name="locations[postal_code][]" value="{{ old('locations.postal_code.0') }}">
                                        @if($errors->has('locations.value.0'))
                                            <label class="error-messages">{{ $errors->first('locations.value.0') }}</label>
                                        @elseif($errors->has('locations.latitude.0'))
                                            <label class="error-messages">{{ $errors->first('locations.latitude.0') }}</label>
                                        @elseif($errors->has('locations.longitude.0'))
                                            <label class="error-messages">{{ $errors->first('locations.longitude.0') }}</label>
                                        @endif   
                                    </div>
                                    <div class="col-md-12 form-group d-none">
                                        <div class="map-imgbox">
                                            <div id="map"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 form-group">
                                        <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span> </label>
                                        <input type="text" name="locations[custom][]" class="form-control location-custom-required" value="{{ old('locations.custom.0') }}" placeholder="{{ __('product.placeholders.customLocation') }}">
                                        @error('locations.custom.0')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror 
                                    </div>
                                </div>
                                <div class="append-location">
                                    @for($i = 1; $i < old('location_count', 1); $i++)
                                    <div class="locat-bg-box p-relative">
                                        <span class="cross-icon remove-location"><i class="fas fa-times"></i></span>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 form-group">
                                                <label>Google Map Location </label>
                                                <input type="text" name="locations[value][]" class="form-control location-required" value="{{ old('locations.value.'.$i) }}" @if(old('locations.longitude.'.$i)) data-lat="old('locations.latitude.'.$i)" data-lng="old('locations.longitude.'.$i)" @endif placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this, 'map')">
                                                <input type="hidden" class="hidden-location location-country" name="locations[country][]" value="{{ old('locations.country.'.$i) }}">
                                                <input type="hidden" class="hidden-location location-state" name="locations[state][]" value="{{ old('locations.state.'.$i) }}">
                                                <input type="hidden" class="hidden-location location-city" name="locations[city][]" value="{{ old('locations.city.'.$i) }}">
                                                <input type="hidden" class="hidden-location location-latitude" name="locations[latitude][]" value="{{ old('locations.latitude.'.$i) }}">
                                                <input type="hidden" class="hidden-location location-longitude" name="locations[longitude][]" value="{{ old('locations.longitude.'.$i) }}">
                                                <input type="hidden" class="hidden-location location-postal-code" name="locations[postal_code][]" value="{{ old('locations.postal_code.'.$i) }}">
                                                @if($errors->has('locations.value.'.$i))
                                                    <label class="error-messages">{{ $errors->first('locations.value.'.$i) }}</label>
                                                @elseif($errors->has('locations.latitude.'.$i))
                                                    <label class="error-messages">{{ $errors->first('locations.latitude.'.$i) }}</label>
                                                @elseif($errors->has('locations.longitude.'.$i))
                                                    <label class="error-messages">{{ $errors->first('locations.longitude.'.$i) }}</label>
                                                @endif
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 ">
                                                <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span> </label>
                                                <input type="text" name="locations[custom][]" class="form-control location-custom-required" value="{{ old('locations.custom.'.$i) }}" placeholder="{{ __('product.placeholders.customLocation') }}">                                        
                                                @error('locations.custom.'.$i)
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror   
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                                <!-- Clone location container -->
                                <div class="clone-location-container hidden cp-location">
                                    <div class="locat-bg-box p-relative">
                                        <span class="cross-icon remove-location"><i class="fas fa-times"></i></span>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 form-group">
                                                <label>Google Map Location </label>
                                                <input type="text" class="location-value form-control" placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this, 'map')">
                                                <input type="hidden" class="hidden-location location-country">
                                                <input type="hidden" class="hidden-location location-state">
                                                <input type="hidden" class="hidden-location location-city">
                                                <input type="hidden" class="hidden-location location-latitude">
                                                <input type="hidden" class="hidden-location location-longitude">
                                                <input type="hidden" class="hidden-location location-postal-code">
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 ">
                                                <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span> </label>
                                                <input type="text" class="location-custom form-control" placeholder="{{ __('product.placeholders.customLocation') }}">  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of clone location container -->
                            </div>
                            <div class="row align-items-end">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <label>Non Availability Date <span class="smallFont grey-text">(If any)</span> </label>
                                    <span class="add-more-daterangepicker addlocation adddate float-right green-text"> <span><i class="fas fa-plus"></i></span> Add</span>
                                    <input type="text" name="non_availabile_dates[0]" class="input-bg mb-3 date-icon form-control non-availability" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ old('non_availabile_dates.0') }}">
                                </div>
                                <div class="append-non-available-dates col-md-12">
                                    @for($i = 1; $i < old('non_available_date_count', 1); $i++)
                                        <div class="">
                                            <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span> Remove</span>
                                            <input type="text" name="non_availabile_dates[{{ $i }}]" class="form-control input-bg mb-3 date-icon non-availability" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ old('non_availabile_dates.' . $i) }}">
                                            @error('non_availabile_dates.'.$i)
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror  
                                        </div>
                                    @endfor
                                </div>
                                <div class="clone-non-available-date-container hidden cp-unavailabilities">
                                    <div class="">
                                        <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span> Remove</span>
                                        <input type="text" class="form-control input-bg mb-3 date-icon" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" @if(old('status', 'Active') == 'Active') checked @endif>
                                        <label class="custom-control-label" for="status">Status</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                    <button type="submit" class="btn blue-btn large-btn fullwidth">Save Details</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places" async></script>
    <script src="{{ asset('js/custom/google-map.js') }}"></script>
    <script src="{{ asset('js/custom/product-add-edit.js') }}"></script>
@stop

*/