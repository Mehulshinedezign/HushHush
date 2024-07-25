@extends('layouts.admin')
@section('content')
    @php
        $user = auth()->user();
    @endphp

    <div class="section-body">
        <x-admin_alert />
        <div class="row">
            {{-- <div class="col-12 col-md-12 col-lg-4">
            <div class="card author-box">
                <div class="card-header">
                    <h4>{{ __('user.myaccount') }}</h4>
                </div>
                <div class="card-body">
                    <div class="author-box-center">
                        <div class="admin-profile-img">
                            <img src="{{ $user->frontend_profile_url }}" alt="user-img" class="rounded-circle author-box-picture">
                            @if (isset($user->profile_url))
                            <a class="remove-profile-pic" href="{{ route('admin.removeprofile') }}" title="Remove Profile"> <i class="fas fa-times"></i></a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <div class="author-box-name">
                            <a href="#">{{ $user->name }}</a>
                        </div>
                        <div class="author-box-job">{{ $user->role->name }}</div>
                    </div>
                    <div class="py-4">
                        <p class="clearfix">
                            <span class="float-left">
                                {{ __('user.name') }}
                            </span>
                            <span class="float-right text-muted">
                                {{ $user->name }}
                            </span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left">
                                {{ __('user.phone') }}
                            </span>
                            <span class="float-right text-muted">
                                {{ $user->phone_number }}
                            </span>
                        </p>
                        <p class="clearfix">
                            <span class="float-left">
                                {{ __('user.email') }}
                            </span>
                            <span class="float-right text-muted">
                                {{ $user->email }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}

            <div class="col-12 col-md-12 col-lg-8">
                <div class="card">
                    <div class="padding-20">
                        <ul class="nav nav-tabs" id="myTab2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (!$errors->has('current_password') && !$errors->has('new_password') && !session('error')) active @endif" id="profile-tab2"
                                    data-toggle="tab" href="#settings" role="tab"
                                    aria-selected="true">{{ __('user.setting') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if ($errors->has('current_password') || $errors->has('new_password') || session('error')) active @endif" id="home-tab2"
                                    data-toggle="tab" href="#changePassword" role="tab"
                                    aria-selected="false">{{ __('user.changePassword') }}</a>
                            </li>
                        </ul>

                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade @if (!$errors->has('current_password') && !$errors->has('new_password') && !session('error')) show active @endif" id="settings"
                                role="tabpanel" aria-labelledby="profile-tab2">
                                <form method="post" id="userDetail" action="{{ route('admin.saveuserdetail') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header">
                                        <h4>{{ __('user.editProfile') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12 floating-addon">
                                                <label>{{ __('user.fields.name') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text icon">
                                                            <i class="fa-solid fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ $user->name }}" placeholder="Enter name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-12 floating-addon">
                                                <label>{{ __('user.fields.email') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text icon">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                    </div>
                                                    {{-- <input type="email" name="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ $user->email }}" placeholder="Enter email address"> --}}
                                                        <p class="form-control">{{$user->email}}</p>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12 floating-addon">
                                                <label>{{ __('user.fields.phone') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text icon">
                                                            <i class="fas fa-phone"></i>
                                                        </div>
                                                    </div>
                                                    <input id="phone_number" type="text"
                                                        class="form-control @error('phone_number') is-invalid @enderror"
                                                        name="phone_number" value="{{ $user->phone_number }}" placeholder="Enter phone number">
                                                    @error('phone_number')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="form-group col-md-6 col-12 floating-addon">
                                            <label>{{ __('user.fields.uploadProfilePic') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-upload"></i>
                                                    </div>
                                                </div>
                                                <input id="profile_pic" type="file" class="form-control @error('profile_pic') is-invalid @enderror" name="profile_pic" accept="image/png, image/jpeg, image/jpg">
                                                @error('profile_pic')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                        </div>
                                        <button id="userSave"
                                            class="btn btn-primary">{{ __('buttons.saveChanges') }}</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade @if ($errors->has('current_password') || $errors->has('new_password') || session('error')) show active @endif"
                                id="changePassword" role="tabpanel" aria-labelledby="home-tab2">
                                <form method="post" id="changePassword" action="{{ route('admin.updatepassword') }}">
                                    @csrf
                                    <div class="card-header">
                                        <h4>Update Password</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                                <label>Current Password</label>
                                                <div class="pass-field">
                                                    <input type="password" placeholder="Current Password"
                                                        name="current_password" class="form-control">
                                                    <span
                                                        class="input-group-text bg-transparent border-left-0 icon toggle-password"><i
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                                @error('current_password')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                                <label>New Password</label>
                                                <div class="pass-field">
                                                    <input type="password" placeholder="New Password" name="new_password"
                                                        id="newPassword" class="form-control">
                                                    <span
                                                        class="input-group-text bg-transparent border-left-0 icon toggle-password"><i
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                                @error('new_password')
                                                    <label class="error-messages">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 form-group view-password">
                                                <label>Confirm New Password</label>
                                                <div class="pass-field">
                                                    <input type="password" placeholder="Confirm New Password"
                                                        name="confirm_password" class="form-control">
                                                    <span
                                                        class="input-group-text bg-transparent border-left-0 icon toggle-password"><i
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('buttons.changePassword') }}</button>
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
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom/toggle-password.js') }}"></script>
    <script src="{{ asset('js/custom/profile.js') }}"></script>
@endpush
