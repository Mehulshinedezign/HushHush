@extends('layouts.front')
@section('title', 'My Profile')
@section('links')
    @php
        $user = auth()->user();
    @endphp
@endsection

@section('content')
    @php
        $user = auth()->user();
    @endphp



    <section class="my-profile-sec cust-form-bg fill-hight">
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
                        <div class="col-md-12">
                            <div class="my-pro-dp-view">
                                @if ($user->profile_file)
                                    <img src="{{ asset('storage/' . $user->profile_file) }}" alt="Profile Picture">
                                @else
                                    <img src="{{ asset('front/images/pro3.png') }}" alt="Default Image">
                                @endif
                                {{-- <img src="{{asset('front/images/pro3.png')}}" alt="img"> --}}
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
                                        <h4>{{ $user->userDetail->complete_address ?? ''}}</h4>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @if (!$user->userBankInfo)
                            <div class="col-md-12">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4>Your bank account details is not store please submit your bank details. <a href="{{ route('stripe.onboarding.redirect') }}">Submit</a> </h4>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        @endif
                        {{-- <div class="col-md-12">
                            <div class="my-pro-detail">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Bank</p>
                                        <h4>{{ $user->name }}, 2034338224</h4>
                                    </div>
                                </div>

                            </div>
                        </div> --}}
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
                                <!-- <div class="my-pro-notify-box">
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
                                        </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('scripts')
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
