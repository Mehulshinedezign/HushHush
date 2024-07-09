@extends('layouts.front')
@section('content')
    <section class="my-profile-sec cust-form-bg fill-hight">
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
                                            <label class="img-upload-box mb-4" for="upload-image-five">
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
                                            <input type="file" name="new_images[]" id="upload-image-five"
                                                accept="image/*" class="d-none" multiple>
                                            <div class="upload-img-preview-box">
                                                <div class="upload-img-preview"
                                                    data-images="{{ json_encode($product->allImages->pluck('file_path')->toArray()) }}">
                                                    @foreach ($product->allImages as $image)
                                                        <div class="image-wrapper" data-id="{{ $image->id }}">
                                                            <img src="{{ asset('storage/' . $image->file_path) }}"
                                                                alt="" loading="lazy">
                                                            <input type="hidden" name="existing_images[]"
                                                                value="{{ $image->id }}">
                                                            <span class="remove-image">&times;</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Product Name</label>
                                            <div class="formfield">
                                                <input type="text" name="product_name" id=""
                                                    placeholder="Enter Name" class="form-control"
                                                    value="{{ $product->name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Category/Subcategory</label>
                                            <div class="duel-select-field">
                                                <div class="formfield">
                                                    <select name="category" class="parent_category">
                                                        <option value="">Category</option>
                                                        @foreach (getParentCategory() as $category)
                                                            <option value="{{ jsencode_userdata($category->id) }}"
                                                                @if ($product->category_id == $category->id) selected @endif>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                </div>
                                                <div class="formfield">
                                                    <select name="subcategory" id="subcategory">
                                                        <option value="">Subcategory</option>
                                                        @foreach (getParentCategory() as $category)
                                                            @foreach (getChild($category->id) as $subcategory)
                                                                <option value="{{ $subcategory->id }}"
                                                                    @if ($product->subcat_id == $subcategory->id) selected @endif>
                                                                    {{ $subcategory->name }}
                                                                </option>
                                                            @endforeach
                                                        @endforeach

                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Size</label>
                                            <div class="formfield">
                                                <select class="form-control" name="size">
                                                    <option value="">Size</option>
                                                    @foreach (getAllsizes() as $size)
                                                        <option value="{{ $size->id }}"
                                                            @if ($product->size == $size->id) selected @endif>
                                                            {{ $size->name }}
                                                        </option>
                                                    @endforeach
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
                                            <label for="">Brand</label>
                                            <div class="formfield">
                                                <select class="form-control" name="brand">
                                                    <option value="">Brand</option>
                                                    @foreach (getBrands() as $brand)
                                                        <option value="{{ $brand->id }}"
                                                            @if ($product->brand == $brand->id) selected @endif>
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
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Color</label>
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
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Condition</label>
                                            <div class="formfield">
                                                <input type="text" name="product_condition" id=""
                                                    placeholder="Product Condition" class="form-control"
                                                    value="{{ $product->product_condition }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">State/city</label>
                                            <div class="duel-select-field">
                                                <div class="formfield">
                                                    <select class="" name="state" id="state-select">
                                                        <option value="">Select State</option>
                                                        @foreach (states() as $state)
                                                            <option value="{{ $state->id }}"
                                                                @if ($product->state == $state->id) selected @endif>
                                                                {{ ucwords($state->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                </div>
                                                <div class="formfield">
                                                    <select name="city" id="city-select">
                                                        <option value="">Select City</option>

                                                    </select>
                                                    <span class="form-icon">
                                                        <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                            alt="img">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Non-Available Dates</label>
                                            <div class="formfield">
                                                <input type="text" name="non_available_dates" id="non_available_date"
                                                    placeholder="Select Dates" class="form-control daterange-cus"
                                                    value="{{ $formattedDates }}">
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <div class="formfield">
                                                <textarea name="description" id="" rows="4" class="form-control" placeholder="Enter Description">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Pickup Location</label>
                                            <div class="formfield">
                                                <div class="formfield">
                                                    <textarea name="pick_up_location" id="" rows="4" class="form-control" placeholder="Text">{{ $pickuplocation->map_address }}</textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product market value</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" class="form-control" name="product_market_value"
                                                    value="{{ number_format($product->product_market_value, 0) }}">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product link</label>
                                            <div class="formfield">
                                                <input type="text" class="form-control" name="product_link"
                                                    value="{{ $product->product_link }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Minimum number of rental days</label>
                                            <div class="formfield ">
                                                <input type="text" class="form-control" name="min_rent_days"
                                                    value="{{ $product->min_days_rent_item }}">
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="">Non-Available Dates</label>
                                    <div class="formfield">
                                        <input type="text" name="" id="non_available_date"
                                            placeholder="Select Dates" class="form-control">
                                        <span class="form-icon">
                                            <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                alt="img">
                                        </span>
                                    </div>
                                </div>
                            </div> --}}
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Rent Price/Day</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_day" id=""
                                                    placeholder="" class="form-control"
                                                    value="{{ $product->rent_day }}">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Week</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_week" id=""
                                                    placeholder="" class="form-control"
                                                    value="{{ $product->rent_week }}">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Month</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_month" id=""
                                                    placeholder="" class="form-control"
                                                    value="{{ $product->rent_month }}">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="right-btn-box">
                                            <button class="button primary-btn " id="addProduct">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadCities(stateId) {
                var url = '{{ route('cities') }}';
                var currentCityId = {{ $product->city }};

                if (stateId) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            state_id: stateId
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            var citySelect = $('#city-select');
                            citySelect.empty();
                            citySelect.append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                var selected = (value.id == currentCityId) ? 'selected' : '';
                                citySelect.append('<option value="' + value.id + '" ' +
                                    selected + '>' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#city-select').empty().append('<option value="">Select City</option>');
                }
            }

            // Load cities when page loads
            var initialStateId = $('#state-select').val();
            loadCities(initialStateId);

            // Load cities when state selection changes
            $('#state-select').change(function() {
                var stateId = $(this).val();
                loadCities(stateId);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let imageCount = $('.image-wrapper').length;
            const maxFiles = 5;

            $('#upload-image-five').on('change', function() {
                let files = $(this)[0].files;
                for (let i = 0; i < files.length && imageCount < maxFiles; i++) {
                    let file = files[i];
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let img = $('<img>').attr('src', e.target.result).attr('alt', '');
                        let removeBtn = $('<span>').addClass('remove-image').text('×');
                        let hiddenInput = $('<input>').attr({
                            type: 'hidden',
                            name: 'new_images[]'
                        }).val(file.name);

                        imgWrapper.append(img, removeBtn, hiddenInput);

                        imageCount++;
                        updateRemoveButtons();
                    }

                    reader.readAsDataURL(file);
                }
            });

            $(document).on('click', '.remove-image', function() {
                $(this).closest('.image-wrapper').remove();
                imageCount--;
                updateRemoveButtons();

                if (imageCount === 0) {
                    alert("Please upload at least one image.");
                }
            });

            function updateRemoveButtons() {
                $('.remove-image').toggle(imageCount > 1);
            }

            updateRemoveButtons();

            // Submit event handler
            $('#addProduct').on('submit', function(e) {
                if (imageCount === 0) {
                    e.preventDefault();
                    alert("Please upload at least one image before submitting.");
                }
            });

            function clearError(fieldName) {
                $('label.error[for="' + fieldName + '"]').remove();
            }
        });
    </script>
@endpush
