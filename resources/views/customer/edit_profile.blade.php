@extends('layouts.front')
@section('title', 'My Profile')
@section('links')
    <script src="https://cdn.tiny.cloud/1/rcqfj1cr6ejxsyuriqt95tnyc64joig2nppf837i8qckzy90/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            branding: false,
        });
        const notify_url = "{{ route('notificationsetting') }}";
    </script>
@endsection

@section('content')
    @php
        $user = auth()->user();
    @endphp

    {{-- <div class="small-banner contact-banner">
        <h4 style="text-align: center">Hi, {{ ucfirst($user->name) }}</h4>
    </div>
    <div class="wrapper-tab">
        <div class="container">
            <x-alert />
            <div class="row">
                <div class="col-sm-12">
                    <div class="profile-form-outer">
                        <nav class="mb-3">
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <a class="nav-link @if (!$errors->has('old_password') && !$errors->has('new_password') && !session('error')) active @endif" id="nav-home-tab"
                                    data-bs-toggle="tab" href="#nav-home" data-bs-target="#nav-home" type="button"
                                    role="tab" aria-controls="nav-home" aria-selected="false">
                                    Profile
                                </a>
                                <a class="nav-link @if ($errors->has('old_password') || $errors->has('new_password') || session('error')) active @endif" id="nav-profile-tab"
                                    data-bs-toggle="tab" href="#nav-profile" data-bs-target="#nav-profile" type="button"
                                    role="tab" aria-controls="nav-profile" aria-selected="false">Password</a>
 
                                <a class="nav-link" id="nav-card-tab" href="#nav-card" data-bs-toggle="tab"
                                    data-bs-target="#nav-card" type="button" role="tab" aria-controls="nav-card"
                                    aria-selected="true">Payment</a>
                                <a class="nav-link" id="nav-bankdetails-tab" href="#nav-bankdetails" data-bs-toggle="tab"
                                    data-bs-target="#nav-bankdetails" type="button" role="tab"
                                    aria-controls="nav-bankdetails" aria-selected="true">Bank Details</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show @if (!$errors->has('old_password') && !$errors->has('new_password') && !session('error')) active @endif" id="nav-home"
                                role="tabpanel" aria-labelledby="nav-home-tab">

                                <form class="profile-detail" method="post" id="userDetail"
                                    action="{{ route('saveprofile') }}" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <div class="row">

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label></label>
                                            <div class="formfield">
                                                <input type="text" placeholder="Username" name="username"
                                                    class="form-control" value="{{ old('username', $user->username) }}">
                                                <span class="icon">
                                                    <i class="fa-solid fa-user"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="text" placeholder="{{ __('user.placeholders.name') }}"
                                                    name="name" class="form-control required"
                                                    value="{{ old('name', $user->name) }}">
                                                <span class="icon">
                                                    <i class="fa-solid fa-user"></i>
                                                </span>
                                            </div>
                                            @error('name')
                                                <label class="error-messages">{{ $message }}</span>
                                                @enderror
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="text" placeholder="{{ __('user.placeholders.phone') }}"
                                                    name="phone_number" class="form-control required"
                                                    value="{{ old('phone_number', $user->phone_number) }}">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                        viewBox="0 0 512 512">
                                                        <path
                                                            d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"
                                                            fill="#DEE0E3" />
                                                    </svg>
                                                </span>
                                            </div>
                                            @error('phone_number')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="text" placeholder="{{ __('user.placeholders.address1') }}"
                                                    name="address1" class="form-control form-class"
                                                    value="{{ old('address1', @$user->userDetail->address1) }}">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                        viewBox="0 0 384 512">
                                                        <path
                                                            d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"
                                                            fill="#DEE0E3" />
                                                    </svg>
                                                </span>
                                            </div>
                                            @error('address1')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label></label>
                                            <div class="formfield">
                                                <input type="text"
                                                    placeholder="{{ __('user.placeholders.address2') }}" name="address2"
                                                    class="form-control"
                                                    value="{{ old('address2', @$user->userDetail->address2) }}">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                        viewBox="0 0 384 512">
                                                        <path
                                                            d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"
                                                            fill="#DEE0E3" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="select-option select_box">
                                                <select name="country" id="country" class="form-select">
                                                    <option selected>{{ __('user.placeholders.country') }}</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                            @if ($country->id == $selectedCountryId) selected @endif>
                                                            {{ ucwords($country->name) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="select-option select_box">
                                                <select name="state" id="state" class="form-select">
                                                    <option value="">{{ __('user.placeholders.state') }}
                                                    </option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}"
                                                            @if ($state->id == @$user->userDetail->state_id) selected @endif>
                                                            {{ ucwords($state->name) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('state')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <div class="select-option select_box">
                                                <select name="city" id="city" class="form-select">
                                                    <option value="">{{ __('user.placeholders.city') }}</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($city->id == @$user->userDetail->city_id) selected @endif>
                                                            {{ ucwords($city->name) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('city')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label><span class="text-danger"></span></label>
                                            <input type="text" placeholder="{{ __('user.placeholders.zip') }}"
                                                name="postcode" class="form-control required"
                                                value="{{ old('postcode', $user->zipcode) }}">
                                            @error('postcode')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                            <label></label>
                                            <div class="form-control input-file-uploader">
                                                <p class="close-file d-none"><i class="fa-solid fa-xmark"></i></p>
                                                <label id="fileLabel" for="editprofileimg">No file selected</label>
                                                <input type="file" id="editprofileimg" name="profile_pic"
                                                    class="form-control" onchange="pressed()"
                                                    accept="{{ str_replace(['[', "'", ']'], ['', '', ''], $global_js_image_extension) }}">
                                                @error('profile_pic')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                            <label></label>
                                            <textarea class="form-control" placeholder="About" name="about" cols="30" rows="10" maxlength="800">{{ old('about', @$user->userDetail->about) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                            <button type="submit" class="btn btn_black" id="confirmYes">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade show @if ($errors->has('old_password') || $errors->has('new_password') || session('error')) active @else fade @endif"
                                id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                                <form class="change-password" method="post" id="changePassword"
                                    action="{{ route('changepassword') }}" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="password" placeholder="Current Password*"
                                                    name="current_password" class="form-control">
                                                <span class="icon toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('current_password')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="password" placeholder="New Password*" name="new_password"
                                                    id="newPassword" class="form-control">
                                                <span class="icon toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('new_password')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                            <label><span class="text-danger"></span></label>
                                            <div class="formfield">
                                                <input type="password" placeholder="Confirm New Password*"
                                                    name="confirm_password" class="form-control">
                                                <span class="icon toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 form-group">
                                            <button type="submit" class="btn btn_black">Change Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="nav-card" role="tabpanel" aria-labelledby="nav-card-tab">
                                <div class="tab-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                        </div>
                                        <div class="btn-right">
                                            <button type="button" class="btn btn-dark load_script"
                                                data-bs-toggle="modal" data-bs-target="#addcardModal"> <img
                                                    src="{{ asset('front/images/plus.svg') }}"> Add Card </button>
                                        </div>
                                    </div>
                                    <div class="wrapper_table">
                                        <table class="rwd-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Cards</th>
                                                    <th>Created Date</th>
                                                    <th>Expiration Date</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($cards as $card)
                                                    <tr data-value="{{ $card->id }}">
                                                        <td data-th="item">
                                                            <p class="radio_holder">
                                                                <input
                                                                    class="form-check-input user-card @if ($card->is_default) 'default' @endif"
                                                                    name="card" type="radio"
                                                                    value="{{ $card->id }}"
                                                                    id="card{{ $card->id }}"
                                                                    @if ($card->is_default) checked @endif>
                                                                <label class="form-check-label"
                                                                    for="card{{ $card->id }}">&nbsp;</label>
                                                            </p>
                                                        </td>
                                                        <td data-th="Cards">
                                                            <span>************{{ jsdecode_userdata($card->last_digits) }}</span>
                                                        </td>
                                                        <td data-th="date added">
                                                            <span>
                                                                {{ $card->created_at->format('d/m/Y') }}
                                                            </span>
                                                        </td>
                                                        <td data-th="expiration date">
                                                            <span>
                                                                {{ (jsdecode_userdata($card->exp_month) > 9 ? jsdecode_userdata($card->exp_month) : '0' . jsdecode_userdata($card->exp_month)) . '/' . jsdecode_userdata($card->exp_year) }}
                                                            </span>
                                                        </td>
                                                        <td data-th="action-btn">
                                                            <div class="btn-area justify-content-center">
                                                                <button class="btn-del btn"><a href="javascript:void(0);"
                                                                        class="button delete-btn delete-card {{ $card->is_default ? 'default' : 'set_default' }}"
                                                                        data-value="{{ $card->id }}"><img
                                                                            src="{{ asset('front/images/del.svg') }}"></a></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-bankdetails" role="tabpanel"
                                aria-labelledby="nav-bankdetails-tab">
                                <form action="{{ route('bankdetail') }}" method="post" id="bankDetail"
                                    autocomplete="off">
                                    @csrf
                                    <div class="add-product">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <input type="hidden" name="checkbankdetails" value="1">
                                                        <input type="text" name="account_holder_first_name"
                                                            class="form-control required"
                                                            value="{{ old('account_holder_first_name', @$bankDetail->account_holder_first_name) }}"
                                                            placeholder="Account Holder First Name">
                                                        @error('account_holder_first_name')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <input type="text" name="account_holder_last_name"
                                                            class="form-control required"
                                                            value="{{ old('account_holder_last_name', @$bankDetail->account_holder_last_name) }}"
                                                            placeholder="Account Holder Last Name">
                                                        @error('account_holder_last_name')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <div class="order-status p-relative">
                                                            <div class="formfield">
                                                                <input type="text" name="account_holder_dob"
                                                                    class="form-control required"
                                                                    value="{{ old('account_holder_dob', isset($bankDetail) ? date('m/d/Y', strtotime(@$bankDetail->account_holder_dob)) : '') }}"
                                                                    placeholder="Date of Birth">
                                                                <span class="icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                        height="11" viewBox="0 0 12 11"
                                                                        fill="none">
                                                                        <path
                                                                            d="M10.3338 0.166016H9.34992V1.1499C9.34992 1.34668 9.18594 1.47786 9.02196 1.47786C8.85798 1.47786 8.694 1.34668 8.694 1.1499V0.166016H3.44661V1.1499C3.44661 1.34668 3.28263 1.47786 3.11865 1.47786C2.95467 1.47786 2.79069 1.34668 2.79069 1.1499V0.166016H1.8068C1.31486 0.166016 0.954102 0.592366 0.954102 1.1499V2.33056H11.4489V1.1499C11.4489 0.592366 10.8585 0.166016 10.3338 0.166016ZM0.954102 3.01928V9.02098C0.954102 9.61132 1.31486 10.0049 1.8396 10.0049H10.3666C10.8913 10.0049 11.4817 9.57852 11.4817 9.02098V3.01928H0.954102ZM3.87296 8.52904H3.08585C2.95467 8.52904 2.82348 8.43065 2.82348 8.26667V7.44677C2.82348 7.31558 2.92187 7.1844 3.08585 7.1844H3.90576C4.03694 7.1844 4.16813 7.28279 4.16813 7.44677V8.26667C4.13533 8.43065 4.03694 8.52904 3.87296 8.52904ZM3.87296 5.57739H3.08585C2.95467 5.57739 2.82348 5.479 2.82348 5.31502V4.49511C2.82348 4.36393 2.92187 4.23274 3.08585 4.23274H3.90576C4.03694 4.23274 4.16813 4.33113 4.16813 4.49511V5.31502C4.13533 5.479 4.03694 5.57739 3.87296 5.57739ZM6.49666 8.52904H5.67675C5.54557 8.52904 5.41438 8.43065 5.41438 8.26667V7.44677C5.41438 7.31558 5.51277 7.1844 5.67675 7.1844H6.49666C6.62784 7.1844 6.75903 7.28279 6.75903 7.44677V8.26667C6.75903 8.43065 6.66064 8.52904 6.49666 8.52904ZM6.49666 5.57739H5.67675C5.54557 5.57739 5.41438 5.479 5.41438 5.31502V4.49511C5.41438 4.36393 5.51277 4.23274 5.67675 4.23274H6.49666C6.62784 4.23274 6.75903 4.33113 6.75903 4.49511V5.31502C6.75903 5.479 6.66064 5.57739 6.49666 5.57739ZM9.12035 8.52904H8.30045C8.16926 8.52904 8.03808 8.43065 8.03808 8.26667V7.44677C8.03808 7.31558 8.13647 7.1844 8.30045 7.1844H9.12035C9.25154 7.1844 9.38272 7.28279 9.38272 7.44677V8.26667C9.38272 8.43065 9.28433 8.52904 9.12035 8.52904ZM9.12035 5.57739H8.30045C8.16926 5.57739 8.03808 5.479 8.03808 5.31502V4.49511C8.03808 4.36393 8.13647 4.23274 8.30045 4.23274H9.12035C9.25154 4.23274 9.38272 4.33113 9.38272 4.49511V5.31502C9.38272 5.479 9.28433 5.57739 9.12035 5.57739Z"
                                                                            fill="#9F9FA0"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>

                                                            @error('account_holder_dob')
                                                                <label class="error-messages">{{ $message }}</label>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <input type="text" name="account_number"
                                                            class="form-control required"
                                                            value="{{ old('account_number', jsdecode_userdata(@$bankDetail->account_number)) }}"
                                                            placeholder="Account Number">
                                                        @error('account_number')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <input type="text" name="routing_number"
                                                            class="form-control required"
                                                            value="{{ old('routing_number', jsdecode_userdata(@$bankDetail->routing_number)) }}"
                                                            placeholder="Routing Number">
                                                        @error('routing_number')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <button type="submit" class="btn btn_black">Save Details</button>
                                                </div>
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
    </div> --}}

    <section class="my-profile-sec">
        <div class="container">
            <div class="my-profile-wrapper">
                <h2>Account Settings</h2>
                <div class="my-profile-info-box">
                    <div class="row g-3">
                        <div class="col-md-12 me-0 ms-auto">
                            <div class="profile-edit-box">
                                {{-- <a href="{{ route('change-Profile') }}" class="button primary-btn"><i class="fa-solid fa-pen-to-square"></i> Edit</a> --}}
                                <a href="{{ route('change-Profile', $user) }}" class="button primary-btn">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="my-pro-detail">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Personal Info</p>
                                        <h4>{{ $user->name }}</h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="my-pro-detail">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Email</p>
                                        <h4>{{ $user->email }}</h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="my-pro-detail">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Address</p>
                                        <h4>{{$user->userDetail->address1}}</h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="my-pro-detail">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Bank</p>
                                        <h4>{{ $user->name }}, 2034338224</h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="my-pro-detail  notification-user-pro">
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Notification</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch" /><label for="switch">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Notification</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" checked id="switch1" /><label for="switch1">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </section>

@endsection
<!-- Add Card Modal -->
<div class="modal fade" id="addcardModal" tabindex="-1" aria-labelledby="addcardModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title text-center addcard-heading">Add Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="pay_mode">
                    <form class="my-profile-form" action="{{ route('card.store') }}" method="post"
                        id="addCardForm">
                        @csrf
                        <div class="hold_card">
                            <div class="left-card">
                                <img src="{{ asset('img/cccard.svg') }}">
                                <div class="card_text">
                                    <p>Credit/ Debit Card</p>
                                    <span>Fill your card details below</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="formfield">
                                <input type="text"
                                    class="form-control @error('card_holder_name') is-invalid @enderror"
                                    name="card_holder_name"
                                    value="{{ old('card_holder_name', $stripeCustomer && $stripeCustomer->name ? $stripeCustomer->name : $user->fullName) }}"
                                    placeholder="Card Holder Name" id="cardHolderName">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20"
                                        viewBox="0 0 15 20" fill="none">
                                        <path
                                            d="M7.61972 11.4658C9.04387 11.4658 10.4096 10.9002 11.4167 9.89312C12.4237 8.88618 12.9894 7.52026 12.9894 6.09628C12.9894 4.67212 12.4237 3.3062 11.4167 2.29926C10.4096 1.29233 9.04387 0.726562 7.61972 0.726562C6.19556 0.726562 4.82981 1.29233 3.8227 2.29926C2.81576 3.3062 2.25 4.67212 2.25 6.09628C2.25672 7.51836 2.82454 8.88031 3.83011 9.88588C4.83568 10.8915 6.19763 11.4593 7.61972 11.4658Z"
                                            fill="#DEE0E3"></path>
                                        <path
                                            d="M11.5 11.7188C11.3544 11.7203 11.2165 11.7846 11.1219 11.8952C10.2664 12.8582 9.05595 13.4317 7.76896 13.4834C6.4025 13.4948 5.0976 12.916 4.18904 11.8952C4.09443 11.7846 3.95656 11.7203 3.81094 11.7188C2.87724 11.7188 1.98196 12.0915 1.32417 12.7541C0.666202 13.4168 0.299982 14.3144 0.306732 15.2481V18.6516C0.306732 18.7853 0.35981 18.9135 0.454425 19.0082C0.549035 19.1026 0.677253 19.1558 0.810982 19.1558H14.4243C14.558 19.1558 14.6863 19.1026 14.7809 19.0082C14.8755 18.9135 14.9286 18.7853 14.9286 18.6516V15.2481C14.9489 14.3236 14.5973 13.4293 13.953 12.766C13.3085 12.1025 12.4249 11.7253 11.5 11.7188Z"
                                            fill="#DEE0E3"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="formfield">
                                <div id="cardNumberElement" class="form-control input-bg card"></div>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="16"
                                        viewBox="0 0 21 16" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.595703 2.38703V13.0762C0.595703 13.608 0.80686 14.1176 1.18296 14.4931C1.55845 14.8692 2.06817 15.0804 2.59992 15.0804H18.6336C19.1654 15.0804 19.6751 14.8692 20.0506 14.4931C20.4267 14.1176 20.6378 13.6079 20.6378 13.0762V2.38703C20.6378 1.85524 20.4267 1.34556 20.0506 0.970067C19.6751 0.593977 19.1654 0.382812 18.6336 0.382812H2.59992C2.06813 0.382812 1.55845 0.593969 1.18296 0.970067C0.806867 1.34556 0.595703 1.85528 0.595703 2.38703ZM19.3017 7.06353V13.0762C19.3017 13.2532 19.2316 13.4236 19.1059 13.5484C18.9809 13.674 18.8106 13.7442 18.6336 13.7442H2.59992C2.42291 13.7442 2.25246 13.6742 2.12765 13.5484C2.00209 13.4235 1.93185 13.2532 1.93185 13.0762V7.06353H19.3017ZM19.3017 3.0551H1.93185V2.38703C1.93185 2.21002 2.00193 2.03957 2.12764 1.91476C2.25261 1.78919 2.42291 1.71896 2.59991 1.71896H18.6336C18.8106 1.71896 18.9811 1.78905 19.1059 1.91476C19.2315 2.03972 19.3017 2.21002 19.3017 2.38703L19.3017 3.0551Z"
                                            fill="#DEE0E3"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="ex_date w-100">
                                <div class="form-group mb-0">
                                    <div class="formfield">
                                        <div id="cardExpiryElement" class="form-control input-bg expiry"></div>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17"
                                                viewBox="0 0 16 17" fill="none">
                                                <path
                                                    d="M14.5634 2.4375H13.2442V2.81287C13.2442 3.22536 13.0242 3.60653 12.667 3.81277C12.3096 4.01901 11.8695 4.01901 11.5123 3.81277C11.1551 3.60653 10.9351 3.22536 10.9351 2.81287V2.43769L5.494 2.4375V2.81287C5.494 3.22536 5.27403 3.60653 4.91683 3.81277C4.55943 4.01901 4.11932 4.01901 3.7621 3.81277C3.4049 3.60653 3.18494 3.22536 3.18494 2.81287V2.43769L1.81664 2.4375C1.4403 2.43692 1.07905 2.58576 0.812683 2.85173C0.546328 3.1175 0.396524 3.47857 0.396524 3.85489V5.98517H15.9836V3.85489C15.9836 3.47855 15.8338 3.1175 15.5674 2.85173C15.3011 2.58576 14.9397 2.43692 14.5634 2.4375ZM0.396484 7.1397V15.2855C0.396484 15.6619 0.546284 16.0227 0.812644 16.2887C1.079 16.5545 1.44028 16.7035 1.8166 16.7027H14.5634C14.9397 16.7035 15.3009 16.5545 15.5673 16.2887C15.8337 16.0227 15.9835 15.6619 15.9835 15.2855V7.1397H0.396484Z"
                                                    fill="#DEE0E3"></path>
                                                <path
                                                    d="M12.6586 1.02658V2.92875C12.6586 3.24749 12.4 3.50592 12.0813 3.50592C11.7623 3.50592 11.5039 3.24748 11.5039 2.92875V1.02658C11.5039 0.707652 11.7623 0.449219 12.0813 0.449219C12.4 0.449219 12.6586 0.707652 12.6586 1.02658Z"
                                                    fill="#DEE0E3"></path>
                                                <path
                                                    d="M4.87739 1.02658V2.92875C4.87739 3.24749 4.61876 3.50592 4.30002 3.50592C3.98109 3.50592 3.72266 3.24748 3.72266 2.92875V1.02658C3.72266 0.707652 3.98109 0.449219 4.30002 0.449219C4.61876 0.449219 4.87739 0.707652 4.87739 1.02658Z"
                                                    fill="#DEE0E3"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="cvv w-100">
                                <div class="form-group mb-0">
                                    <div class="formfield">
                                        <div id="cardCVCElement" class="form-control input-bg cvv"></div>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="17"
                                                viewBox="0 0 21 17" fill="none">
                                                <path
                                                    d="M2.38763 16.8487H18.8492C19.3949 16.8487 19.9183 16.6293 20.3041 16.2389C20.6901 15.8483 20.9068 15.3187 20.9068 14.7665V2.27362C20.9068 1.72148 20.6901 1.19182 20.3041 0.801239C19.9183 0.410835 19.3949 0.191406 18.8492 0.191406H2.38763C1.84198 0.191406 1.31855 0.410835 0.932739 0.801239C0.546755 1.19182 0.330078 1.72148 0.330078 2.27362V14.7665C0.330078 15.3187 0.546755 15.8483 0.932739 16.2389C1.31855 16.6293 1.84198 16.8487 2.38763 16.8487ZM2.38763 4.35583H18.8492V8.52008H2.38763V4.35583ZM3.15925 11.9038H7.27452L7.2747 11.9036C7.55028 11.9036 7.805 12.0524 7.94279 12.294C8.08075 12.5356 8.08075 12.8333 7.94279 13.0748C7.805 13.3164 7.55026 13.4652 7.2747 13.4652H3.15925C2.88367 13.4652 2.62894 13.3164 2.49115 13.0748C2.35319 12.8333 2.35319 12.5356 2.49115 12.294C2.62894 12.0524 2.88368 11.9036 3.15925 11.9036L3.15925 11.9038Z"
                                                    fill="#DEE0E3"></path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-holder mt-2">
                            <button type="submit" class="btn btn-dark" id="payButton">Save Card</button>
                        </div>
                    </form>
                    <!--end of choose Payment Mode area-->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Add Card Modal -->

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripeKey = '{{ $stripePublicKey }}';
        const clientSecret = "{{ $intent->client_secret }}";
        const deleteCardRoute = '{{ route('card.delete') }}';
        const defaultCardRoute = '{{ route('card.default') }}';
        let defaultCardId = '{{ $cards->where('is_default', 1)->pluck('id')->first() ?? '' }}';
    </script>
    <script src="{{ asset('js/custom/notification-setting.js') }}"></script>
    <script src="{{ asset('js/custom/toggle-password.js') }}"></script>
    <script src="{{ asset('js/custom/profile.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/custom/card.js') }}"></script>

    <script>
        const stateId = '{{ auth()->user()->userDetail ? auth()->user()->userDetail->state_id : '' }}';
        const cityId = '{{ auth()->user()->userDetail ? auth()->user()->userDetail->city_id : '' }}';

        jQuery('#payButton').on('click', function() {
            jQuery('#nav-contact-tab');
        })
        jQuery(document).ready(function() {

            var tab = `{{ request()->tab }}`;

            if (tab) {

                $('.nav-tabs a[href="#' + tab + '"]').tab('show');

            }

        })

        window.pressed = function() {
            var a = document.getElementById('editprofileimg');
            if (a.value == "") {
                fileLabel.innerHTML = "Choose file";
            } else {
                var theSplit = a.value.split('\\');
                fileLabel.innerHTML = theSplit[theSplit.length - 1];
            }
        };

        jQuery('#editprofileimg').on('change', function() {
            var element = jQuery('#editprofileimg');

            if (element.get(0).files.length > 0) {
                jQuery('.close-file').removeClass('d-none');
            }
        })
        jQuery('.close-file').on('click', function() {
            // console.log('herere');
            jQuery('#fileLabel').text('No file selected');
            document.getElementById("editprofileimg").value = null;
            jQuery('.close-file').addClass('d-none');

        })
    </script>

    <script>
        const rule = {
            account_holder_first_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            account_holder_last_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            // account_type: {
            //     required: true,
            //     inarray: ['Custom', 'Express', 'Standard']
            // },
            account_number: {
                required: true,
                minlength: 10,
                maxlength: 12,
            },
            routing_number: {
                required: true,
                minlength: 9,
                maxlength: 9,
            },
        };
        const message = {
            account_holder_first_name: {
                required: 'This field is required.',
                regex: 'Account holder first name should not contain any spaces, only alphabetical characters are allowed.'
            },
            account_holder_last_name: {
                required: 'This field is required.',
                regex: 'Account holder first name should not contain any spaces, only alphabetical characters are allowed.'
            },
            // account_type: {
            //     required: 'Please select the account type',
            //     inarray: 'Account type can be Custom, Express or Standard'
            // },
            account_number: {
                required: 'This field is required.',
                minlength: 'Account number must be 10-12 digits.',
                maxlength: 'Account number must be 10-12 digits.',
            },
            routing_number: {
                required: 'This field is required.',
                minlength: 'Routing number must be 9 digits',
                maxlength: 'Routing number must be 9 digits',
            },
        };
        handleValidation('bankDetail', rule, message);

        // END
    </script>
@endpush
