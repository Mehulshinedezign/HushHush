 @extends('layouts.admin')
 @section('content')
     <div class="section-body">
         <div class="row">
             <div class="col-12 col-md-12 col-lg-12">
                 <div class="card">
                     <form action="{{ route('admin.update.user', ['user' => $user->id]) }}" method="post"
                         enctype="multipart/form-data" id="userDetail">
                         @csrf
                         <div class="card-body">
                             <div class="form-row divider-bottom">
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.name') }}<span class="text-danger">*</span></label>
                                     <input type="text" name="name"
                                         class="form-control @error('name') is-invalid @enderror"
                                         value="{{ $user->name }}" placeholder="{{ __('user.placeholders.name') }}">
                                     @error('name')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                                 <!-- <div class="form-row"> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.email') }}<span class="text-danger">*</span></label>
                                     {{-- <input type="email" name="email"
                                         class="form-control @error('name') is-invalid @enderror"
                                         value="{{ $user->email }}" placeholder="{{ __('user.placeholders.email') }}"> --}}
                                         <p class="form-control"> {{ $user->email }}</p>
                                     @error('email')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>

                                 <!-- <div class="form-row"> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.phone') }}<span class="text-danger">*</span></label>
                                     <input type="text" name="phone_number"
                                         class="form-control @error('name') is-invalid @enderror"
                                         value="{{ $user->phone_number }}"
                                         placeholder="{{ __('user.placeholders.phone') }}">
                                     @error('phone_number')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>

                                 <!-- <div class="form-row"> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.uploadProfilePic') }}<span
                                             class="text-danger"></span></label>
                                     <input type="file" name="profile_pic"
                                         class="form-control @error('name') is-invalid @enderror"
                                         placeholder="{{ __('user.placeholders.profile_pic') }}"
                                         accept="image/png, image/jpeg, image/jpg">
                                         {{-- @if ($user->profile_file)
                                            <img id="preview-img" src="{{ asset('storage/' . $user->profile_file) }}"
                                                alt="Profile Picture">
                                        @else
                                            <img id="preview-img" src="{{ asset('front/images/pro3.png') }}"
                                                alt="Default Image">
                                        @endif --}}
                                     @error('profile_pic')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>

                                 <!-- <div class="form-row"> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.address1') }}<span class="text-danger"></span></label>
                                     <input type="text" name="address1"
                                         class="form-control @error('address1') is-invalid @enderror"
                                         placeholder="{{ __('user.placeholders.address1') }}"
                                         value="{{ old('address1', !is_null($user->userDetail) ? $user->userDetail->address1 : '') }}">

                                     @error('address1')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>

                                 <!-- <div class="form-row"> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.address2') }}<span class="text-danger"></span></label>
                                     <input type="text" name="address2"
                                         class="form-control @error('address2') is-invalid @enderror"
                                         placeholder="{{ __('user.placeholders.address2') }}"
                                         value="{{ old('address2', !is_null($user->userDetail) ? $user->userDetail->address2 : '') }}">
                                     @error('address2')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                                 <!-- </div> -->
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.country') }}</label>
                                     <select name="country" id="country"
                                         class="form-control @error('main_category') is-invalid @enderror">
                                         <option value="">Select country</option>
                                         @foreach ($countries as $country)
                                             <option value="{{ $country->name }}" data-country_id="{{$country->id}}"
                                                 @if ($country->name == $selectedCountryName) selected @endif>{{ $country->name }}
                                             </option>
                                         @endforeach
                                     </select>
                                     @error('country')
                                         <label class="error-messages">{{ $message }}</label>
                                     @enderror
                                     </select>
                                 </div>
                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.state') }}</label>
                                     <select name="state" id="state"
                                         class="form-control @error('main_category') is-invalid @enderror">
                                         <option value="">Select state</option>
                                         @foreach ($states as $state)
                                            <option value="{{ $state->name }}" data-state_id="{{$state->id}}"
                                                @if ($state->name == (isset($user->userDetail) ? $user->userDetail->state : '')) selected @endif>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                     </select>
                                     @error('state')
                                         <label class="error-messages">{{ $message }}</label>
                                     @enderror
                                     </select>
                                 </div>

                                 <div class="form-group col-md-4">
                                     <label>{{ __('user.fields.city') }}</label>
                                     <select name="city" id="city"
                                         class="form-control @error('main_category') is-invalid @enderror">
                                         <option value="">Select city</option>
                                         @foreach ($cities as $city)
                                             <option value="{{ $city->name }}"
                                                @if ($city->name == (isset($user->userDetail) ? $user->userDetail->city : '')) selected @endif>
                                                {{ $city->name }}
                                             </option>
                                         @endforeach
                                     </select>
                                     @error('city')
                                         <label class="error-messages">{{ $message }}</label>
                                     @enderror
                                     </select>
                                 </div>

                                 <div class="form-group col-md-4">
                                    <label>Gov Id</label>
                                    <div class="formfield">
                                       <input type="file" name="gov_id"
                                           class="form-control @error('gov_id') is-invalid @enderror"
                                           placeholder="Gov Id">
                                    </div>
                                    @error('gov_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                 <div class="form-group col-md-4">
                                     <label>Password</label>
                                     <div class="formfield">
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Password">
                                            <span class="form-icon extra-icon">
                                                <i class="fa-solid fa-lock togglePassword"></i>
                                            </span>
                                     </div>
                                     @error('password')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>

                                 <div class="form-group col-md-4">
                                     <label>Confirm password</label>
                                     <div class="formfield">
                                        <input type="password" name="confirm_password"
                                            class="form-control @error('confirm_password') is-invalid @enderror"
                                            placeholder="Confirm password">
                                            <span class="form-icon extra-icon">
                                                <i class="fa-solid fa-lock togglePassword"></i>
                                            </span>
                                     </div>
                                     @error('confirm_password')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                                 <div class="form-group col-md-8">
                                     <label>About</label>
                                     <textarea name="about" class="form-control" placeholder="About">{{ @$user->userDetail->about }}</textarea>
                                 </div>
                             </div>
                             {{-- <div class="form-row-card divider-bottom">
                                 <h4 class="form-heading-label">Documents Details</h4>
                                 <div class="form-group-cart">
                                     <ul class="listing-row ">
                                         @if ($user->documents->isNotEmpty())
                                             <li class="list-col">
                                                 <span class="list-item">ID Proof</span>
                                                 <span class="download-icon">
                                                     <a href="{{ route('admin.downloadproof', [$user->id]) }}"
                                                         target="_blank">
                                                         Download Proof
                                                         <span class="download-file-icon"><span
                                                                 class="icon-box small-icon"><i
                                                                     class="fas fa-arrow-down"></i></span></span>
                                                     </a>
                                                 </span>
                                             </li>
                                         @endif
                                     </ul>

                                     @if ($user->is_approved == 1)
                                         <span class="btn btn-info">Approved</span>
                                     @elseif($user->documents->isNotEmpty())
                                         @if ($user->role_id == 2)
                                             <a href="{{ route('admin.approvevendor', [$user->id]) }}"
                                                 class="btn btn-primary">Approve</a>
                                         @else
                                             <a href="{{ route('admin.approvecustomer', [$user->id]) }}"
                                                 class="btn btn-primary">Approve</a>
                                         @endif
                                     @else
                                         <span class="btn btn-warning">Documents not uploaded</span>
                                     @endif
                                 </div>
                             </div> --}}
                             <div class="form-row-card">
                                 <h4 class="form-heading-label">Notification Settings</h4>
                                 <div class="form-group-cart">
                                     <ul class="ven-notifi-setting notification-status">
                                         <li>
                                             <div class="personal-info-row mb-0">
                                                 <div class="toggle_cstm notification-status-box">
                                                     <div>
                                                         <span>Order Placed</span>
                                                     </div>
                                                     <label>
                                                         <input type="checkbox" class="custom-switch-input"
                                                             name="order_placed" onchange="notification(this)"
                                                             @if (@$user->notification->order_placed == 'on') checked @endif>
                                                         <span class="custom-switch-indicator"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="personal-info-row mb-0">
                                                 <div class="toggle_cstm notification-status-box">
                                                     <div>
                                                         <span>Order Pickup</span>
                                                     </div>
                                                     <label>
                                                         <input type="checkbox" class="custom-switch-input"
                                                             name="order_pickup" onchange="notification(this)"
                                                             @if (@$user->notification->order_pickup == 'on') checked @endif>
                                                         <span class="custom-switch-indicator"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="personal-info-row mb-0">
                                                 <div class="toggle_cstm notification-status-box">
                                                     <div>
                                                         <span>Order Return</span>
                                                     </div>
                                                     <label>
                                                         <input type="checkbox" class="custom-switch-input"
                                                             name="order_return" onchange="notification(this)"
                                                             @if (@$user->notification->order_return == 'on') checked @endif>
                                                         <span class="custom-switch-indicator"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="personal-info-row mb-0">
                                                 <div class="toggle_cstm notification-status-box">
                                                     <div>
                                                         <span>Order Cancelled</span>
                                                     </div>
                                                     <label>
                                                         <input type="checkbox" class="custom-switch-input"
                                                             name="order_cancelled" onchange="notification(this)"
                                                             @if (@$user->notification->order_cancelled == 'on') checked @endif>
                                                         <span class="custom-switch-indicator"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </li>
                                         <li>
                                             <div class="personal-info-row mb-0">
                                                 <div class="toggle_cstm notification-status-box">
                                                     <div>
                                                         <span>Payment</span>
                                                     </div>
                                                     <label>
                                                         <input type="checkbox" class="custom-switch-input"
                                                             name="payment" onchange="notification(this)"
                                                             @if (@$user->notification->payment == 'on') checked @endif>
                                                         <span class="custom-switch-indicator"></span>
                                                     </label>
                                                 </div>
                                             </div>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                             <div class="card-footer-form">
                                 <button class="btn btn-primary" type="submit">{{ __('buttons.save') }}</button>
                             </div>
                         </div>
                 </div>
                 </form>
             </div>
         </div>
     </div>
     </div>
 @endsection

 @push('scripts')
 @includeFirst(['validation.js_show_password'])
     <script src="{{ asset('js/custom/profile.js') }}"></script>
     {{-- <script src="{{ asset('js/custom/notification-setting.js') }}"></script>
     <script src="{{ asset('js/custom/toggle-password.js') }}"></script>
     <script src="{{ asset('js/custom/profile.js') }}"></script>
     <script src="{{ asset('js/sweetalert.min.js') }}"></script>
     <script>
         const stateId = '{{ auth()->user()->userDetail ? auth()->user()->userDetail->state_id : '' }}';
         const cityId = '{{ auth()->user()->userDetail ? auth()->user()->userDetail->city_id : '' }}';
     </script> --}}
 @endpush
