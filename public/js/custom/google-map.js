function initAutocomplete(elem) {
    let autocomplete;
    //Autocomplete location
    autocomplete = new google.maps.places.Autocomplete(elem);
    autocomplete.addListener("place_changed", () => {
        let place = autocomplete.getPlace();
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
    });
    
}
