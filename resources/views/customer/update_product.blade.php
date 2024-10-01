@extends('layouts.front')
@section('content')
    <section class="my-profile-sec cust-form-bg fill-hight update_product-modal">
        <div class="container">
            <div class="edit-product-main">
                <h2>Edit Product</h2>
                <div class="my-profile-info-box">
                    <div class="add-product-main">
                        <div class="add-pro-form">
                            <form method="POST"
                                action="{{ route('updateproduct', ['id' => jsencode_userdata($product->id)]) }}"
                                id="addProduct" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="rentaltype" value="Day">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Add Product Images (Up to 5)</label>
                                        <div class="formfield">
                                            <label class="img-upload-box mb-4" for="update-upload-image-five">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="57" height="45"
                                                        viewBox="0 0 57 45" fill="none">
                                                        <path
                                                            d="M47.4023 15.4413C44.8141 5.13075 34.3578 -1.12941 24.0472 1.45877C15.9898 3.48144 10.1392 10.4454 9.5364 18.7308C3.81529 19.6743 -0.0578042 25.0769 0.885671 30.798C1.72438 35.8842 6.13149 39.609 11.2862 39.5885H20.0352V36.0889H11.2862C7.42066 36.0889 4.28697 32.9552 4.28697 29.0897C4.28697 25.2241 7.42066 22.0904 11.2862 22.0904C12.2526 22.0904 13.036 21.3071 13.036 20.3406C13.0273 11.6431 20.071 4.58524 28.7685 4.5766C36.2975 4.56906 42.7787 9.89176 44.2351 17.2785C44.3789 18.016 44.9775 18.5794 45.7224 18.6783C50.5062 19.3595 53.8318 23.7897 53.1507 28.5734C52.539 32.8689 48.8714 36.0673 44.5326 36.0889H37.5333V39.5885H44.5326C51.2973 39.5681 56.7646 34.0675 56.744 27.3028C56.727 21.6717 52.8726 16.7776 47.4023 15.4413Z"
                                                            fill="#DEE0E3" />
                                                        <path
                                                            d="M27.5422 22.5987L20.543 29.5979L23.0102 32.0652L27.0348 28.0581V44.8388H30.5344V28.0581L34.5414 32.0652L37.0087 29.5979L30.0094 22.5987C29.3269 21.9202 28.2247 21.9202 27.5422 22.5987Z"
                                                            fill="#DEE0E3" />
                                                    </svg>
                                                </span>
                                                <p>Upload Images</p>
                                            </label>
                                            <input type="file" name="new_images[]" id="update-upload-image-five"
                                                accept="image/*"
                                                class="d-none form-control form-class @error('new_images') is-invalid @enderror"
                                                multiple>
                                            @error('new_images')
                                                {{-- <span class="invalid-feedback" role="alert"> --}}
                                                <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                                                {{-- </span> --}}
                                            @enderror
                                            <div class="upload-img-preview-box">
                                                <div class="update-upload-img-preview sortable-images1234"
                                                    data-images="{{ json_encode($product->allImages->pluck('file_path')->toArray()) }}">
                                                    {{-- @foreach ($product->allImages as $image)
                                                        <div class="image-wrapper" data-id="{{ $image->id }}">
                                                            <img src="{{ $image->file_path }}" alt=""
                                                                loading="lazy">
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image->id }}">
                                                            <span class="remove-image">&times;</span>
                                                        </div>
                                                    @endforeach --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Product Name*</label>
                                            <div class="formfield">
                                                <input type="text" name="product_name" id=""
                                                    placeholder="Enter Name"
                                                    class="produt_input form-control form-class @error('product_name') is-invalid @enderror"
                                                    value="{{ $product->name }}">
                                                @error('product_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Category/Subcategory*</label>
                                            <div class="duel-select-field">
                                                <div class="formfield">
                                                    <select name="category"
                                                        class="parent_category produt_input form-class @error('category') is-invalid @enderror"
                                                        id="parent-category">
                                                        <option value="">Category</option>
                                                        @foreach (getParentCategory() as $category)
                                                            <option value="{{ jsencode_userdata($category->id) }}"
                                                                data-subcategories="{{ getChild($category->id) }}"
                                                                @if ($product->category_id == $category->id) selected @endif>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                    @error('category')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="formfield">
                                                    <select name="subcategory" id="subcategory"
                                                        class="produt_input form-class @error('subcategory') is-invalid @enderror">
                                                        <option value="">Subcategory</option>
                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                </div>
                                                @error('subcategory')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Size*</label>
                                            <div class="formfield">
                                                <select class="form-control" name="size">
                                                    <option value="">Size</option>
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
                                            <label for="">Brand*</label>
                                            <div class="formfield">
                                                <select class="form-control" name="brand">
                                                    <option value="">Brand</option>
                                                    @foreach (getBrands() as $brand)
                                                        <option value="{{ $brand->name }}"
                                                            class="@if ($brand->name == 'Other') moveMe @endif"
                                                            @if ($product->brand == $brand->name) selected @endif>
                                                            {{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-sm-12 d-none" id="other">
                                        <div class="form-group">
                                            <label for="">Other Brand</label>
                                            <div class="formfield">
                                                <input type="text" value=""
                                                    class="produt_input form-control form-class" placeholder="other"
                                                    name="other_brand">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Color*</label>
                                            <div class="formfield">
                                                <select class="form-control" name="color">
                                                    <option value="">Color</option>
                                                    @foreach (getColors() as $color)
                                                        <option value="{{ $color->id }}"
                                                            @if ($product->color == $color->id) selected @endif>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <div class="product_manual_location">
                                                <label for="">Delivery Option*</label>

                                                <div class="form-check form-switch">
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckChecked">Pickup</label>
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="manual_location" id="flexSwitchCheckChecked"
                                                        {{ $product->productCompleteLocation->manul_pickup_location ? 'checked' : '' }}>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <label class="form-check-label" for="shipmentToggle">Shipment</label>
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="shipment" id="shipmentToggle"
                                                        {{ $product->productCompleteLocation->shipment ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="form-field">
                                                <input type="text"
                                                    class="form-control produt_input form-class @error('product_complete_location') is-invalid @enderror"
                                                    placeholder="Address" name="product_complete_location"
                                                    id="product_address"
                                                    value="{{ $product->productCompleteLocation->pick_up_location ?? '' }}">
                                                @error('product_complete_location')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Address line 1*</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('address1') is-invalid @enderror"
                                                    placeholder="address line 1" name="address1" id="product_address1"
                                                    value="{{ $product->productCompleteLocation->address1 ?? '' }}">
                                                @error('address1')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Address line 2*</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('address2') is-invalid @enderror"
                                                    placeholder="address line 2" name="address2" id="product_address2"
                                                    value="{{ $product->productCompleteLocation->address2 ?? '' }}">
                                                @error('address2')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Country*</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('country') is-invalid @enderror"
                                                    placeholder="country" name="country" id="product_country"
                                                    value="{{ $product->productCompleteLocation->country ?? '' }}">
                                                @error('country')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">State*</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('state') is-invalid @enderror"
                                                    placeholder="state" name="state" id="product_state"
                                                    value="{{ $product->productCompleteLocation->state ?? '' }}">
                                                @error('state')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">City*</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('city') is-invalid @enderror"
                                                    placeholder="city" name="city" id="product_city"
                                                    value="{{ $product->city ?? '' }}">
                                                @error('city')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-2 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Zip-Code/Postal Code</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('zipcode') is-invalid @enderror"
                                                    placeholder="zipcode" name="zipcode" id="zipcode"
                                                    value="{{ $product->productCompleteLocation->postcode ?? '' }}"
                                                    readonly>
                                                @error('zipcode')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Non-Available Dates*</label>
                                            <div class="formfield">
                                                <input type="text" name="non_available_dates" id="non_available_date"
                                                    placeholder="Select Dates" class="form-control daterange-cus"
                                                    value="{{ $formattedDates }}" readonly>
                                                <span class="form-icon cal-icon">
                                                    <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product market value*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number"
                                                    class="produt_input form-control form-class @error('product_market_value') is-invalid @enderror"
                                                    name="product_market_value"
                                                    value="{{ number_format($product->product_market_value, 0, '', '') }}"
                                                    min="1">
                                                <span class="form-icon">$</span>
                                                @error('product_market_value')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="">Condition*</label>
                                            <div class="formfield">
                                                <select
                                                    class="produt_input form-control form-class @error('product_condition') is-invalid @enderror"
                                                    name="product_condition">
                                                    <option value="">Condition</option>
                                                    <option value="Hardly"
                                                        @if ($product->product_condition == 'Hardly') selected @endif>Hardly used
                                                    </option>
                                                    <option value="Great"
                                                        @if ($product->product_condition == 'Great') selected @endif>Hardly used
                                                    </option>
                                                    <option value="Good"
                                                        @if ($product->product_condition == 'Good') selected @endif>Good condition
                                                    </option>
                                                    <option value="Fair"
                                                        @if ($product->product_condition == 'Fair') selected @endif>Fair condition
                                                    </option>
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                                @error('product_condition')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="">Choose cancellation Policy*</label>
                                            <div class="formfield">
                                                <select
                                                    class="produt_input form-control form-class @error('cancellation_policy') is-invalid @enderror"
                                                    name="cancellation_policy">
                                                    {{-- <option value="">Condition</option> --}}
                                                    <option value="flexible"
                                                        @if ($product->cancellation_policy == 'flexible') selected @endif>Flexible
                                                    </option>
                                                    <option value="firm"
                                                        @if ($product->cancellation_policy == 'firm') selected @endif>Firm
                                                    </option>

                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                                @error('product_condition')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#cancellationModal">Read More</a>
                                        </div>
                                    </div>


                                    {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Pickup Location*</label>
                                            <div class="formfield">
                                                <div class="formfield">
                                                    <textarea name="pick_up_location" id="" rows="4" class="form-control" placeholder="Text">{{ $pickuplocation->pick_up_location }}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-12">
                                        <x-edit-date-avaliable :product="$ranges" />
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Product link</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('product_link') is-invalid @enderror"
                                                    name="product_link" value="{{ $product->product_link }}">
                                                @error('product_link')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Minimum number of rental days*</label>
                                            <div class="formfield ">
                                                <input type="number"
                                                    class="produt_input form-control form-class @error('min_rent_days') is-invalid @enderror"
                                                    name="min_rent_days" value="{{ $product->min_days_rent_item }}"
                                                    min="5">
                                                @error('min_rent_days')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            {{-- <div class="formfield">
                                                <select
                                                    class="produt_input form-control form-class @error('min_rent_days') is-invalid @enderror"
                                                    name="min_rent_days">
                                                    <option value="">Select rental days</option>
                                                    <option value="7"
                                                        @if ($product->min_days_rent_item == 7) selected @endif>7 Days
                                                    </option>
                                                    <option value="14"
                                                        @if ($product->min_days_rent_item == 14) selected @endif>14 Days</option>
                                                    <option value="30"
                                                        @if ($product->min_days_rent_item == 30) selected @endif>30 Days</option>
                                                    <!-- <option value="Fair"
                                                                                    @if ($product->product_condition == 'Fair') selected @endif>Fair condition</option> -->
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>

                                                @error('min_rent_days')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Rent Price/Day*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number" name="rent_price_day" id=""
                                                    placeholder=""
                                                    class="produt_input form-control form-class @error('rent_price_day') is-invalid @enderror"
                                                    value="{{ $product->rent_day }}" min="1">
                                                <span class="form-icon">$</span>
                                                @error('rent_price_day')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Week*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number" name="rent_price_week" id=""
                                                    placeholder=""
                                                    class="produt_input form-control form-class @error('rent_price_week') is-invalid @enderror"
                                                    value="{{ $product->rent_week }}" min="1">
                                                <span class="form-icon">$</span>
                                                @error('rent_price_week')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Month*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number" name="rent_price_month" id=""
                                                    placeholder=""
                                                    class="produt_input form-control form-class @error('rent_price_month') is-invalid @enderror"
                                                    value="{{ $product->rent_month }}" min="1">
                                                <span class="form-icon">$</span>
                                                @error('rent_price_month')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Description*</label>
                                            <div class="formfield">
                                                <textarea name="description" id="" rows="4"
                                                    class="produt_input form-control form-class @error('description') is-invalid @enderror"
                                                    placeholder="Enter Description">{{ $product->description }}</textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="right-btn-box">
                                            <button class="button primary-btn " id="addProduct">Update</button>
                                        </div>
                                    </div>
                                    <!-- <img id="image123" src="" alt="asfdsfd"> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('modal.cancellationModal')
@endsection

@push('scripts')
    <script>
        // The same script as in Blade 1
        $(document).ready(function() {
            var subcategorySelect = $('#subcategory');

            $('#parent-category').change(function() {
                var selectedCategory = $(this).find(':selected');
                var subcategories = selectedCategory.data('subcategories');
                subcategorySelect.empty().append('<option value="">Subcategory</option>');
                if (subcategories) {
                    $.each(subcategories, function(index, subcategory) {
                        subcategorySelect.append('<option value="' + subcategory.id + '">' +
                            subcategory.name + '</option>');
                    });
                }
            });

            // If the product has a selected category and subcategory
            var selectedCategory = $('#parent-category').find(':selected');
            if (selectedCategory.length) {
                var subcategories = selectedCategory.data('subcategories');
                var selectedSubcategoryId = "{{ $product->subcat_id }}";
                subcategorySelect.empty().append('<option value="">Subcategory</option>');
                if (subcategories) {
                    $.each(subcategories, function(index, subcategory) {
                        subcategorySelect.append('<option value="' + subcategory.id + '" ' + (subcategory
                                .id == selectedSubcategoryId ? 'selected' : '') + '>' + subcategory
                            .name + '</option>');
                    });
                }
            }
        });
    </script>
    <script>
        var newFiles = new Set();
        let fileInput = document.getElementById('update-upload-image-five');
        let uploadedFiles = new Set();




        function updateFileInput() {
            let dt = new DataTransfer();
            newFiles.forEach(item => {
                if (!item.existing) {
                    dt.items.add(item.file);
                }
            });
            fileInput.files = dt.files;
        }

        function updateRemoveButtons() {
            $('.remove-image').toggle(newFiles.size > 0);
        }

        async function fetchAndDisplayImage(url, isExisting = false) {
            try {
                const response = await fetch(url);
                const blob = await response.blob();
                const mimeTypeToExtension = {
                    'image/jpeg': 'jpg', // Standard format for JPEG images
                    'image/jfif': 'jpg', // JPEG File Interchange Format
                    'image/png': 'png', // Portable Network Graphics
                    'image/gif': 'gif', // Graphics Interchange Format
                    'image/webp': 'webp', // WebP format
                    'image/bmp': 'bmp', // Bitmap image
                    'image/svg+xml': 'svg' // Scalable Vector Graphics
                };

                const extension = mimeTypeToExtension[blob.type] || 'bin';
                const fileName = `new_${Date.now()}.${extension}`;
                const file = new File([blob], fileName, {
                    type: blob.type
                });
                let dt = new DataTransfer();
                dt.items.add(file);
                let reader = new FileReader();

                reader.onload = function(e) {
                    let imgWrapper = $('<div>').addClass('image-wrapper');
                    let img = $('<img>').attr({
                        src: e.target.result,
                        alt: '',
                        loading: 'lazy'
                    });
                    let removeBtn = $('<span>').addClass('remove-image').html('&times;');
                    let hiddenInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'new_images[]'
                    }).val(file.name);
                    imgWrapper.append(img, removeBtn, hiddenInput);
                    $('.update-upload-img-preview').append(imgWrapper);

                    if (!isExisting) {
                        uploadedFiles.add(file.name);
                        updateRemoveButtons();
                    }
                };

                reader.readAsDataURL(file);
                fileInput.files = dt.files;
                newFiles.add({
                    file,
                    existing: false
                });
                updateFileInput();
            } catch (error) {
                console.error('Error fetching the image:', error);
            }
        }

        $(document).ready(function() {
            const maxFiles = 5;

            let prev_images = @json($product->allImages->pluck('file_path')->toArray());
            prev_images.forEach(imageUrl => fetchAndDisplayImage(imageUrl, true));

            $('#update-upload-image-five').on('change', function(e) {
                let files = e.target.files;
                let remainingSlots = maxFiles - newFiles.size;

                if (files.length > remainingSlots) {
                    alert(`You can upload only ${maxFiles} images in total.`);
                    return;
                }

                processFiles(Array.from(files));
            });

            function processFiles(files) {
                if (files.length === 0) {
                    updateFileInput();
                    return;
                }

                let file = files.shift();

                // Check if the file extension is 'jfif'
                if (file.name.toLowerCase().endsWith('.jfif')) {
                    alert(
                        'Only images in JPG, JPEG, SVG, and PNG formats are allowed for upload. Please upload a different image format.'
                        );

                    processFiles(files); // Continue processing the next file
                    return;
                }

                if (!uploadedFiles.has(file.name)) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let imgWrapper = $('<div>').addClass('image-wrapper');
                        let img = $('<img>').attr({
                            src: e.target.result,
                            alt: '',
                            loading: 'lazy'
                        });
                        let removeBtn = $('<span>').addClass('remove-image').html('&times;');
                        let hiddenInput = $('<input>').attr({
                            type: 'hidden',
                            name: 'new_images[]'
                        }).val(file.name);
                        imgWrapper.append(img, removeBtn, hiddenInput);
                        console.log('here');

                        $('.update-upload-img-preview').append(imgWrapper);

                        newFiles.add({
                            file,
                            existing: false
                        });
                        uploadedFiles.add(file.name);
                        updateRemoveButtons();
                        processFiles(files);
                    };

                    reader.readAsDataURL(file);
                } else {
                    processFiles(files);
                }
            }

            $(document).on('click', '.remove-image', function() {
                let wrapper = $(this).closest('.image-wrapper');
                let filename = wrapper.find('input[name="new_images[]"]').val();

                if (filename) {
                    uploadedFiles.delete(filename);
                    newFiles = new Set([...newFiles].filter(item => item.file.name !== filename));
                }

                wrapper.remove();
                updateFileInput();
                updateRemoveButtons();
            });

            new Sortable(document.querySelector('.sortable-images1234'), {
                animation: 150,
                onEnd: function(evt) {
                    const newIndex = evt.newIndex;
                    const oldIndex = evt.oldIndex;
                    let sortedArray = Array.from(newFiles);
                    const movedItem = sortedArray.splice(oldIndex, 1)[0];
                    sortedArray.splice(newIndex, 0, movedItem);
                    newFiles = new Set(sortedArray);
                    updateFileInput();
                }
            });

            $('.img-upload-box').on('dragover', function(e) {
                console.log('here');

                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('drag-over');
            });

            $('.img-upload-box').on('dragleave', function(e) {
                console.log('here1');

                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('drag-over');
            });

            $('.img-upload-box').on('drop', function(e) {
                console.log('here2');

                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('drag-over');

                const files = e.originalEvent.dataTransfer.files;

                // Set the files to the input and trigger the change event
                $('#update-upload-image-five').prop('files', files);
                $('#update-upload-image-five').trigger('change');
                console.log('done');

            });

            $('form').on('submit', function(e) {
                if (newFiles.size === 0) {
                    e.preventDefault();
                    alert("Please upload at least one image before submitting.");
                } else if (newFiles.size > maxFiles) {
                    e.preventDefault();
                    alert(
                        `You can only have a maximum of ${maxFiles} images. Please remove some images before submitting.`
                    );
                }
            });

            var sizes = @json(config('size'));
            var category_size = $('select[name="size"]').find('option:selected').data('fetchsize');
            var size = "{{ $product->size }}";
            var selectedOption = $('select[name="size"]');
            selectedOption.empty();

            var sizeOptions = sizes[category_size] || [];
            var defaultSizes = sizeOptions.length === 0 ? sizes['bydefault'] : sizeOptions;

            defaultSizes.forEach(confSize => {
                var isSelected = confSize === size ? ' selected' : '';
                selectedOption.append(`<option value="${confSize}"${isSelected}>${confSize}</option>`);
            });
            
        });
    </script>
@endpush
