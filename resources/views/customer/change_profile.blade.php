@extends('layouts.front')
@section('title', 'My Profile')
@section('links')
    @php
        $user = auth()->user();
    @endphp
@endsection

@section('content')

    <section class="my-profile-sec cust-form-bg fill-hight">
        <div class="container">
            <div class="my-profile-wrapper">
                <h2>Account Settings</h2>
                <div class="my-profile-info-box">
                    <form action="{{ route('saveUserprofile') }}" method="POST" id="save_user" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="my-profilr-dp-edit">
                                    <div class="my-pro-dp-view">
                                        @if ($user->profile_file)
                                            <img id="preview-img" src="{{ asset('storage/' . $user->profile_file) }}"
                                                alt="Profile Picture">
                                        @else
                                            <img id="preview-img" src="{{ asset('front/images/pro3.png') }}"
                                                alt="Default Image">
                                        @endif
                                    </div>
                                    <label for="upload-my-pro" class="upload-my-pro">
                                        <i class="fa-solid fa-pen"></i>
                                        <input type="file" class="d-none" id="upload-my-pro" name="profile_pic">
                                    </label>
                                </div>
                            </div>
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
                                                    <div class="formfield form-control">
                                                        {{-- <input type="email" name="email" id="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Email" value="{{ $user->email }}"> --}}
                                                            <h4>{{ $user->email }}</h4>
                                                    </div>
                                                </div>
                                                {{-- @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror --}}
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
                                                        <textarea name="complete_address" id="address" placeholder="Address"
                                                            class="form-control @error('complete_address') is-invalid @enderror">{{ $user->userDetail->address1 }}</textarea>
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
                            <div class="col-md-12 user_bank_details p-0">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Bank</p>
                                            <div class="my-pro-edit-form">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">First Name</label>
                                                            <div class="formfield">
                                                                <input type="text" name="account_holder_first_name"
                                                                    class="form-control @error('account_holder_first_name') is-invalid @enderror"
                                                                    placeholder="Account Holder First Name"
                                                                    value="{{ $user->vendorBankDetails->account_holder_first_name ?? '' }}">
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
                                                            <label for="">Last Name</label>
                                                            <div class="formfield">
                                                                <input type="text" name="account_holder_last_name"
                                                                    class="form-control @error('account_holder_last_name') is-invalid @enderror"
                                                                    placeholder="Account Holder Last Name"
                                                                    value="{{ $user->vendorBankDetails->account_holder_last_name ?? '' }}">
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
                                                            <label for="">DOB</label>
                                                            <div class="formfield">
                                                                <input type="date" name="date_of_birth"
                                                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                    placeholder="Date of Birth"
                                                                    value="{{ $user->vendorBankDetails->account_holder_dob ?? '' }}">
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
                                                            <label for="">Account Number</label>
                                                            <div class="formfield">
                                                                <input type="text" name="account_number"
                                                                    class="form-control @error('account_number') is-invalid @enderror"
                                                                    placeholder="Account Number"
                                                                    value="{{ isset($user->vendorBankDetails->account_number) ? jsdecode_userdata($user->vendorBankDetails->account_number) : '' }}">
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
                                                            <label for="">Routing Number</label>
                                                            <div class="formfield">
                                                                <input type="text" name="routing_number"
                                                                    class="form-control @error('routing_number') is-invalid @enderror"
                                                                    placeholder="Routing Number"
                                                                    value="{{ isset($user->vendorBankDetails->routing_number) ? jsdecode_userdata($user->vendorBankDetails->routing_number) : '' }}">
                                                            </div>
                                                            @error('routing_number')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="my-pro-detail-left">
                                                            <div class="my-pro-detail-para">
                                                                <p>About me</p>
                                                                <div class="my-pro-edit-form">
                                                                    <div class="form-group">
                                                                        <div class="formfield">
                                                                            <textarea name="about" id="about" placeholder="About me"
                                                                                class="form-control @error('about') is-invalid @enderror">{{ $user->userDetail->about }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                    <button type="submit" class="button primary-btn">Update</button>
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
        // jQuery(document).ready(function() {
        //     $("#save_user").find('button').attr('disabled', false);
        //     const rules = {
        //         name: {
        //             required: true,
        //         },
        //         email: {
        //             required: true,
        //             email: true,
        //             regex: emailRegex,
        //         },
        //         complete_address: {
        //             required: true,
        //         },
        //         account_holder_first_name: {
        //             required: true,
        //         },
        //         account_holder_last_name: {
        //             required: true,
        //         },
        //         date_of_birth: {
        //             required: true,
        //         },
        //         account_number: {
        //             required: true,
        //         },
        //         routing_number: {
        //             required: true,
        //         }
        //     }
        //     const messages = {
        //         name: {
        //             required: `{{ __('customvalidation.user.name.required') }}`,
        //         },
        //         email: {
        //             required: `{{ __('customvalidation.user.email.required') }}`,
        //             email: `{{ __('customvalidation.user.email.email') }}`,
        //             regex: `{{ __('customvalidation.user.email.regex', ['regex' => '${emailRegex}']) }}`,
        //         },
        //         complete_address: {
        //             required: 'This field is required.',
        //         },
        //         account_holder_first_name: {
        //             required: 'This field is required.',
        //         },
        //         account_holder_last_name: {
        //             required: 'This field is required.',
        //         },
        //         date_of_birth: {
        //             required: 'This field is required.',
        //         },
        //         account_number: {
        //             required: 'This field is required.',
        //         },
        //         routing_number: {
        //             required: 'This field is required.',
        //         }
        //     };

        //     handleValidation('save_user', rules, messages);
        // });
        jQuery(document).ready(function() {
            $("#save_user").find('button').attr('disabled', false);

            const nameRegex = /^[a-zA-Z\s]+$/;
            const lastNameRegex = /^[a-zA-Z]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const addressRegex = /^[a-zA-Z0-9\s,.'-]{3,}$/;
            const dateOfBirthRegex = /^\d{4}-\d{2}-\d{2}$/;
            const accountNumberRegex = /^\d+$/;
            const routingNumberRegex = /^\d+$/;

            const rules = {
                name: {
                    required: true,
                    regex: nameRegex,
                },
                email: {
                    required: true,
                    email: true,
                    regex: emailRegex,
                },
                complete_address: {
                    required: true,
                    regex: addressRegex,
                },
                account_holder_first_name: {
                    required: true,
                    regex: nameRegex,
                },
                account_holder_last_name: {
                    required: true,
                    regex: lastNameRegex,
                },
                date_of_birth: {
                    required: true,
                    regex: dateOfBirthRegex,
                },
                account_number: {
                    required: true,
                    regex: accountNumberRegex,
                },
                routing_number: {
                    required: true,
                    regex: routingNumberRegex,
                }
            };

            const messages = {
                name: {
                    required: `{{ __('customvalidation.user.name.required') }}`,
                    regex: 'Name must contain only letters and spaces.',
                },
                email: {
                    required: `{{ __('customvalidation.user.email.required') }}`,
                    email: `{{ __('customvalidation.user.email.email') }}`,
                    regex: `{{ __('customvalidation.user.email.regex', ['regex' => '${emailRegex}']) }}`,
                },
                complete_address: {
                    required: 'This field is required.',
                    regex: 'Address must contain only letters, numbers, spaces, commas, periods, and hyphens.',
                },
                account_holder_first_name: {
                    required: 'This field is required.',
                    regex: 'First name must contain only letters and spaces.',
                },
                account_holder_last_name: {
                    required: 'This field is required.',
                    regex: 'Last name must not contain space and digits.',
                },
                date_of_birth: {
                    required: 'This field is required.',
                    regex: 'Date of birth must be in the format YYYY-MM-DD.',
                },
                account_number: {
                    required: 'This field is required.',
                    regex: 'Account number must contain only digits.',
                },
                routing_number: {
                    required: 'This field is required.',
                    regex: 'Routing number must contain only digits.',
                }
            };

            handleValidation('save_user', rules, messages, function(form) {
                $('body').addClass('loading');
                form.submit();
            });
        });

        $(document).ready(function() {

            $('#upload-my-pro').change(function() {
                var file = this.files[0];
                var fileType = file.type;

                // Check if the file type is not jpeg, png, or jpg
                if (fileType !== 'image/jpeg' && fileType !== 'image/png' && fileType !== 'image/jpg') {
                    alert('Only JPEG, PNG, and JPG files are allowed.');
                    $('#upload-my-pro').val(''); // Clear input
                    return;
                }

                var fileSize = file.size / 1024 / 1024; // in MB

                // Validate file size
                if (fileSize > 2) {
                    alert('File size exceeds the limit of 2 MB.');
                    $('#upload-my-pro').val(''); // Clear input
                    return;
                }
            });

            // SHow the preview of image

            $('#upload-my-pro').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#preview-img').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            });
        });

        const bank = new URLSearchParams(window.location.search);
        const bank_details = bank.get('bank');
        if(bank_details){
            $('.user_bank_details').addClass('highlight');
        }


    </script>
@endpush
