@extends('layouts.front')
@section('title', 'My Profile')
@section('links')

@endsection

@section('content')

    <section class="my-profile-sec">
        <div class="container">
            <div class="my-profile-wrapper">
                <h2>My Profile</h2>
                <div class="my-profile-info-box">
                    <form action="{{ route('saveUserprofile') }}" method="POST" id="save_user">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Personal Info</p>
                                            <div class="my-pro-edit-form">
                                                <div class="form-group">
                                                    <div class="formfield">
                                                        <input type="text" name="name" id="name"
                                                            class="form-control form-class @error('name') is-invalid @enderror"
                                                            placeholder="Name" value="{{ $user->name }}">
                                                    </div>
                                                </div>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Email</p>
                                            <div class="my-pro-edit-form">
                                                <div class="form-group">
                                                    <div class="formfield">
                                                        <input type="email" name="email" id="email"
                                                            class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                                            value="{{ $user->email }}">
                                                    </div>
                                                </div>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Address</p>
                                            <div class="my-pro-edit-form">
                                                <div class="form-group">
                                                    <div class="formfield">
                                                        <textarea name="complete_address" id="address" placeholder="Address" class="form-control @error('complete_address') is-invalid @enderror">{{ $user->userDetail->address1 }}</textarea>
                                                    </div>
                                                </div>
                                                @error('complete_address')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Bank</p>
                                            <div class="my-pro-edit-form">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <input type="text" name="account_holder_first_name"
                                                                    class="form-control @error('account_holder_first_name') is-invalid @enderror"
                                                                    placeholder="Account Holder First Name"
                                                                    value="{{ explode(' ', $user->name)[0] ?? '' }}">
                                                            </div>
                                                            @error('account_holder_first_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <input type="text" name="account_holder_last_name"
                                                                    class="form-control @error('account_holder_last_name') is-invalid @enderror"
                                                                    placeholder="Account Holder Last Name"
                                                                    value="{{ explode(' ', $user->name)[1] ?? '' }}">
                                                            </div>
                                                            @error('account_holder_last_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <input type="text" name="date_of_birth"
                                                                    class="form-control @error('date_of_birth') is-invalid @enderror" placeholder="Date of Birth"
                                                                    value="02/01/2002">
                                                            </div>
                                                            @error('date_of_birth')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <input type="text" name="account_number"
                                                                    class="form-control @error('account_number') is-invalid @enderror" placeholder="Account Number"
                                                                    value="537947293472">
                                                            </div>
                                                            @error('account_number')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <input type="text" name="routing_number"
                                                                    class="form-control @error('routing_number') is-invalid @enderror" placeholder="Routing Number"
                                                                    value="000123456789">
                                                            </div>
                                                            @error('routing_number')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="profile-edit-btn">
                                    <button type="submit" class="button primary-btn">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    @includeFirst(['validation'])
    <script src="{{ asset('js/custom/notification-setting.js') }}"></script>
    <script src="{{ asset('js/custom/toggle-password.js') }}"></script>
    <script src="{{ asset('js/custom/profile.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/custom/card.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            $("#save_user").find('button').attr('disabled', false);
            const rules = {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                    regex: emailRegex,
                },
                complete_address: {
                    required: true,
                },
                account_holder_first_name: {
                    required: true,
                },
                account_holder_last_name: {
                    required: true,
                },
                date_of_birth: {
                    required: true,
                },
                account_number: {
                    required: true,
                },
                routing_number: {
                    required: true,
                }
            }
            const messages = {
                name: {
                    required: `{{ __('customvalidation.user.name.required') }}`,
                },
                email: {
                    required: `{{ __('customvalidation.user.email.required') }}`,
                    email: `{{ __('customvalidation.user.email.email') }}`,
                    regex: `{{ __('customvalidation.user.email.regex', ['regex' => '${emailRegex}']) }}`,
                },
                complete_address: {
                    required: `{{ __('customvalidation.user.complete_address.required') }}`,
                },
                account_holder_first_name: {
                    required: 'This field is required.',
                },
                account_holder_last_name: {
                    required: 'This field is required.',
                },
                date_of_birth: {
                    required: 'This field is required.',
                },
                account_number: {
                    required: 'This field is required.',
                },
                routing_number: {
                    required: 'This field is required.',
                }
            };

            handleValidation('save_user', rules, messages);
        });
    </script>
@endpush
