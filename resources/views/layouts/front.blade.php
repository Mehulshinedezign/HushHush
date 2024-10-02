<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />


    <title>@yield('title') | {{ config('app.name', 'Home') }}</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/slick.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}?ver={{ now() }}">
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom-front.css') }}?ver={{ now() }}" />
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}?ver={{ now() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <!-- <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
                (e.ctrlKey && e.key === 'U')) {
                e.preventDefault();
            }
        });
    </script> -->
    <style>
        /* Custom CSS to darken modal backdrop */
        .modal-backdrop.show {
            opacity: 0.8 !important;
            /* Increase this value for a darker effect */
        }

        /* Custom CSS to darken background specifically for the cancellation modal */
        #cancellationModal.modal-backdrop.show {
            opacity: 0.8 !important;
            /* Adjust opacity as needed */
        }
    </style>


    <script>
        const APP_URL = "{{ url('') }}";
        const login_url = '{{ route('login') }}';
        const userId = '{{ jsencode_userdata(auth()->id()) }}';
        const url = "{{ route('addfavorite') }}";
        const errorTitle = "{{ __('favorite.error') }}";
        const allowedExtension = @php echo $global_js_image_extension; @endphp;
        const allowedProofExtension = @php echo $global_js_proof_extension; @endphp;
        const uploadFileSize = parseInt("{{ $global_js_file_size }}");
        const allowedExtensionMessage = allowedExtension.join(',');
        const uploadFileSizeInMb = uploadFileSize / 1000000 + 'Mb';
        const fileNameLength = parseInt("{{ $global_preview_file_name_length }}");
        const minPickedUpImageCount = parseInt("{{ $global_min_picked_up_image_count }}");
        const maxPickedUpImageCount = parseInt("{{ $global_max_picked_up_image_count }}");
        const minReturnedImageCount = parseInt("{{ $global_min_returned_image_count }}");
        const maxReturnedImageCount = parseInt("{{ $global_max_returned_image_count }}");
        const minDisputeImageCount = parseInt("{{ $global_min_dispute_image_count }}");
        const maxDisputeImageCount = parseInt("{{ $global_max_dispute_image_count }}");
        const minProductImageCount = parseInt("{{ $global_min_product_image_count }}");
        const dateFormat = "{{ $global_js_date_format }}";

        const dateOptions = {
            locale: {
                format: "{{ $global_js_date_format }}",
                separator: " {{ $global_date_separator }} ",
            },
            autoUpdateInput: false,
            drops: 'down',
            opens: 'right',
        };
        dateOptions["minDate"] = new Date();
        let selectedLocation = '{{ isset($selectedLocation) ? $selectedLocation : '' }}';
        var loaderIcon = '<span class="loader" id="loader"><img src="{{ asset('img/loader-icon.svg') }}"></span>';
        var rentPrice = '';
    </script>
  
    @yield('links')

</head>

<body>
    <div class="overlay" id="overlay"></div>
    @include('layouts.header')

    <main class="main @if (isset($layout_class)) single_product @endif">
        @yield('content')
    </main>

    {{-- Add produc modal here --}}
    <div class="modal fade addproduct-Modal product-modal" id="addproduct-Modal" tabindex="-1"
        aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Add New Product</h3>
                    <a href="{{ url()->current() }}">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="add-product-main">
                        <div class="add-pro-form">
                            <form method="POST" action="{{ route('saveproduct') }}" id="addProduct"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="rentaltype" value="Day">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Add Product Images (Up to 5)*</label>
                                        <div class="formfield">
                                            <label class="img-upload-box mb-4" for="upload-image-five">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="57"
                                                        height="45" viewBox="0 0 57 45" fill="none">
                                                        <path
                                                            d="M47.4023 15.4413C44.8141 5.13075 34.3578 -1.12941 24.0472 1.45877C15.9898 3.48144 10.1392 10.4454 9.5364 18.7308C3.81529 19.6743 -0.0578042 25.0769 0.885671 30.798C1.72438 35.8842 6.13149 39.609 11.2862 39.5885H20.0352V36.0889H11.2862C7.42066 36.0889 4.28697 32.9552 4.28697 29.0897C4.28697 25.2241 7.42066 22.0904 11.2862 22.0904C12.2526 22.0904 13.036 21.3071 13.036 20.3406C13.0273 11.6431 20.071 4.58524 28.7685 4.5766C36.2975 4.56906 42.7787 9.89176 44.2351 17.2785C44.3789 18.016 44.9775 18.5794 45.7224 18.6783C50.5062 19.3595 53.8318 23.7897 53.1507 28.5734C52.539 32.8689 48.8714 36.0673 44.5326 36.0889H37.5333V39.5885H44.5326C51.2973 39.5681 56.7646 34.0675 56.744 27.3028C56.727 21.6717 52.8726 16.7776 47.4023 15.4413Z"
                                                            fill="#DEE0E3" />
                                                        <path
                                                            d="M27.5422 22.5987L20.543 29.5979L23.0102 32.0652L27.0348 28.0581V44.8388H30.5344V28.0581L34.5414 32.0652L37.0087 29.5979L30.0094 22.5987C29.3269 21.9202 28.2247 21.9202 27.5422 22.5987Z"
                                                            fill="#DEE0E3" />
                                                    </svg>
                                                </span>

                                                <p>Upload Images</p>
                                                <input type="file" name="images[]" id="upload-image-five" multiple
                                                    accept="image/*"
                                                    class="produt_input d-none form-control form-class @error('images') is-invalid @enderror">
                                            </label>
                                            @error('images')
                                                {{-- <span class="invalid-feedback" role="alert"> --}}
                                                <span class="text-danger"
                                                    style="font-size: 14px;">{{ $message }}</span>
                                                {{-- </span> --}}
                                            @enderror
                                        </div>
                                        {{-- <div class="upload-img-preview-box">
                                            <div class="upload-img-preview">
                                            </div>
                                        </div> --}}
                                        <div class="upload-img-preview-box">
                                            <div class="upload-img-preview sortable-images">
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
                                                    class="produt_input form-control form-class @error('product_name') is-invalid @enderror">
                                                @error('product_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}</span>
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
                                                                data-fetchsize="{{ $category->name }}">
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
                                            @error('subcategory')
                                                <span class="invalid-feedback" role="alert">
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Brand*</label>
                                            <div class="formfield">
                                                <select class="form-control" id="mySelect" name="brand">
                                                    <option value="">Brand</option>
                                                    @foreach (getBrands() as $brand)
                                                        <option value="{{ $brand->name }}"
                                                            class="@if ($brand->name == 'Other') moveMe @endif">
                                                            {{ $brand->name }}</option>
                                                        {{-- @if ($brand->name == 'Other')
                                                                <option value="{{$brand->id}}" id="moveMe">{{$brand->name}}</option>
                                                            @endif --}}
                                                    @endforeach
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                            @error('brand')
                                                <span class="invalid-feedback" role="alert">
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-sm-12 d-none" id="other">
                                        <div class="form-group">
                                            <label for="">Other Brand</label>
                                            <div class="formfield">
                                                <input type="text" value=""
                                                    class="produt_input form-control form-class" placeholder="other"
                                                    name="other_brand">
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
                                                <select class="form-control" id="selectColor" name="color">
                                                    <option value="">Color</option>
                                                    @foreach (getColors() as $color)
                                                        <option value="{{ $color->id }}"
                                                            class="@if ($color->name == 'Other') otherColor @endif">
                                                            {{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                            @error('color')
                                                <span class="invalid-feedback" role="alert">
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <div class="product_manual_location">
                                                <label for="">Delivery Option*</label>

                                                <div class="form-check form-switch">
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckChecked">Manual Pickup</label>
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="manual_location" id="flexSwitchCheckChecked" checked>
                                                </div>

                                                <div class="form-check form-switch">
                                                    <label class="form-check-label"
                                                        for="shipmentToggle">Shipment</label>
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="shipment" id="shipmentToggle" checked>
                                                </div>
                                            </div>
                                            <div class="form-field">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('product_complete_location') is-invalid @enderror"
                                                    name="product_complete_location" placeholder="Address"
                                                    id="product_address">
                                                @error('product_complete_location')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    placeholder="address line 1" name="address1"
                                                    id="product_address1">
                                                @error('address1')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-3 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Address line 2</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('address2') is-invalid @enderror"
                                                    placeholder="address line 2" name="address2"
                                                    id="product_address2">
                                                @error('address2')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    placeholder="Country" name="country" id="product_country">
                                                @error('country')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    placeholder="State" placeholder="state" name="state"
                                                    id="product_state">
                                                @error('state')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">City</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('city') is-invalid @enderror"
                                                    placeholder="City" placeholder="city" name="city"
                                                    id="product_city">
                                                @error('city')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    placeholder="zipcode" placeholder="zipcode" name="zipcode"
                                                    id="zipcode" readonly>
                                                @error('city')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Non-Available Dates</label>
                                            <div class="formfield">
                                                <input type="text" name="non_available_dates"
                                                    id="non_available_date" placeholder="Select Dates"
                                                    class="form-control daterange-cus">
                                                <span class="form-icon cal-icon" id="trigger-calendar">
                                                    <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                            @error('non_available_dates')
                                                <span class="invalid-feedback" role="alert">
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product market value*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number"
                                                    class="produt_input form-control form-class @error('product_market_value') is-invalid @enderror"
                                                    name="product_market_value" value="" min="1">
                                                <span class="form-icon">$</span>
                                                @error('product_market_value')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    <option value="">Select Condition</option>
                                                    <option value="Hardly">Hardly used</option>
                                                    <option value="Great">Great condition</option>
                                                    <option value="Good">Good condition</option>
                                                    <option value="Fair">Fair condition</option>
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
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="">Choose cancellation Policy*</label>
                                            <div class="formfield">

                                                <select
                                                    class="produt_input form-control form-class @error('cancellation_policy') is-invalid @enderror"
                                                    name="cancellation_policy">
                                                    <option value="flexible">Flexible</option>
                                                    <option value="firm">Firm</option>
                                                </select>
                                                <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                                @error('cancellation_policy')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#cancellationModal">Read More</a>


                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <x-product-date-avaliable />
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Product link</label>
                                            <div class="formfield">
                                                <input type="text"
                                                    class="produt_input form-control form-class @error('product_link') is-invalid @enderror"
                                                    name="product_link" placeholder="Product link" value="">
                                                @error('product_link')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Minimum number of rental days*</label>
                                            <div class="formfield ">
                                                {{-- <select
                                                    class="produt_input form-control form-class @error('min_rent_days') is-invalid @enderror"
                                                    name="min_rent_days">


                                                    <option value="">Select Rental days</option>
                                                    <option value="7">7 Days</option>
                                                    <option value="14">14 Days</option>
                                                    <option value="30">30 Days</option>
                                                    <!-- <option value="Fair">Fair condition</option> -->
                                                </select> --}}
                                                <input type="number"
                                                    class="produt_input form-control form-class @error('min_rent_days') is-invalid @enderror"
                                                    name="min_rent_days" placeholder="Enter min rental days"
                                                    value="" min="5">

                                                {{-- <span class="form-icon">
                                                    <img src="{{ asset('front/images/dorpdown-icon.svg') }}"
                                                        alt="img">
                                                </span> --}}
                                                @error('min_rent_days')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Rent Price/Day*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number" name="rent_price_day" id=""
                                                    placeholder=""
                                                    class="produt_input form-control form-class @error('rent_price_day') is-invalid @enderror"
                                                    min="1">
                                                <span class="form-icon">$</span>
                                                @error('rent_price_day')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
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
                                                    min="1">
                                                <span class="form-icon">$</span>
                                            </div>
                                            @error('rent_price_week')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Month*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="number" name="rent_price_month" id=""
                                                    placeholder=""
                                                    class="produt_input form-control form-class @error('rent_price_month') is-invalid @enderror"
                                                    min="1">
                                                <span class="form-icon">$</span>
                                            </div>
                                            @error('rent_price_month')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Description*</label>
                                            <div class="formfield">
                                                <textarea name="description" id="" rows="4"
                                                    class="produt_input form-control form-class @error('description') is-invalid @enderror"
                                                    placeholder="Enter Description"></textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="right-btn-box">
                                            <button class="button primary-btn " id="addProduct">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addbank-Modal" id="addbank-Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('stripe.onboarding.redirect') }}" method="POST">
                        <h3 class="modal-title" id="exampleModalLabel">Add your bank details</h3>
                        @csrf
                        <img src="{{ asset('front/images/bank-img.png') }}" alt="">
                        <div class="profile-select-box border-disabled">
                            <div class="profile-check-list">

                                <a href="javascript:void(0)" data-bs-dismiss="modal" arial-label="Close"
                                    class="button outline-btn full-btn">Cancel</a>

                                <button type="submit" class="button primary-btn full-btn"
                                    id="bank_info">Add</button>

                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addbank-Modal" id="identity" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('send.verification.email') }}" method="POST" id="verificationForm">
                        <h3 class="modal-title" id="exampleModalLabel">Verify Your Identity</h3>
                        @csrf
                        <img src="{{ asset('front/images/bank-img.png') }}" alt="">
                        <div class="profile-select-box border-disabled">
                            <div class="profile-check-list">

                                <a href="javascript:void(0)" data-bs-dismiss="modal" arial-label="Close"
                                    class="button outline-btn full-btn">No</a>

                                <button type="submit" class="button primary-btn full-btn"
                                    id="bank_info">Yes</button>

                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn-close" id="closeModalBtn1" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    @include('account-setting')
    @php
        $user = auth()->user();
    @endphp
    @auth
        <div class="modal fade addbank-Modal" id="addaddress-Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ route('change-Profile', $user) }}" method="GET">
                            <h3 class="modal-title" id="exampleModalLabel">Add your Address</h3>
                            @csrf
                            <img src="{{ asset('front/images/—Pngtree—address icon isolated on abstract_5218933.png') }}"
                                alt="" width="200" height="200">
                            <div class="profile-select-box border-disabled">
                                <div class="profile-check-list">

                                    <a href="javascript:void(0)" data-bs-dismiss="modal" arial-label="Close"
                                        class="button outline-btn full-btn">Cancel</a>

                                    <button type="submit" class="button primary-btn full-btn"
                                        id="bank_info">Yes</button>

                                </div>
                            </div>
                        </form>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    {{-- Query modal section success and error  --}}
    <div class="modal fade query_msg" id="query_msg" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    @if (session()->has('success'))
                        <div class="alert-container">
                            <div class="container-box">
                                <div class="alert alert-success" role="alert">
                                    <span class="alert-icon"><i class="far fa-check-circle"></i></span>
                                    <div class="alert-content">
                                        <h6>Success</h6>
                                        <p>{{ session()->get('success') }}</p>
                                    </div>
                                    <a class="alert-cross close" data-bs-dismiss="alert" aria-label="Close">
                                        <svg width="7" height="7" viewBox="0 0 7 7" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6.66033 0.947986C6.61823 0.906277 6.56822 0.873185 6.51316 0.850607C6.4581 0.828028 6.39907 0.816406 6.33946 0.816406C6.27985 0.816406 6.22083 0.828028 6.16577 0.850607C6.11071 0.873185 6.06069 0.906277 6.01859 0.947986L3.81264 3.12833L1.60668 0.947986C1.52158 0.863874 1.40616 0.81662 1.28581 0.81662C1.16546 0.81662 1.05004 0.863874 0.96494 0.947986C0.87984 1.0321 0.832031 1.14618 0.832031 1.26513C0.832031 1.38408 0.87984 1.49816 0.96494 1.58228L3.1709 3.76262L0.96494 5.94296C0.922803 5.98461 0.889378 6.03405 0.866573 6.08847C0.843769 6.14288 0.832031 6.20121 0.832031 6.26011C0.832031 6.31901 0.843769 6.37733 0.866573 6.43174C0.889378 6.48616 0.922803 6.5356 0.96494 6.57725C1.00708 6.6189 1.0571 6.65194 1.11216 6.67448C1.16721 6.69702 1.22622 6.70862 1.28581 6.70862C1.3454 6.70862 1.40441 6.69702 1.45946 6.67448C1.51452 6.65194 1.56454 6.6189 1.60668 6.57725L3.81264 4.39691L6.01859 6.57725C6.06073 6.6189 6.11075 6.65194 6.16581 6.67448C6.22086 6.69702 6.27987 6.70862 6.33946 6.70862C6.39905 6.70862 6.45806 6.69702 6.51311 6.67448C6.56817 6.65194 6.61819 6.6189 6.66033 6.57725C6.70247 6.5356 6.73589 6.48616 6.7587 6.43174C6.7815 6.37733 6.79324 6.31901 6.79324 6.26011C6.79324 6.20121 6.7815 6.14288 6.7587 6.08847C6.73589 6.03405 6.70247 5.98461 6.66033 5.94296L4.45438 3.76262L6.66033 1.58228C6.70253 1.54066 6.73601 1.49123 6.75885 1.43681C6.7817 1.38239 6.79346 1.32405 6.79346 1.26513C6.79346 1.20621 6.7817 1.14787 6.75885 1.09345C6.73601 1.03903 6.70253 0.9896 6.66033 0.947986Z"
                                                fill="#898383" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert-container">
                            <div class="container-box">
                                <div class="alert alert-danger" role="alert">
                                    <span class="alert-icon"><i class="far fa-times-circle"></i></span>
                                    <div class="alert-content">
                                        <h6>Error</h6>
                                        <p>{{ session()->get('error') }}</p>
                                    </div>
                                    <a class="alert-cross close" data-bs-dismiss="alert" aria-label="Close">
                                        <svg width="7" height="7" viewBox="0 0 7 7" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6.66033 0.947986C6.61823 0.906277 6.56822 0.873185 6.51316 0.850607C6.4581 0.828028 6.39907 0.816406 6.33946 0.816406C6.27985 0.816406 6.22083 0.828028 6.16577 0.850607C6.11071 0.873185 6.06069 0.906277 6.01859 0.947986L3.81264 3.12833L1.60668 0.947986C1.52158 0.863874 1.40616 0.81662 1.28581 0.81662C1.16546 0.81662 1.05004 0.863874 0.96494 0.947986C0.87984 1.0321 0.832031 1.14618 0.832031 1.26513C0.832031 1.38408 0.87984 1.49816 0.96494 1.58228L3.1709 3.76262L0.96494 5.94296C0.922803 5.98461 0.889378 6.03405 0.866573 6.08847C0.843769 6.14288 0.832031 6.20121 0.832031 6.26011C0.832031 6.31901 0.843769 6.37733 0.866573 6.43174C0.889378 6.48616 0.922803 6.5356 0.96494 6.57725C1.00708 6.6189 1.0571 6.65194 1.11216 6.67448C1.16721 6.69702 1.22622 6.70862 1.28581 6.70862C1.3454 6.70862 1.40441 6.69702 1.45946 6.67448C1.51452 6.65194 1.56454 6.6189 1.60668 6.57725L3.81264 4.39691L6.01859 6.57725C6.06073 6.6189 6.11075 6.65194 6.16581 6.67448C6.22086 6.69702 6.27987 6.70862 6.33946 6.70862C6.39905 6.70862 6.45806 6.69702 6.51311 6.67448C6.56817 6.65194 6.61819 6.6189 6.66033 6.57725C6.70247 6.5356 6.73589 6.48616 6.7587 6.43174C6.7815 6.37733 6.79324 6.31901 6.79324 6.26011C6.79324 6.20121 6.7815 6.14288 6.7587 6.08847C6.73589 6.03405 6.70247 5.98461 6.66033 5.94296L4.45438 3.76262L6.66033 1.58228C6.70253 1.54066 6.73601 1.49123 6.75885 1.43681C6.7817 1.38239 6.79346 1.32405 6.79346 1.26513C6.79346 1.20621 6.7817 1.14787 6.75885 1.09345C6.73601 1.03903 6.70253 0.9896 6.66033 0.947986Z"
                                                fill="#898383" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @include('modal.cancellationModal')
    @include('modal.cancellationModal')
    {{-- Notifications --}}
    {{-- @include('layouts.notifications') --}}

    <footer>
        <div class="container">
            <div class="copyright footer-section">
                <p class="small-font">© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved</p>
                <div class="copyright-links">
                    <ul>
                        @foreach ($cms as $cms)
                            <li>
                                <a href="{{ route('view', ['slug' => $cms->slug]) }}">{{ $cms->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div id="loader">
        <div class="cube-folding">
            <span class="leaf1"></span>
            <span class="leaf2"></span>
            <span class="leaf3"></span>
            <span class="leaf4"></span>
        </div>
    </div>
    @include('layouts.filter_popup')

    <!--JS-->

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/slick.js') }}"></script>
    <script src="{{ asset('front/js/slick.min.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places">
    </script>
    </script>
    <!-- Include DateRangePicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
    <script src="{{ asset('js/custom/product-list.js') }}"></script>
    <script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}?ver={{ now() }}"></script>



    <script>
        // Ensure background modal still has the fade effect when the second modal is opened
        $('#cancellationModal').on('show.bs.modal', function() {
            $('.addproduct-Modal, .product-modal').css('opacity', 0.5); // Darken the background modal
        });

        $('#cancellationModal').on('hide.bs.modal', function() {
            $('.addproduct-Modal, .product-modal').css('opacity', 1); // Restore the original opacity
        });
    </script>
    <script>
        jQuery(document).on("change", 'select[name="brand"]', function() {
            const selectedValue = $(this).val();
            const otherBrandField = $('#other');
            console.log('Selected Value:', selectedValue);
            console.log('Other Brand Field:', otherBrandField);

            if (selectedValue === 'Other') {
                if (otherBrandField.length > 0) { // Check if the element exists
                    otherBrandField.removeClass('d-none');
                } else {
                    console.log('Other brand field not found');
                }
            } else {
                otherBrandField.addClass('d-none');
            }
        });


        $(document).on('click', '.non-availability', function() {
            $('.daterangepicker, .ltr, .show-calendar, .opensright').last().addClass('testCheck')
        })

        document.getElementById('verificationForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Collect form data

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Optional
                    }
                });

                const data = await response.json(); // Parse JSON response

                if (data.status === 'success') {
                    window.location.href = data.url; // Redirect to the return URL
                } else {
                    alert(data.message || 'An error occurred'); // Handle error
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                alert('There was an error with the form submission. Please try again.'); // Error handling
            }
        });

        // Close modal button
        document.getElementById('closeModalBtn1').addEventListener('click', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('identity'));
            if (modal) {
                modal.hide(); // Hide the modal
            }
        });

        // Prevent form submission on Enter key when focus is on modal
        document.getElementById('verificationForm').addEventListener('keydown', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });

        // Prevent form submission if clicking outside the modal content
        document.getElementById('identity').addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent click events from propagating
        });
    </script>

    <script>
        // Initialize the daterangepicker for a given element
        function initDaterangepicker($element) {
            $element.daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                drops: 'down',
                opens: 'right',
                minDate: moment().startOf('day'),
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });
        }

        // Reposition the daterangepicker relative to the input field when scrolling
        function repositionDatepicker($element) {
            var $container = $element.data('daterangepicker').container;
            var elementOffset = $element.offset();
            $container.css({
                top: elementOffset.top + $element.outerHeight(),
                left: elementOffset.left
            });
        }

        // Scroll event to reposition the calendar when modal or page scrolls
        function initScrollListener($element) {
            var $modal = $('.update_product-modal, .product-modal');

            $modal.on('scroll', function() {
                if ($element.data('daterangepicker')) {
                    repositionDatepicker($element);
                }
            });
        }

        // Initialize the datepicker when the input field is focused
        $(document).on('focus', '.non-availability', function() {
            var $this = $(this);
            initDaterangepicker($this);
            initScrollListener($this);
            repositionDatepicker($this); // Initial position on focus
        });

        // Adding more date fields dynamically
        // $('.add-more-daterangepicker').on('click', function() {
        //     var $clone = $('.clone-non-available-date-container').clone().removeClass('hidden');
        //     $('.append-non-available-dates').append($clone);
        // });

        // Remove the dynamically added datepicker field
        // $(document).on('click', '.remove-daterangepicker', function() {
        //     $(this).closest('.clone-non-available-date-container').remove();
        // });

        // Static initialization for date ranges, e.g., buttons
        $('.daterange-btn').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            autoUpdateInput: false,
            minDate: moment().startOf('day')
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    </script>



    <script>
        // close pop-up button remove input field data
        // $(document).ready(function() {
        //     $('.btn-close').click(function() {
        //         // Clear all input fields inside the form
        //         $('.upload-img-preview').text('');
        //         $('#addProduct').find(
        //                 'input[type="text"], input[type="file"], input[type="email"], select, textarea')
        //             .val('');
        //     });
        // });
        // end
        $(document).ready(function() {
            // When the calendar icon is clicked, trigger the date picker input
            $('#trigger-calendar').on('click', function() {
                $('#non_available_date').focus(); // Focuses the input to trigger the datepicker
            });

            // Initialize the date range picker (make sure you have daterangepicker.js included)
        });
        $(document).ready(function() {
            $('#mySelect').click(function() {
                let $optionToMove = $('.moveMe');

                // Remove the specific option and append it to the end of the select
                $optionToMove.detach().appendTo('#mySelect');
            });
            $('#selectColor').click(function() {
                let $optionToMove = $('.otherColor');

                // Remove the specific option and append it to the end of the select
                $optionToMove.detach().appendTo('#selectColor');
            });
        });

        $(document).ready(function() {
            $('#flexSwitchCheckChecked, #shipmentToggle').on('change', function() {
                // Get the current state of both checkboxes
                const isManualPickupChecked = $('#flexSwitchCheckChecked').is(':checked');
                const isShipmentChecked = $('#shipmentToggle').is(':checked');

                // If both are unchecked, prevent the change
                if (!isManualPickupChecked && !isShipmentChecked) {
                    $(this).prop('checked', true); // Re-check the checkbox that was just unchecked

                    iziToast.error({
                        title: 'Error',
                        message: 'At least one option must be selected.',
                        position: 'topRight',
                    });
                }
            });
        });

        jQuery(document).ready(function() {
            $('#parent-category').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var subcategories = selectedOption.data('subcategories');
                var subcategoryDropdown = $('#subcategory');

                // Clear the subcategory dropdown
                subcategoryDropdown.empty();
                subcategoryDropdown.append('<option value="">Subcategory</option>');

                // If no category is selected, stop here
                if (!subcategories || subcategories.length === 0) {
                    return;
                }

                // Populate the subcategory dropdown with the received data
                subcategories.forEach(function(subcategory) {
                    subcategoryDropdown.append('<option value="' + subcategory.id + '">' +
                        subcategory.name + '</option>');
                });
            });
        });

        $('#non_available_date').on('keypress', function(e) {
            e.preventDefault(); // Prevent any keypress event
        });

        // Alternatively, if you want to prevent only specific keys like letters or numbers, you can do:
        $('#non_available_date').on('keypress', function(e) {
            return false; // Blocks all keyboard inputs
        });


        $('.custom-slider').slick({
            centerPadding: '60px',
            slidesToShow: 10,
            responsive: [{
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 7
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 4
                    }
                }
            ]
        });
        // single product slider
        $('.profile-custom-slider').slick({
            centerPadding: '0px',
            slidesToShow: 4,
            autoplaySpeed: 5000,

            responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        //For sticky header
        // $(window).scroll(function() {
        //     var scroll = $(window).scrollTop();

        //     if (scroll >= 100) {
        //         $("header").addClass("fixed");
        //     } else {
        //         $("header").removeClass("fixed");
        //     }
        // });
    </script>

    {{-- Search the country and state  and GOogle place Api --}}
    <script>
        $(document).ready(function() {

            $('.parent_category').change(function() {
                var categoryId = $(this).val();
                var route = '{{ url('sub_category') }}/' + categoryId;

                if (categoryId) {
                    $.ajax({
                        type: 'GET',
                        url: route,
                        // data: {
                        //     categoryId: categoryId
                        // },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data, size) {
                            $('#subcategory').empty();
                            $('#subcategory').append('<option value="">Subcategory</option>');

                            $.each(data, function(key, value) {
                                $('#subcategory').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred: " + error);
                        }
                    });
                } else {
                    $('#subcategory').empty();
                    $('#subcategory').append('<option value="">Subcategory</option>');
                }


                var sizes = @json(config('size'));
                var fetchSize = $(this).find('option:selected').data('fetchsize');

                var sizeOptions = sizes[fetchSize] || [];
                var sizeSelect = $('select[name="size"]');
                sizeSelect.empty();
                sizeSelect.append('<option value="">Size</option>');

                if (sizeOptions.length === 0) {
                    var bydefaultSizes = sizes['bydefault'];
                    $.each(bydefaultSizes, function(index, size) {
                        sizeSelect.append('<option value="' + size + '">' + size + '</option>');
                    });
                } else {
                    $.each(sizeOptions, function(key, options) {
                        $.each(options, function(index, size) {
                            sizeSelect.append('<option value="' + size + '">' + size +
                                '</option>');
                        });
                    });
                }

            });

            $('.productLink').on('click', function() {
                console.log("click");
                $('body').addClass('loading');
            });


            // Google place api
            $('.product_sub_data').hide();

            $('#product_address').on('focus', function() {
                $(".product_sub_data").slideDown("slow");
                initAutocomplete();
            });

            $('#product_address').on('input', function() {
                if ($(this).val() === '') {
                    $(".product_sub_data").slideUp("slow");
                    // $('#country, #state, #city').val('');
                    $(' #product_address1,#product_address2,#product_country, #product_state, #product_city')
                        .val('');
                }
            });

            function initAutocomplete() {
                var input = document.getElementById('product_address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                $('#product_address1,#product_address2,#product_country, #product_state, #product_city').prop(
                    'readonly', true);

                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    $(' #product_address1,#product_address2,#product_country, #product_state, #product_city',
                            '#zipcode')
                        .val('');
                    var zipcode = null;

                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        if (addressType === 'street_number') {
                            $('#product_address1').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'route') {
                            $('#product_address2').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'country') {
                            $('#product_country').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'administrative_area_level_1') {
                            $('#product_state').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'locality') {
                            $('#product_city').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'postal_code') {
                            $('#zipcode').val(place.address_components[i]
                                .long_name); // Assign zipcode if available
                        }
                    }

                    function setReadonly(selector) {
                        if ($(selector).val()) {
                            $(selector).prop('readonly', true);
                        } else {
                            $(selector).prop('readonly', false);
                        }
                    }

                    setReadonly('#product_address1');
                    setReadonly('#product_address2');

                    $(".product_sub_data").slideDown("slow");
                });
            }


            @if (session('showModal1'))
                $('#addbank-Modal').modal('show');
            @endif
            @if (session('showModal2'))
                $('#addaddress-Modal').modal('show');
            @endif
            // after account details open a modal
            @if (session('showModal3'))
                $('#addproduct-Modal').modal('show');
            @endif
            @if (session('showModal4'))
                $('#identity').modal('show');
            @endif


            @if ($errors->has('add_product_error'))
                $('#addproduct-Modal').modal('show');
            @endif
        });
    </script>

    {{-- This code show the image preview option --}}
    @if (Route::currentRouteName() !== 'editproduct')
        <script>
            $(document).ready(function() {
                const MAX_IMAGES = 5;
                let selectedFiles = [];

                // Function to preview images
                function previewImages(input, imgPreviewPlaceholder) {
                    const files = Array.from(input.files);
                    const currentCount = selectedFiles.length;

                    // Check if the total files exceed the max limit
                    if (currentCount + files.length > MAX_IMAGES) {
                        alert(`You can upload up to ${MAX_IMAGES} images.`);
                        return;
                    }

                    // Filter out files with .jfif extension
                    const filteredFiles = files.filter((file) => {
                        const fileExtension = file.name.split('.').pop().toLowerCase();
                        if (fileExtension === 'jfif') {
                            alert(
                                'Only images in JPG, JPEG, SVG, and PNG formats are allowed for upload. Please upload a different image format.'
                            );
                            return false;
                        }
                        return true;
                    });

                    filteredFiles.forEach((file) => {
                        selectedFiles.push(file);
                    });

                    renderPreview(imgPreviewPlaceholder);
                    updateFileInput();
                }

                // Function to render image preview
                function renderPreview(imgPreviewPlaceholder) {
                    $(imgPreviewPlaceholder).empty(); // Clear the existing preview

                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const element = `
                            <div class="upload-img-box" data-index="${index}">
                                <img src="${event.target.result}" alt="img">
                                <div class="upload-img-cross">
                                    <i class="fa-regular fa-circle-xmark remove_uploaded"></i>
                                </div>
                            </div>`;
                            $(imgPreviewPlaceholder).append(element);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                // Function to update the file input
                function updateFileInput() {
                    const dataTransfer = new DataTransfer();

                    selectedFiles.forEach((file) => dataTransfer.items.add(file));
                    $('#upload-image-five')[0].files = dataTransfer.files;
                }

                // Handle image selection and preview
                $('#upload-image-five').on('change', function() {
                    previewImages(this, 'div.upload-img-preview');
                });

                // Remove an image from the preview
                $(document).on('click', '.remove_uploaded', function() {
                    const index = $(this).closest('.upload-img-box').data('index');
                    selectedFiles.splice(index, 1);
                    renderPreview('div.upload-img-preview'); // Rerender the preview after removing the file
                    updateFileInput();
                });

                // Initialize Sortable.js for reordering images
                const sortable = new Sortable(document.querySelector('.sortable-images'), {
                    animation: 150,
                    onEnd: function(evt) {
                        const oldIndex = evt.oldIndex;
                        const newIndex = evt.newIndex;

                        // Rearrange selectedFiles array based on the new order
                        const movedItem = selectedFiles.splice(oldIndex, 1)[0];
                        selectedFiles.splice(newIndex, 0, movedItem);

                        renderPreview('div.upload-img-preview'); // Rerender the preview in the new order
                        updateFileInput();
                    }
                });

                // Drag and drop file upload
                $('.img-upload-box').on('dragover', function(e) {
                    console.log('here');

                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('drag-over');
                });

                $('.img-upload-box').on('dragleave', function(e) {
                    console.log('here2');

                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('drag-over');
                });

                $('.img-upload-box').on('drop', function(e) {
                    console.log('here3');

                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('drag-over');

                    const files = e.originalEvent.dataTransfer.files;

                    // Set the files to the input and trigger the change event
                    $('#upload-image-five').prop('files', files);
                    $('#upload-image-five').trigger('change');
                    console.log('hrehge');



                });
            });
        </script>
    @endif

    {{-- end header --}}
    @auth
        {{-- chat --}}
        <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>


        <script>
            const firebaseConfig = {
                apiKey: "{{ env('APIKEY') }}",
                authDomain: "{{ env('AUTHDOMAIN') }}",
                databaseURL: "{{ env('DATABASEURL') }}",
                projectId: "{{ env('PROJECTID') }}",
                storageBucket: "{{ env('STORAGEBUCKET') }}",
                messagingSenderId: "{{ env('MESSAGINGSENDERID') }}",
                appId: "{{ env('APPID') }}",
                // measurementId: "{{ env('MEASUREMENTID') }}"
            };
            var senderId = "{{ auth()->user()->id }}";
            const authUserId = "{{ auth()->user()->id }}";
            const authUserprofile =
                "{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}";
            var userImage = "{{ route('retaileruserimage') }}";
            var imagePath = "{{ asset('storage/') }}";
            var chat_store_url = "{{ route('retailerstore.chat') }}";
            var get_chat_url = APP_URL + '/retailer/chat/messages';
            var get_user_names = APP_URL + '/retailer/getuser/names';
            var last_msg_update_url = APP_URL + '/retailer/lastchat/update';
            var search_url = APP_URL + '/retailer/chat/search/';
            var chat_image_store_url = APP_URL + '/retailer/chat/image';
            var chat_page_url = APP_URL + '/chat';

            firebase.initializeApp(firebaseConfig);
            const db = firebase.database();
            var senderid = parseInt(senderId);
            var dbRef = db.ref('/users/' + senderid);
        </script>

        <script>
            // Initialize unseenMessageListenersOverall as an object
            const unseenMessageListenersOverall = {};

            array_list = checkmessagecount();
            array_list.then(chat_list => {
                var messageCount = countSingleChatCount(chat_list);
                messageCount.then(m => {
                    // show total number of unseen message in the header
                    if (m) {
                        $('.userIconbtn').text(m);
                    } else {
                        $('.userIconbtn').text('');

                    }
                });

                // Set up a listener for new messages in each chat
                chat_list.forEach(chatId => {

                    // If a listener already exists for this chat, remove it first
                    if (unseenMessageListenersOverall[chatId]) {
                        db.ref('messeges/' + chatId).off('child_added', unseenMessageListenersOverall[chatId]);
                        console.log('Existing listener removed for:', chatId);
                    }

                    // Add a new listener for child_added
                    unseenMessageListenersOverall[chatId] = db.ref('messeges/' + chatId).on('child_added', (
                        snap) => {
                        const message = snap.val();

                        // Check if the message is unseen and update the count
                        if (message && message.isSeen === 'false') {
                            // Update the unseen message count
                            messageCount.then(currentCount => {
                                let updatedCount = currentCount + 1;
                                if (updatedCount > 0) {
                                    $('.userIconbtn').text(updatedCount);
                                } else {
                                    $('.userIconbtn').text('');
                                }
                            });
                        }

                        // Alternatively, you can update the count by re-checking all messages
                        countSingleChatCount(chat_list).then(updatedCount => {
                            if (updatedCount > 0) {

                                $('.userIconbtn').text(updatedCount);
                            } else {
                                $('.userIconbtn').text('');
                            }
                        });
                    });
                });
            });

            // count total number of unseen message
            async function countSingleChatCount(chat_list) {
                return new Promise(async (res, rej) => {
                    try {
                        let totalMessage = 0;

                        for (let index = 0; index < chat_list.length; index++) {
                            const unseenMessageSnapshot = await db.ref('messeges/' + chat_list[index]).once(
                                'value');

                            if (unseenMessageSnapshot.exists()) {
                                unseenMessageSnapshot.forEach(message => {
                                    const msg = message.val();

                                    // Ensure the message object is not null and has the isSeen property
                                    if (msg && msg.isSeen === 'false') {
                                        totalMessage++;
                                    }
                                });
                            }

                            if (index === chat_list.length - 1) {
                                res(totalMessage);
                            }
                        }
                    } catch (error) {
                        rej(error);
                    }
                });
            }

            async function checkmessagecount() {
                return new Promise((res, rej) => {
                    var array_list = [];
                    dbRef.once("value").then(snap => {
                        snap.forEach((message) => {
                            receiver = parseInt(`${message.key}`);
                            sender = parseInt(`${message.val().id}`);
                            var chatKey = `${sender}_${receiver}`;
                            array_list.push(chatKey);
                        });
                        res(array_list);
                    }).catch(error => {
                        rej(error);
                    });
                });
            }
        </script>
        @yield('custom_variables')
        @yield('custom_variables')
        <script defer src="{{ asset('js/custom/chat2.js') }}"></script>
        <script defer src="{{ asset('js/custom/chatlist.js') }}"></script>
    @endauth
    @include('validation')
    @include('validation.js_product')
    @stack('scripts')
</body>

</html>
