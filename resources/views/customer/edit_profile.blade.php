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
                                        <p>User Name</p>
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
                                            <h4>Your bank account details is not stored please submit your bank details. <a href="{{ route('stripe.onboarding.redirect') }}">Submit</a> </h4>

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
                                            <h4 class="m-0">Lender receive query</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch" name="query_receive" @if (@$user->usernotification->query_receive == '1') checked="checked" @endif><label for="switch">Toggle</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Customer query accepted</h4>
                                        </div>
                                    </div>
                                    {{-- @dd(@$user->usernotification); --}}
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch1" name="accept_item" @if (@$user->usernotification->accept_item == '1') checked="checked" @endif><label
                                                for="switch1">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Customer query rejected</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch2" name="reject_item" @if (@$user->usernotification->reject_item == '1') checked="checked" @endif><label
                                                for="switch2">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Lender Order book</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch3" name="order_req" @if (@$user->usernotification->order_req == '1') checked="checked" @endif><label
                                                for="switch3">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Customer order book</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox"  id="switch4" name="customer_order_req"><label
                                                for="switch1">Toggle</label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Customer order pickup</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox"  id="switch5" name="customer_order_pickup" @if (@$user->usernotification->customer_order_pickup == '1') checked="checked" @endif><label
                                                for="switch5">Toggle</label>
                                        </div>
                                    </div>
                                </div><div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Lender order pickup</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox"  id="switch6" name="lender_order_pickup" @if (@$user->usernotification->lender_order_pickup == '1') checked="checked" @endif><label
                                                for="switch6">Toggle</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Customer order return</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox"  id="switch7" name="customer_order_return" @if (@$user->usernotification->customer_order_return == '1') checked="checked" @endif><label
                                                for="switch7">Toggle</label>
                                        </div>
                                    </div>
                                </div><div class="my-pro-notify-box">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <h4 class="m-0">Lender order return</h4>
                                        </div>
                                    </div>
                                    <div class="my-pro-detail-right">
                                        <div class="toggle-btn">
                                            <input type="checkbox" id="switch8" name="lender_order_return" @if (@$user->usernotification->lender_order_return == '1') checked="checked" @endif><label
                                                for="switch8">Toggle</label>
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
        $(document).ready(function() {

            $('input[type="checkbox"]').change(function() {
                var checkbox = $(this);
                var name = checkbox.attr('name');
                var isChecked = checkbox.is(':checked');

                $.ajax({
                    url: "{{ route('notification_preference') }}",
                    type: 'POST',
                    data: {
                        name: name,
                        value: isChecked ? 1 : 0
                    },
                    success: function(response) {
                        console.log(response.msg);
                    },
                    error: function(xhr) {
                        console.log(response.msg);
                    }
                });
            });
        });




    </script>
@endpush
