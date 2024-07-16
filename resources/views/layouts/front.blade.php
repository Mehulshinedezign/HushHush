<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}?ver={{ now() }}">
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">


    <script>
        const APP_URL = "{{ url('') }}";
        const login_url = '{{ route("login") }}';
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
    <div class="modal fade addproduct-Modal" id="addproduct-Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Add New Product</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    accept="image/*" class="d-none">
                                            </label>
                                        </div>
                                        <div class="upload-img-preview-box">
                                            <div class="upload-img-preview">
                                                {{-- <img src="{{asset('front/images/pro10.png')}}" alt="img"> --}}
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
                                                    placeholder="Enter Name" class="form-control">
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
                                                            <option value="{{ jsencode_userdata($category->id) }}">
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
                                                        <option value="{{ $size->id }}">{{ $size->name }}
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
                                                        <option value="{{ $brand->id }}">
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
                                                        <option value="{{ $color->id }}">
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
                                            <label for="">Address</label>
                                            <div class="form-field">
                                                <input type="text" class="form-control" name="product_complete_location" placeholder="Address" id="product_address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">Country*</label>
                                            <div class="formfield">
                                                <!-- <select name="country" class="form-control" id="country-select">
                                                    <option value="">Select Country</option>
                                                    {{-- @foreach (country() as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ ucwords($country->name) }}
                                                        </option>
                                                    @endforeach --}}
                                                </select> -->
                                                <input type="text" class="form-control" placeholder="country" name="country" id="product_country">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">State*</label>
                                            <div class="formfield">
                                                <input type="text" class="form-control" placeholder="state" name="state" id="product_state">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 product_sub_data">
                                        <div class="form-group">
                                            <label for="">City*</label>
                                            <div class="formfield">
                                                <input type="text" class="form-control" placeholder="city" name="city" id="product_city">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">State/city*</label>
                                            <div class="duel-select-field">
                                                <div class="formfield">
                                                    <select class="" name="state" id="state-select">
                                                        <option value="">Select State</option>
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
                                    </div> -->
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Non-Available Dates*</label>
                                            <div class="formfield">
                                                <input type="text" name="non_available_dates"
                                                    id="non_available_date" placeholder="Select Dates"
                                                    class="form-control daterange-cus">
                                                <span class="form-icon cal-icon">
                                                    <img src="{{ asset('front/images/calender-icon.svg') }}"
                                                        alt="img">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Condition*</label>
                                            <div class="formfield">

                                                {{-- <input type="text" name="product_condition" id=""
                                                    placeholder="Product Condition" class="form-control"> --}}
                                                <select class="form-control" name="product_condition">
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Pickup Location*</label>
                                            <div class="formfield">
                                                <textarea name="pick_up_location" id="" rows="4" class="form-control" placeholder="Text"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Description*</label>
                                            <div class="formfield">
                                                <textarea name="description" id="" rows="4" class="form-control" placeholder="Enter Description"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product market value*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" class="form-control"
                                                    name="product_market_value" value="">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product link</label>
                                            <div class="formfield">
                                                <input type="text" class="form-control" name="product_link"
                                                    placeholder="Product link" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Minimum number of rental days*</label>
                                            <div class="formfield ">
                                                <input type="text" class="form-control" name="min_rent_days"
                                                    placeholder="Rental days" value="">
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Rent Price/Day*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_day" id=""
                                                    placeholder="" class="form-control">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Week*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_week" id=""
                                                    placeholder="" class="form-control">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Month*</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_month" id=""
                                                    placeholder="" class="form-control">
                                                <span class="form-icon">$</span>
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

    <div class="modal fade addbank-Modal" id="addbank-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('stripe.onboarding.redirect') }}" method="POST">
                        <h3 class="modal-title" id="exampleModalLabel">Add your bank details</h3>
                        @csrf
                        <img src="{{ asset('front/images/bank-img.png') }}" alt="">
                        <div class="profile-select-box border-disabled">
                            <div class="profile-check-list">
                             
                                <a href="javascript:void(0)" data-bs-dismiss="modal" arial-label="Close" class="button outline-btn full-btn">Cancel</a>
                               
                                <button type="submit" class="button primary-btn full-btn" id="bank_info">Yes</button>
                       
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

{{-- Query modal section success and error  --}}
    <div class="modal fade query_msg" id="query_msg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.66033 0.947986C6.61823 0.906277 6.56822 0.873185 6.51316 0.850607C6.4581 0.828028 6.39907 0.816406 6.33946 0.816406C6.27985 0.816406 6.22083 0.828028 6.16577 0.850607C6.11071 0.873185 6.06069 0.906277 6.01859 0.947986L3.81264 3.12833L1.60668 0.947986C1.52158 0.863874 1.40616 0.81662 1.28581 0.81662C1.16546 0.81662 1.05004 0.863874 0.96494 0.947986C0.87984 1.0321 0.832031 1.14618 0.832031 1.26513C0.832031 1.38408 0.87984 1.49816 0.96494 1.58228L3.1709 3.76262L0.96494 5.94296C0.922803 5.98461 0.889378 6.03405 0.866573 6.08847C0.843769 6.14288 0.832031 6.20121 0.832031 6.26011C0.832031 6.31901 0.843769 6.37733 0.866573 6.43174C0.889378 6.48616 0.922803 6.5356 0.96494 6.57725C1.00708 6.6189 1.0571 6.65194 1.11216 6.67448C1.16721 6.69702 1.22622 6.70862 1.28581 6.70862C1.3454 6.70862 1.40441 6.69702 1.45946 6.67448C1.51452 6.65194 1.56454 6.6189 1.60668 6.57725L3.81264 4.39691L6.01859 6.57725C6.06073 6.6189 6.11075 6.65194 6.16581 6.67448C6.22086 6.69702 6.27987 6.70862 6.33946 6.70862C6.39905 6.70862 6.45806 6.69702 6.51311 6.67448C6.56817 6.65194 6.61819 6.6189 6.66033 6.57725C6.70247 6.5356 6.73589 6.48616 6.7587 6.43174C6.7815 6.37733 6.79324 6.31901 6.79324 6.26011C6.79324 6.20121 6.7815 6.14288 6.7587 6.08847C6.73589 6.03405 6.70247 5.98461 6.66033 5.94296L4.45438 3.76262L6.66033 1.58228C6.70253 1.54066 6.73601 1.49123 6.75885 1.43681C6.7817 1.38239 6.79346 1.32405 6.79346 1.26513C6.79346 1.20621 6.7817 1.14787 6.75885 1.09345C6.73601 1.03903 6.70253 0.9896 6.66033 0.947986Z" fill="#898383" />
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
                                        <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.66033 0.947986C6.61823 0.906277 6.56822 0.873185 6.51316 0.850607C6.4581 0.828028 6.39907 0.816406 6.33946 0.816406C6.27985 0.816406 6.22083 0.828028 6.16577 0.850607C6.11071 0.873185 6.06069 0.906277 6.01859 0.947986L3.81264 3.12833L1.60668 0.947986C1.52158 0.863874 1.40616 0.81662 1.28581 0.81662C1.16546 0.81662 1.05004 0.863874 0.96494 0.947986C0.87984 1.0321 0.832031 1.14618 0.832031 1.26513C0.832031 1.38408 0.87984 1.49816 0.96494 1.58228L3.1709 3.76262L0.96494 5.94296C0.922803 5.98461 0.889378 6.03405 0.866573 6.08847C0.843769 6.14288 0.832031 6.20121 0.832031 6.26011C0.832031 6.31901 0.843769 6.37733 0.866573 6.43174C0.889378 6.48616 0.922803 6.5356 0.96494 6.57725C1.00708 6.6189 1.0571 6.65194 1.11216 6.67448C1.16721 6.69702 1.22622 6.70862 1.28581 6.70862C1.3454 6.70862 1.40441 6.69702 1.45946 6.67448C1.51452 6.65194 1.56454 6.6189 1.60668 6.57725L3.81264 4.39691L6.01859 6.57725C6.06073 6.6189 6.11075 6.65194 6.16581 6.67448C6.22086 6.69702 6.27987 6.70862 6.33946 6.70862C6.39905 6.70862 6.45806 6.69702 6.51311 6.67448C6.56817 6.65194 6.61819 6.6189 6.66033 6.57725C6.70247 6.5356 6.73589 6.48616 6.7587 6.43174C6.7815 6.37733 6.79324 6.31901 6.79324 6.26011C6.79324 6.20121 6.7815 6.14288 6.7587 6.08847C6.73589 6.03405 6.70247 5.98461 6.66033 5.94296L4.45438 3.76262L6.66033 1.58228C6.70253 1.54066 6.73601 1.49123 6.75885 1.43681C6.7817 1.38239 6.79346 1.32405 6.79346 1.26513C6.79346 1.20621 6.7817 1.14787 6.75885 1.09345C6.73601 1.03903 6.70253 0.9896 6.66033 0.947986Z" fill="#898383" />
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
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"> </script>
    </script>
    <!-- Include DateRangePicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
    <script src="{{ asset('js/custom/product-list.js') }}"></script>
    <script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}?ver={{ now() }}"></script>
    <script>
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
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 250) {
                $("header").addClass("fixed");
            } else {
                $("header").removeClass("fixed");
            }
        });
    </script>

    {{-- Search the country and state  --}}
    <script>
        $(document).ready(function() {

            // fetch the subcategory data
            $('.parent_category').change(function() {
                var categoryId = $(this).val();
                var route = '{{ url('sub_category') }}/' + categoryId;
                // console.log(route, 'route');

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
                        success: function(data,size) {
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
            });


            $('.daterange-cus').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                drops: 'down',
                opens: 'right',
                minDate: moment().startOf('day') // Disable previous dates
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('.daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                autoUpdateInput: false,
                minDate: moment().startOf('day') // Disable previous dates
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
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
                    $('#country, #state, #city').val('');
                }
            });

            function initAutocomplete() {
                var input = document.getElementById('product_address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                $('#product_country, #product_state, #product_city').prop('readonly', true);

                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    $('#product_country, #product_state, #product_city').val('');

                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];

                        if (addressType === 'country') {
                            $('#product_country').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'administrative_area_level_1') {
                            $('#product_state').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'locality') {
                            $('#product_city').val(place.address_components[i].long_name);
                        }
                    }

                    $(".product_sub_data").slideDown("slow");
                });
            }



        });
    </script>

    {{-- This code show the image preview option --}}
    <script>
        $(document).ready(function() {
            const MAX_IMAGES = 5;
            let selectedFiles = [];

            function previewImages(input, imgPreviewPlaceholder) {
                const files = Array.from(input.files);
                const currentCount = selectedFiles.length;

                if (currentCount + files.length > MAX_IMAGES) {
                    alert(`You can upload up to ${MAX_IMAGES} images.`);
                    return;
                }

                files.forEach((file) => {
                    selectedFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const element = `
                    <div class="upload-img-box">
                        <img src="${event.target.result}" alt="img">
                        <div class="upload-img-cross">
                            <i class="fa-regular fa-circle-xmark remove_uploaded"></i>
                        </div>
                    </div>`;
                        $(imgPreviewPlaceholder).append(element);
                    };
                    reader.readAsDataURL(file);
                });

                updateFileInput();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach((file) => dataTransfer.items.add(file));
                $('#upload-image-five')[0].files = dataTransfer.files;
            }

            function updateImageCount(change) {
                const $uploadImage = $('#upload-image-five');
                let currentCount = parseInt($uploadImage.attr('upload-image-count') || 0);
                currentCount += change;
                $uploadImage.attr('upload-image-count', currentCount);
            }

            $('#upload-image-five').on('change', function() {
                previewImages(this, 'div.upload-img-preview');
            });

            $(document).on('click', '.remove_uploaded', function() {
                const index = $(this).closest('.upload-img-box').index();
                selectedFiles.splice(index, 1);
                $(this).closest('.upload-img-box').remove();
                updateFileInput();
                updateImageCount(-1);
            });
        });
    </script>
    {{-- end header --}}

    @include('validation')
    @include('validation.js_product')
    @stack('scripts')
</body>

</html>
