@extends('layouts.retailer')

@section('title', 'Add Product')

@section('links')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <script src="https://cdn.tiny.cloud/1/rcqfj1cr6ejxsyuriqt95tnyc64joig2nppf837i8qckzy90/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            branding: false,
            height: '500',
        });
        dateOptions["minDate"] = new Date();

        @if (old('rentaltype') == 'Hour')
            dateOptions["timePicker"] = true;
            // dateOptions["timePicker24Hour"] = true;
            dateOptions["timePickerIncrement"] = calendarTimeGap;
            dateOptions.locale["format"] = dateTimeFormat;
        @endif
    </script>
@stop

@section('content')

    <section class="section-space add-product-sec">
        <div class="innerbox-container">
            <h5 class="order-heading mb-0">Add Product</h5>
            <hr class="h-border">
            <form action="{{ route('retailer.saveproduct') }}" method="post" id="addProduct" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <input type="hidden" name="location_count" value="{{ old('location_count', 1) }}">
                <input type="hidden" name="non_available_date_count" value="{{ old('non_available_date_count', 1) }}">
                <div class="add-product">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="{{ __('product.placeholders.productName') }}">
                                    @error('name')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="cus-multi-select">
                                        <label>Category</label>
                                        <select id="category" name="category" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if (old('category') == $category->id) selected @endif>{{ $category->name }}
                                                </option>
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
                                <div class="col-md-12 form-group">
                                    <div class="cus-multi-select">
                                        <label>Rent Type</label>
                                        <select id="rentalType" name="rentaltype" class="form-control form-control-sm">
                                            <option value="Day" @if (old('rentaltype', 'Day') == 'Day') selected @endif>Day
                                            </option>
                                            <option value="Hour" @if (old('rentaltype') == 'Hour') selected @endif>Hour
                                            </option>
                                        </select>
                                        @error('rentaltype')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Rent <small>(per day)</small></label>
                                    <input type="text" name="rent" class="form-control amount-limit"
                                        value="{{ old('rent') }}"
                                        placeholder="{{ __('product.placeholders.rentPerDay') }}">
                                    @error('rent')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Product Estimated Value</label>
                                    <input type="text" name="price" class="form-control amount-limit"
                                        value="{{ old('price') }}"
                                        placeholder="{{ __('product.placeholders.productPrice') }}">
                                    @error('price')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>

                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="">
                                        <h6 class="largeFont w-600 mb-3">Upload Product Images<small
                                                class=" smallFont w-400 grey-text"> (Allowed File Extensions:
                                                {{ $global_php_image_extension }})</small></h6>
                                        <div class="product-pic-gallery gallery-upload">
                                            <div class="multi-file-upload">
                                                @for ($i = 1; $i <= $global_max_product_image_count; $i++)
                                                    <input type="file" name="image{{ $i }}"
                                                        class="product-images upload-pending {{ $i == 1 ? '' : 'hidden' }}"
                                                        data-order="{{ $i }}"
                                                        accept="{{ str_replace(['[', "'", ']'], ['', '', ''], $global_js_image_extension) }}">
                                                @endfor
                                                <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi">
                                                </span>
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">Select File to upload...</span>
                                                    <span class="d-block smallFont">(Min upload:
                                                        {{ $global_min_product_image_count }}, Max upload:
                                                        {{ $global_max_product_image_count }}, Max file size:
                                                        {{ $global_php_file_size / 1000 }}MB)</span>
                                                    <span class="uploaded-file-name"></span>
                                                </p>
                                            </div>
                                            <input type="hidden" name="uploaded_image" id="uploadedImage">
                                            @for ($i = 1; $i <= $global_max_product_image_count; $i++)
                                                @if ($errors->has('image.' . $i))
                                                    <label class="error-messages">{{ $errors->first('image.' . $i) }}</label>
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
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <p class="largeFont black-text w-500 mb-2">Location</p>
                            <div class="map-location form-group">
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Google Map Location</label>
                                        <span class="addlocation float-right add-more-location green-text"> <span><i
                                                    class="fas fa-plus"></i></span> Add Location</span>
                                        <input type="text" name="locations[value][]"
                                            class="form-control location-required" value="{{ old('locations.value.0') }}"
                                            @if (old('locations.longitude.0')) data-lat="old('locations.latitude.0')" data-lng="old('locations.longitude.0')" @endif
                                            placeholder="{{ __('product.placeholders.location') }}"
                                            onfocus="initAutocomplete(this)">
                                        <input type="hidden" class="hidden-location location-country"
                                            name="locations[country][]" value="{{ old('locations.country.0') }}">
                                        <input type="hidden" class="hidden-location location-state"
                                            name="locations[state][]" value="{{ old('locations.state.0') }}">
                                        <input type="hidden" class="hidden-location location-city"
                                            name="locations[city][]" value="{{ old('locations.city.0') }}">
                                        <input type="hidden" class="hidden-location location-latitude"
                                            name="locations[latitude][]" value="{{ old('locations.latitude.0') }}">
                                        <input type="hidden" class="hidden-location location-longitude"
                                            name="locations[longitude][]" value="{{ old('locations.longitude.0') }}">
                                        <input type="hidden" class="hidden-location location-postal-code"
                                            name="locations[postal_code][]" value="{{ old('locations.postal_code.0') }}">
                                        @if ($errors->has('locations.value.0'))
                                            <label
                                                class="error-messages">{{ $errors->first('locations.value.0') }}</label>
                                        @elseif($errors->has('locations.latitude.0'))
                                            <label
                                                class="error-messages">{{ $errors->first('locations.latitude.0') }}</label>
                                        @elseif($errors->has('locations.longitude.0'))
                                            <label
                                                class="error-messages">{{ $errors->first('locations.longitude.0') }}</label>
                                        @endif
                                    </div>
                                    <div class="col-md-12 form-group d-none">
                                        <div class="map-imgbox">
                                            <div id="map"></div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 form-group">
                                        <label>Exact Location <span class="smallFont grey-text">(as per you either
                                                available on google map or not)</span> </label>
                                        <input type="text" name="locations[custom][]"
                                            class="form-control location-custom-required"
                                            value="{{ old('locations.custom.0') }}"
                                            placeholder="{{ __('product.placeholders.customLocation') }}">
                                        @error('locations.custom.0')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="append-location">
                                    @for ($i = 1; $i < old('location_count', 1); $i++)
                                        <div class="locat-bg-box p-relative">
                                            <span class="cross-icon remove-location"><i class="fas fa-times"></i></span>
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                                    <label>Google Map Location </label>
                                                    <input type="text" name="locations[value][]"
                                                        class="form-control location-required"
                                                        value="{{ old('locations.value.' . $i) }}"
                                                        @if (old('locations.longitude.' . $i)) data-lat="old('locations.latitude.'.$i)" data-lng="old('locations.longitude.'.$i)" @endif
                                                        placeholder="{{ __('product.placeholders.location') }}"
                                                        onfocus="initAutocomplete(this)">
                                                    <input type="hidden" class="hidden-location location-country"
                                                        name="locations[country][]"
                                                        value="{{ old('locations.country.' . $i) }}">
                                                    <input type="hidden" class="hidden-location location-state"
                                                        name="locations[state][]"
                                                        value="{{ old('locations.state.' . $i) }}">
                                                    <input type="hidden" class="hidden-location location-city"
                                                        name="locations[city][]" value="{{ old('locations.city.' . $i) }}">
                                                    <input type="hidden" class="hidden-location location-latitude"
                                                        name="locations[latitude][]"
                                                        value="{{ old('locations.latitude.' . $i) }}">
                                                    <input type="hidden" class="hidden-location location-longitude"
                                                        name="locations[longitude][]"
                                                        value="{{ old('locations.longitude.' . $i) }}">
                                                    <input type="hidden" class="hidden-location location-postal-code"
                                                        name="locations[postal_code][]"
                                                        value="{{ old('locations.postal_code.' . $i) }}">
                                                    @if ($errors->has('locations.value.' . $i))
                                                        <label
                                                            class="error-messages">{{ $errors->first('locations.value.' . $i) }}</label>
                                                    @elseif($errors->has('locations.latitude.' . $i))
                                                        <label
                                                            class="error-messages">{{ $errors->first('locations.latitude.' . $i) }}</label>
                                                    @elseif($errors->has('locations.longitude.' . $i))
                                                        <label
                                                            class="error-messages">{{ $errors->first('locations.longitude.' . $i) }}</label>
                                                    @endif
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-12 ">
                                                    <label>Exact Location <span class="smallFont grey-text">(as per you
                                                            either available on google map or not)</span> </label>
                                                    <input type="text" name="locations[custom][]"
                                                        class="form-control location-custom-required"
                                                        value="{{ old('locations.custom.' . $i) }}"
                                                        placeholder="{{ __('product.placeholders.customLocation') }}">
                                                    @error('locations.custom.' . $i)
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
                                                <input type="text" class="location-value form-control"
                                                    placeholder="{{ __('product.placeholders.location') }}"
                                                    onfocus="initAutocomplete(this)">
                                                <input type="hidden" class="hidden-location location-country">
                                                <input type="hidden" class="hidden-location location-state">
                                                <input type="hidden" class="hidden-location location-city">
                                                <input type="hidden" class="hidden-location location-latitude">
                                                <input type="hidden" class="hidden-location location-longitude">
                                                <input type="hidden" class="hidden-location location-postal-code">
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 ">
                                                <label>Exact Location <span class="smallFont grey-text">(as per you either
                                                        available on google map or not)</span> </label>
                                                <input type="text" class="location-custom form-control"
                                                    placeholder="{{ __('product.placeholders.customLocation') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of clone location container -->
                            </div>
                            <div class="row align-items-end">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <label>Non Availability Date <span class="smallFont grey-text">(If any)</span> </label>
                                    <span class="add-more-daterangepicker addlocation adddate float-right green-text">
                                        <span><i class="fas fa-plus"></i></span> Add</span>
                                    <input type="text" name="non_availabile_dates[0]"
                                        class="input-bg mb-3 date-icon form-control non-availability"
                                        placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                                        autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)"
                                        value="{{ old('non_availabile_dates.0') }}">
                                    @error('non_availabile_dates.0')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="append-non-available-dates col-md-12">
                                    @for ($i = 1; $i < old('non_available_date_count', 1); $i++)
                                        <div class="">
                                            <span class="remove-daterangepicker float-right red-text"> <span><i
                                                        class="fas fa-minus"></i></span> Remove</span>
                                            <input type="text" name="non_availabile_dates[{{ $i }}]"
                                                class="form-control input-bg mb-3 date-icon non-availability"
                                                placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                                                autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)"
                                                value="{{ old('non_availabile_dates.' . $i) }}">
                                            @error('non_availabile_dates.' . $i)
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    @endfor
                                </div>
                                <div class="clone-non-available-date-container hidden cp-unavailabilities">
                                    <div class="">
                                        <span class="remove-daterangepicker float-right red-text"> <span><i
                                                    class="fas fa-minus"></i></span> Remove</span>
                                        <input type="text" class="form-control input-bg mb-3 date-icon"
                                            placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                                            onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="status"
                                            name="status" @if (old('status', 'Active') == 'Active') checked @endif>
                                        <label class="custom-control-label" for="status">Status</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                    <button type="submit" class="btn blue-btn" id="saveProduct">Save Details</button>
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
    <script>
        const rentPerDay = {
            placeholder: "{{ __('product.placeholders.rentPerDay') }}",
            small: '(per day)'
        };
        const rentPerHour = {
            placeholder: "{{ __('product.placeholders.rentPerHour') }}",
            small: '(per hour)'
        };
    </script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places">
    </script>
    <script src="{{ asset('js/custom/google-map.js') }}"></script>
    <script src="{{ asset('js/custom/product-add-edit.js') }}"></script>
@stop
