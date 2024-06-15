@extends('layouts.retailer')
@section('title', 'My Profile')
@section('links')
<script src="https://cdn.tiny.cloud/1/rcqfj1cr6ejxsyuriqt95tnyc64joig2nppf837i8qckzy90/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            branding: false,
        });
        const notify_url = "{{ route('retailer.notificationsetting') }}";
    </script>
@stop
@section('content')
    @php
        $user = auth()->user();
    @endphp
    <div class="right-content innerpages">
        <div class="vendor-profile-sec">
            <div class="innerbox-container">
                <h5 class="order-heading mb-0">My Account</h5>
                <x-alert/>
                <hr class="h-border">
                <div class="vend-pro-box">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-5 col-lg-4">
                            <div class="profile-view-outer">
                                <div class="profile-header por_holder">
                                    <div class="profile-big-image">
                                        @if(isset($user->profile_url))
                                            <img src="{{ Storage::url($user->profile_url) }}">
                                        @else
                                            <img src="{{ asset('img/avatar-small.png') }}">
                                        @endif
                                    </div>
                                    <div class="profile_content pro_content">
                                        <h6 class="m-0">{{ $user->name }}</h6>
                                        <p class="smallFont">{{ ucfirst($user->role->name) }}</p>
                                    </div>
                                </div>
                                <div class="profile-view-body">
                                    <ul class="listing-row">
                                        <li class="list-col auto-width">
                                            <span class="list-item">Email:</span>
                                            <span class="">{{ $user->email }}</span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">Phone Number:</span>
                                            <span>{{ !is_null($user->phone_number) ? @$user->phone_number : $notAvailable }}</span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">Address:</span>
                                            <span>{{ !is_null($user->userDetail) ? @$user->userDetail->address1 : $notAvailable }} @if(isset($user->userDetail->address2)) , {{ $user->userDetail->address2 }} @endif</span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">{{ __('user.country') }}:</span>
                                            <span>{{ !is_null($user->userDetail) ?  @$user->userDetail->country->name : $notAvailable }} </span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">{{ __('user.state') }}:</span>
                                            <span>{{ !is_null($user->userDetail) ? @$user->userDetail->state->name : $notAvailable }}</span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">{{ __('user.city') }}:</span>
                                            <span>{{ !is_null($user->userDetail) ? @$user->userDetail->city->name : $notAvailable }}</span>
                                        </li>
                                        <li class="list-col auto-width">
                                            <span class="list-item">{{ __('user.zip') }}:</span>
                                            <span>{{ !is_null($user->zipcode) ? @$user->zipcode : $notAvailable }}</span>
                                        </li>
                                        @if ($user->documents->isNotEmpty())
                                        <li class="list-col auto-width">
                                            <span class="list-item">{{ __('user.idProof') }}:</span>
                                            <span class=""><a href="{{ route('retailer.download-proof') }}" target="_blank"> <i class="fas fa-download"></i> </a> </span>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-7 col-lg-8">
                            <div class="profile-form-outer">
                                <nav class="mb-3">
                                    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                        <button class="nav-link @if (!$errors->has('old_password') && !$errors->has('new_password') && !session('error')) active @endif" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                                           Edit Profile
                                        </button>
                                        <button class="nav-link @if ($errors->has('old_password') || $errors->has('new_password') || session('error')) active @endif" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Change Password</button>
                                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Notification Settings</button>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show @if (!$errors->has('old_password') && !$errors->has('new_password') && !session('error')) active @endif" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <form class="profile-detail" method="post" id="userDetail" action="{{ route('retailer.saveuserdetail') }}" enctype="multipart/form-data" autocomplete="off">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                                    <label>{{ __('Switch Profile') }}</label>
                                                    <div class="personal-info-row mb-0 account_switch">
                                                        <div>
                                                            <span>Borrower</span>
                                                        </div>
                                                        <div class="toggle_cstm d-flex items-center ">
                                                            <input type="checkbox" name="user_role" value="{{ $user->role_id }}" @if($user->role_id == '2') checked="checked" @endif>
                                                        </div>
                                                        <div>
                                                            <span>Lender</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Username</label>
                                                    <div class="formfield"> 
                                                    <input type="text" placeholder="Username" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                                                    <span class="icon">
                                                          <i class="fa-solid fa-user"></i>
                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Full Name<span class="text-danger">*</span></label>
                                                    <div class="formfield">
                                                    <input type="text" placeholder="{{ __('user.placeholders.name') }}" name="name" class="form-control required" value="{{ old('name', $user->name) }}">
                                                    <span class="icon">
                                                         <i class="fa-solid fa-user"></i>
                                                    </span>
                                                    </div>
                                                    @error('name')
                                                        <label class="error-messages">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Phone Number<span class="text-danger">*</span></label>
                                                    <div class="formfield">
                                                        <input type="text" placeholder="{{ __('user.placeholders.phone') }}" name="phone_number" class="form-control required" value="{{ old('phone_number', $user->phone_number) }}">
                                                        <span class="icon">
                                                            <i class="fa-solid fa-phone"></i>
                                                        </span>
                                                    </div>
                                                    @error('phone_number')
                                                        <label class="error-messages">{{ $message }}</label>
                                                    @enderror  
                                                </div>
                                                @if ($user->is_approved == 0)
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                        <label>Update ID Proof</label>
                                                        <div class="form-control input-file-uploader">
                                                            <input type="file" name="proof" class="form-control" accept="{{ str_replace(["[", "'", "]"],["","",""], $global_js_proof_extension) }}">
                                                            @error('proof')
                                                                <label class="error-messages">{{ $message }}</label>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Address 1<span class="text-danger">*</span></label>
                                                    <div class="formfield"> 
                                                    <input type="text" placeholder="{{ __('user.placeholders.address1') }}" name="address1" class="form-control form-class" value="{{ old('address1', @$user->userDetail->address1) }}">
                                                    <span class="icon">
                                                     <i class="fa-solid fa-location-dot"></i>
                                                    </span>
                                                    </div>
                                                    @error('address1')
                                                        <label class="error-messages">{{ $message }}</label>
                                                    @enderror
                                                </div>   
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Address 2</label>
                                                    <div class="formfield"> 
                                                    <input type="text" placeholder="{{ __('user.placeholders.address2') }}" name="address2" class="form-control" value="{{ old('address2', @$user->userDetail->address2) }}">
                                                    <span class="icon">
                                                         <i class="fa-solid fa-location-dot"></i>
                                                    </span>
                                                    </div>
                                                </div>   
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Country<span class="text-danger">*</span></label>
                                                    <div class="select-option select_box">
                                                        <select name="country" id="country" class="form-select">
                                                            <option selected>{{ __('user.placeholders.country') }}</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}" @if($country->id == $selectedCountryId) selected @endif>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('country')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>State<span class="text-danger">*</span></label>
                                                    <div class="select-option select_box">
                                                    <select name="state" id="state" class="form-select">
                                                        <option value="">{{ __('user.placeholders.state') }}</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->id }}" @if($state->id == @$user->userDetail->state_id) selected @endif>{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('state')
                                                        <label class="error-messages">{{ $message }}</label>
                                                    @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>City<span class="text-danger">*</span></label>
                                                    <div class="select-option select_box">
                                                    <select name="city" id="city" class="form-select">
                                                        <option value="">{{ __('user.placeholders.city') }}</option>
                                                    </select>
                                                    @error('city')
                                                        <label class="error-messages">{{ $message }}</label>
                                                    @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Zip Code<span class="text-danger">*</span></label>
                                                    <input type="text" placeholder="{{ __('user.placeholders.zip') }}" name="postcode" class="form-control required" value="{{ old('postcode', $user->zipcode) }}">
                                                    @error('postcode')
                                                        <label class="error-messages">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                    <label>Upload Profile Pic</label>
                                                    <div class="form-control input-file-uploader">
                                                        <input type="file" name="profile_pic" class="form-control" accept="{{ str_replace(["[", "'", "]"],["","",""], $global_js_image_extension) }}">
                                                        @error('profile_pic')
                                                            <label class="error-messages">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 form-group">
                                                    <label>About</label>
                                                    <textarea class="form-control" name="about" cols="30" rows="10">{{ old('about', @$user->userDetail->about) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                                    <button type="submit" class="btn btn_black">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade @if ($errors->has('old_password') || $errors->has('new_password') || session('error')) active @else fade @endif" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <form class="change-password" method="post" id="changePassword" action="{{ route('retailer.updatepassword') }}" autocomplete="off">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                                    <label>Current Password<span class="text-danger">*</span></label>
                                                    <div class="formfield">
                                                        <input type="password" placeholder="Current Password" name="current_password" class="form-control">
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
                                                    <label>New Password<span class="text-danger">*</span></label>
                                                    <div class="formfield">
                                                        <input type="password" placeholder="New Password" name="new_password" id="newPassword" class="form-control">
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
                                                    <label>Confirm New Password<span class="text-danger">*</span></label>
                                                    <div class="formfield">
                                                        <input type="password" placeholder="Confirm New Password" name="confirm_password" class="form-control">
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
                                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                        <ul class="ven-notifi-setting">
                                            <li>
                                                <div class="personal-info-row mb-0">
                                                    <div>
                                                        <span>Order Placed</span>
                                                    </div>
                                                    <div class="toggle_cstm">
                                                        <input type="checkbox" name="order_placed" onchange="notification(this)" @if(@$user->notification->order_placed == 'on') checked @endif>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="personal-info-row mb-0">
                                                    <div>
                                                        <span>Order Cancelled</span>
                                                    </div>
                                                    <div class="toggle_cstm">
                                                        <input type="checkbox" name="order_cancelled" onchange="notification(this)" @if(@$user->notification->order_cancelled == 'on') checked @endif>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="personal-info-row mb-0">
                                                    <div>
                                                        <span>Pick Up</span>
                                                    </div>
                                                    <div class="toggle_cstm">
                                                        <input type="checkbox" name="order_pickup" onchange="notification(this)" @if(@$user->notification->order_pickup == 'on') checked @endif>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="personal-info-row mb-0">
                                                    <div>
                                                        <span>Return</span>
                                                    </div>
                                                    <div class="toggle_cstm">
                                                        <input type="checkbox" name="order_return" onchange="notification(this)" @if(@$user->notification->order_return == 'on') checked @endif>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="personal-info-row mb-0">
                                                    <div>
                                                        <span>Payment</span>
                                                    </div>
                                                    <div class="toggle_cstm">
                                                        <input type="checkbox" name="payment" onchange="notification(this)" @if(@$user->notification->payment == 'on') checked @endif>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom/notification-setting.js') }}"></script>
    <script src="{{ asset('js/custom/toggle-password.js') }}"></script>
    <script src="{{ asset('js/custom/profile.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        const stateId = '{{ (auth()->user()->userDetail) ? auth()->user()->userDetail->state_id : '' }}';
        const cityId = '{{ (auth()->user()->userDetail) ? auth()->user()->userDetail->city_id : '' }}';
        jQuery(document).on("change", 'input[name="user_role"]', function() {
            if(!$(this).is(":checked")){
                $(this).attr('checked', false)
            }else{
                $(this).attr('checked', false)
            }
            swal({
                title: 'Are you sure?',
                text: 'You want to switch profile',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                buttons: ["No", "Yes"],
            })
            .then((done) => {
                if (done) {
                    window.location.replace(APP_URL+'/retailer/switch-profile/3')
                }else{
                    $('input[name="user_role"]').attr('checked', true);
                }
            });
        });
    </script>
@endpush