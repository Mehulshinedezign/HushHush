if (undefined != selectedLocation && '' != selectedLocation) {
    var currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set("location", selectedLocation); // setting your param
    history.pushState(null, '', currentUrl.href); 
}

jQuery('form#filterForm,  form#bookForm').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
        e.preventDefault();

        return false;
    }
});

jQuery(document).ready(function() {
    /*SHOW CURRECT LOCATION OPTION*/
    jQuery(document).on("click focus","#location",function() {
        jQuery("#geolocate").removeClass('d-none');
    });

    /*HIDE CURRENT LOCATION OPTION*/
    jQuery(document).on("keyup keypress","#location",function() {
        jQuery("#geolocate, #locationLoader").addClass('d-none');
    });


    /*GET CURRENT LOCATION FROM BROWSER*/
    jQuery('#geolocate').on('click', function() {
        jQuery('#locationLoader').removeClass('d-none');
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
                    let address = results[0].formatted_address;
                    jQuery('#location').val(address);
                    jQuery('#selectedLocation').val(address);
                    jQuery("#latitude").val(latitude);
                    jQuery("#longitude").val(longitude);
                    jQuery('#geolocate, #locationLoader').addClass('d-none');
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
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();        
        jQuery("#latitude").val(latitude);
        jQuery("#longitude").val(longitude);
        jQuery('#selectedLocation').val(place.formatted_address);
    });
});