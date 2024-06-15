@extends('layouts.retailer')

@section('title', 'Edit Product')

@section('links')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<script src="https://cdn.tiny.cloud/1/rcqfj1cr6ejxsyuriqt95tnyc64joig2nppf837i8qckzy90/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        branding: false,
        height: '500',
    });

    @if($product->rentaltype == 'Hour')
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
        <h5 class="order-heading mb-0">Edit Product</h5>
        <hr class="h-border">
        <form action="{{ route('retailer.updateproduct', [$product->id]) }}" method="post" id="addProduct" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="hidden" name="location_count" value="{{ old('location_count', $product->locations->count()) }}">
            <input type="hidden" name="non_available_date_count" value="{{ old('non_available_date_count', $product->nonAvailableDates->count()) }}">
            <div class="add-product">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" placeholder="{{ __('product.placeholders.productName') }}">
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
                                        <option value="{{ $category->id }}" @if(old('category')==$category->id || $product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>{{ __('product.fields.description') }}</label>
                                <textarea name="description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                <label class="error-messages">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="cus-multi-select">
                                    <label>Rent Type</label>
                                    <select id="rentalType" name="rentaltype" class="form-control form-control-sm">
                                        <option value="Day" @if('Day'==$product->rentaltype) selected @endif>Day</option>
                                        <option value="Hour" @if('Hour'==$product->rentaltype) selected @endif>Hour</option>
                                    </select>
                                    @error('rentaltype')
                                    <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Rent <small>(per day)</small></label>
                                <input type="text" name="rent" class="form-control" value="{{ old('rent', $product->rent) }}" placeholder="{{ __('product.placeholders.rentPerDay') }}">
                                @error('rent')
                                <label class="error-messages">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Product Estimated Value</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}" placeholder="{{ __('product.placeholders.productPrice') }}">
                                @error('price')
                                <label class="error-messages">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="">
                                    <h6 class="largeFont w-600 mb-3">Upload Product Images<small class=" smallFont w-400 grey-text"> (Allowed File Extensions: .jpg, .jpeg, .png)</small></h6>
                                    <div class="product-pic-gallery gallery-upload">
                                        <div class="multi-file-upload">
                                            @for($i = 1; $i <= $global_max_product_image_count; $i++) <input type="file" name="image{{ $i }}" class="product-images @if(isset($product->allImages[$i - 1]->file)) upload-done @else upload-pending @endif {{ $product->allImages->count() + 1 == $i ? '' : 'hidden' }}" data-order="{{ $i }}" value="{{ @$product->allImages[$i - 1]->file }}" accept="{{ str_replace(["[", "'", "]"],["","",""], $global_js_image_extension) }}">
                                                @endfor
                                                <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi"> </span>
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">@if($product->allImages->count() < $global_max_product_image_count) Select File to upload... @else Upload file limit reached @endif</span>
                                                            <span class="d-block smallFont">(Min upload: {{ $global_min_product_image_count }}, Max upload: {{ $global_max_product_image_count }}, Max file size: {{ $global_php_file_size / 1000 }}MB)</span>
                                                            <span class="uploaded-file-name"></span>
                                                </p>
                                        </div>
                                        <input type="hidden" name="removed_images" id="removedImages">
                                        <input type="hidden" name="uploaded_image" id="uploadedImage">
                                        <input type="hidden" name="uploaded_image_count" id="uploadedImageCount" value="{{ $product->allImages->count() }}">
                                        @for($i = 1; $i <= $global_max_product_image_count; $i++) @if($errors->has('image.'.$i))
                                            <label class="error-messages">{{ $errors->first('image.'.$i) }}</label>
                                            @endif
                                            @endfor

                                            @error('error')
                                            <label class="error-messages">{{ $message }}</label>
                                            @enderror

                                            @error('thumbnail_image')
                                            <label class="error-messages">{{ $message }}</label>
                                            @enderror 
                                            <ul class="product-img-preview">@foreach($product->allImages as $index => $image)<li><span class="custom-radios"><input type="radio" name="thumbnail_image" class="thumbnail-img" @if($image->type == 'thumbnail') value="{{ ($index + 1) }}" checked @endif><label class="circle-outer" for="select-img"></label></span></div></li>@endforeach</ul>
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
                                    <input type="text" name="locations[value][]" class="form-control location-required" value="{{ old('locations.value.0', $product->locations[0]->map_address) }}" @if(old('locations.longitude.0', $product->locations[0]->latitude)) data-lat="{{ old('locations.latitude.0', $product->locations[0]->latitude) }}" data-lng="{{ old('locations.longitude.0', $product->locations[0]->longitude) }}" @endif placeholder="{{ __('product.placeholders.location') }}" onfocus="initAutocomplete(this)">
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
                                <div class="col-12 col-sm-12 col-md-12 form-group">
                                    <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span> </label>
                                    <input type="text" name="locations[custom][]" class="form-control location-custom-required" value="{{ old('locations.custom.0', $product->locations[0]->custom_address) }}" placeholder="{{ __('product.placeholders.customLocation') }}">
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
                                                <label class="error-messages">{{ $errors->first('locations.longitude.'.$i) }}</label>
                                                @endif
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 ">
                                                <label>Exact Location <span class="smallFont grey-text">(as per you either available on google map or not)</span> </label>
                                                <input type="text" name="locations[custom][]" class="form-control location-custom-required" placeholder="{{ __('product.placeholders.customLocation') }}" value="{{ old('locations.custom.'.$i, @$product->locations[$i]->custom_address) }}">
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
                                @if ($product->rentaltype == 'Day')
                                    <input type="text" name="non_availabile_dates[0]" class="form-control input-bg mb-3 date-icon non-availability" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off" @if($product->nonAvailableDates->isNotEmpty()) data-from="{{ date($global_date_format, strtotime($product->nonAvailableDates[0]->from_date)) }}" data-to="{{ date($global_date_format, strtotime($product->nonAvailableDates[0]->to_date)) }}" @endif onfocus="initDateRangePicker(this, dateOptions)" @if($product->nonAvailableDates->isNotEmpty()) value="{{ old('non_availabile_dates.0', date($global_date_format, strtotime($product->nonAvailableDates[0]->from_date)) . ' ' .$global_date_separator. ' '. date($global_date_format, strtotime($product->nonAvailableDates[0]->to_date))) }}" @else value="{{ old('non_availabile_dates.0') }}" @endif>
                                @else
                                    <input type="text" name="non_availabile_dates[0]" class="form-control input-bg mb-3 date-icon non-availability" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off" @if($product->nonAvailableDates->isNotEmpty()) data-from="{{ date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[0]->from_date . $product->nonAvailableDates[0]->from_hour . ':' . $product->nonAvailableDates[0]->from_minute)) }}" data-to="{{ date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[0]->to_date . $product->nonAvailableDates[0]->to_hour . ':' . $product->nonAvailableDates[0]->to_minute)) }}" @endif onfocus="initDateRangePicker(this, dateOptions)" @if($product->nonAvailableDates->isNotEmpty()) value="{{ old('non_availabile_dates.0', date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[0]->from_date . $product->nonAvailableDates[0]->from_hour . ':' . $product->nonAvailableDates[0]->from_minute)) . ' ' .$global_date_separator. ' '. date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[0]->to_date . $product->nonAvailableDates[0]->to_hour . ':' . $product->nonAvailableDates[0]->to_minute))) }}" @else value="{{ old('non_availabile_dates.0') }}" @endif>
                                @endif
                                @error('non_availabile_dates.0')
                                <label class="error-messages">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="append-non-available-dates col-md-12">
                                @if ($product->rentaltype == 'Day')
                                    @for($i = 1; $i < old('non_available_date_count', $product->nonAvailableDates->count()); $i++)
                                        <div class="cp-unavailabilities">
                                            <div class="">
                                                <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span> Remove</span>
                                                <input type="text" name="non_availabile_dates[{{ $i }}]" class="form-control input-bg mb-3 date-icon non-availability" data-from="{{ date($global_date_format, strtotime($product->nonAvailableDates[$i]->from_date)) }}" data-to="{{ date($global_date_format, strtotime($product->nonAvailableDates[$i]->to_date)) }}" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ old('non_availabile_dates.' . $i, date($global_date_format, strtotime($product->nonAvailableDates[$i]->from_date)) . ' ' . $global_date_separator . ' ' . date($global_date_format, strtotime($product->nonAvailableDates[$i]->to_date))) }}">
                                                @error('non_availabile_dates.'.$i)
                                                <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    @endfor

                                @else
                                    @for($i = 1; $i < old('non_available_date_count', $product->nonAvailableDates->count()); $i++)
                                        <div class="cp-unavailabilities">
                                            <div class="">
                                                <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span> Remove</span>
                                                <input type="text" name="non_availabile_dates[{{ $i }}]" class="form-control input-bg mb-3 date-icon non-availability" data-from="{{ date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[$i]->from_date . $product->nonAvailableDates[$i]->from_hour . ':' . $product->nonAvailableDates[$i]->from_minute)) }}" data-to="{{ date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[$i]->to_date . $product->nonAvailableDates[$i]->to_hour . ':' . $product->nonAvailableDates[$i]->to_minute)) }}" autocomplete="off" onfocus="initDateRangePicker(this, dateOptions)" value="{{ old('non_availabile_dates.0', date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[$i]->from_date . $product->nonAvailableDates[$i]->from_hour . ':' . $product->nonAvailableDates[$i]->from_minute)) . ' ' .$global_date_separator. ' '. date($global_product_blade_date_time_format, strtotime($product->nonAvailableDates[$i]->to_date . $product->nonAvailableDates[$i]->to_hour . ':' . $product->nonAvailableDates[$i]->to_minute))) }}">
                                                @error('non_availabile_dates.'.$i)
                                                <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <div class="clone-non-available-date-container hidden cp-unavailabilities">
                                <div class="">
                                    <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span> Remove</span>
                                    <input type="text" class="form-control input-bg mb-3 date-icon" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" value="{{ old('status', $product->status) == 'Inactive' ? 'Inactive' : 'Active' }}" @if(old('status', $product->status) == 'Active') checked @endif @if($product->disabled_through == 'Admin') disabled @endif>
                                    <label class="custom-control-label" for="status">Status</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 form-group">
                                <button type="submit" class="btn blue-btn large-btn fullwidth" id="saveProduct">Save Details</button>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places"></script>
<script src="{{ asset('js/custom/google-map.js') }}"></script>
<script src="{{ asset('js/custom/product-add-edit.js') }}"></script>
@stop