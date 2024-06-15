<div class="formfield">
    <label for="text">Pickup Location<span class="text-danger">*</span></label>
    <div class="map-location form-group">
        <div class="row">
            <div class="col-md-12 form-group">
                <label>Google Map Location</label>
                <span class="addlocation float-right add-more-location">
                    <span><i class="fas fa-plus"></i></span> Add Location
                </span>
                <input type="text" name="locations[value][]" class="form-control location-required" value="{{ count($product->locations) > 0 ? $product->locations[0]->map_address : '' }}" @if(count($product->locations) > 0) data-lat="{{ $product->locations[0]->latitude }}" data-lng="{{ $product->locations[0]->longitude }}" @endif placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this)">
                <input type="hidden" class="hidden-location location-country" name="locations[country][]" value="{{ old('locations.country.0', @$product->locations[0]->country) }}">
                <input type="hidden" class="hidden-location location-state" name="locations[state][]" value="{{ old('locations.state.0', @$product->locations[0]->state) }}">
                <input type="hidden" class="hidden-location location-city" name="locations[city][]" value="{{ old('locations.city.0', @$product->locations[0]->city) }}">
                <input type="hidden" class="hidden-location location-latitude" name="locations[latitude][]" value="{{ old('locations.latitude.0', @$product->locations[0]->latitude) }}">
                <input type="hidden" class="hidden-location location-longitude" name="locations[longitude][]" value="{{ old('locations.longitude.0', @$product->locations[0]->longitude) }}">
                <input type="hidden" class="hidden-location location-postal-code" name="locations[postal_code][]" value="{{ old('locations.postal_code.0', @$product->locations[0]->postcode) }}">
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
                <input type="text" name="locations[custom][]" class="form-control location-custom-required" value="{{ count($product->locations) > 0 ? $product->locations[0]->custom_address : '' }}" placeholder="{{ __('product.placeholders.customLocation') }}">
                @error('locations.custom.0')
                <label class="error-messages">{{ $message }}</label>
                @enderror
            </div>
        </div>
        <div class="append-location">
            @for($i = 1; $i < old('location_count', $product->locations->count()); $i++)
                <div class="locat-bg-box p-relative cp-location">
                    <span class="cross-icon remove-location"><i class="fas fa-times"></i></span>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 form-group">
                            <label>Google Map Location </label>
                            <input type="text" name="locations[value][]" class="form-control location-required" placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this)" value="{{ old('locations.value.'.$i, @$product->locations[$i]->map_address) }}" data-lat="{{ old('locations.latitude.'.$i, @$product->locations[$i]->latitude) }}" data-lng="{{ old('locations.longitude.'.$i, @$product->locations[$i]->longitude) }}">
                            <input type="hidden" class="hidden-location location-country" name="locations[country][]" value="{{ old('locations.country.'.$i, @$product->locations[$i]->country) }}">
                            <input type="hidden" class="hidden-location location-state" name="locations[state][]" value="{{ old('locations.state.'.$i, @$product->locations[$i]->state) }}">
                            <input type="hidden" class="hidden-location location-city" name="locations[city][]" value="{{ old('locations.city.'.$i, @$product->locations[$i]->city) }}">
                            <input type="hidden" class="hidden-location location-latitude" name="locations[latitude][]" value="{{ old('locations.latitude.'.$i, @$product->locations[$i]->latitude) }}">
                            <input type="hidden" class="hidden-location location-longitude" name="locations[longitude][]" value="{{ old('locations.longitude.'.$i, @$product->locations[$i]->longitude) }}">
                            <input type="hidden" class="hidden-location location-postal-code" name="locations[postal_code][]" value="{{ old('locations.postal_code.'.$i, @$product->locations[$i]->postcode) }}">
                            @if($errors->has('locations.value.'.$i))
                            <label class="error-messages">{{ $errors->first('locations.value.'.$i) }}</label>
                            @elseif($errors->has('locations.latitude.'.$i))
                            <label class="error-messages">{{ $errors->first('locations.latitude.'.$i) }}</label>
                            @elseif($errors->has('locations.longitude.'.$i))
                            <label class="error-messages">{{ $errors->first('locations.longitude.'.$i)}}</label>
                            @endif
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 ">
                            <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span></label>
                            <input type="text" name="locations[custom][]" class="form-control location-custom-required" value="{{ old('locations.custom.'.$i, @$product->locations[$i]->custom_address) }}" placeholder="{{ __('product.placeholders.customLocation') }}">
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
                        <input type="text" class="location-value form-control" placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this)">
                        <input type="hidden" class="hidden-location location-country">
                        <input type="hidden" class="hidden-location location-state">
                        <input type="hidden" class="hidden-location location-city">
                        <input type="hidden" class="hidden-location location-latitude">
                        <input type="hidden" class="hidden-location location-longitude">
                        <input type="hidden" class="hidden-location location-postal-code">
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 ">
                        <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span></label>
                        <input type="text" class="location-custom form-control" placeholder="{{ __('product.placeholders.customLocation') }}">
                    </div>
                </div>
            </div>
        </div>
        <!-- End of clone location container -->
    </div>
</div>