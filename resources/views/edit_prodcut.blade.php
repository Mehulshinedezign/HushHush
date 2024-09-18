@extends('layouts.front')
@section('content')
<section class="my-profile-sec cust-form-bg fill-hight">
    <div class="container">
        <div class="edit-product-main">
            <h2>Edit Product</h2>
            <div class="my-profile-info-box">
                <div class="add-product-main">
                        <div class="add-pro-form">
                            <form method="POST" action="{{ route('saveproduct') }}" id="addProduct"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <input type="hidden" name="rentaltype" value="Day">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Add Product Images (Up to 5)</label>
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

                                        </div>
                                        </label>
                                        <div class="upload-img-preview-box">
                                            <div class="upload-img-preview">
                                                {{-- <img src="{{ asset('front/images/pro-0.png') }}" alt=""> --}}
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
                                                            <option value="{{ $category->id }}">
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
                                                        <option value="{{ $brand->name }}">
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
                                                <input type="text" value="" class="produt_input form-control form-class"
                                                    placeholder="other" name="other_brand">

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
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Condition</label>
                                            <div class="formfield">
                                                <input type="text" name="product_condition" id=""
                                                    placeholder="Product Condition" class="form-control">
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
                                                            <option value="{{ $state->id }}">
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

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Product market value</label>
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
                                                    value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Minimum number of rental days</label>
                                            <div class="formfield ">
                                                <input type="text" class="form-control" name="min_rent_days"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <div class="formfield">
                                                <textarea name="description" id="" rows="4" class="form-control" placeholder="Enter Description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="">Pickup Location</label>
                                            <div class="formfield">
                                                <textarea name="pick_up_location" id="" rows="4" class="form-control" placeholder="Text"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="">Non-Available Dates</label>
                                    <div class="formfield">
                                        <input type="text" name="" id="non_available_date"
                                            placeholder="Select Dates" class="form-control">
                                        <span class="form-icon ">
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
                                                    placeholder="" class="form-control">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Week</label>
                                            <div class="formfield right-icon-field">
                                                <input type="text" name="rent_price_week" id=""
                                                    placeholder="" class="form-control">
                                                <span class="form-icon">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="form-group ">
                                            <label for="">Rent Price/Month</label>
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
</section>
@endsection
