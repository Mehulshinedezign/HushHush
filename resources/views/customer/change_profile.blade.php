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
                                                <div class="form-group ">
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
                                    
                                    <div class="row w-100">
                                        <div class="col-md-12">
                                            <div class="my-pro-detail-left">
                                                <div class="my-pro-detail-para">
                                                    <p>Address</p>
                                                    <div class="my-pro-edit-form">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <textarea name="complete_address" id="address" placeholder="Address"
                                                                    class="form-control @error('complete_address') is-invalid @enderror">{{ $user->userDetail->complete_address ?? '' }}</textarea>
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
                                        <div class="col-md-4 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>Country</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" placeholder="Country" id="country" name="country"
                                                                class="form-control" value="{{ $user->userDetail->country ?? '' }}">
                                                        </div>
                                                    </div>
                                                    @error('country')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>State</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" name="state" placeholder="state" value="{{ $user->userDetail->state ?? ''}}"
                                                                id="state" class="form-control">
                                                        </div>
                                                    </div>
                                                    @error('state')
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>       
                                        </div>
                                        <div class="col-md-4 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>City</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" name="city" id="city" value="{{ $user->userDetail->city ?? '' }}"
                                                                placeholder="City" class="form-control">
                                                        </div>
                                                    </div>
                                                    @error('city')
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

                            <div class="col-md-12 user_bank_details p-0">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            <p>Bank</p>
                                            <div class="my-pro-edit-form">
                                                <div class="row g-3">
                                                    {{-- <div class="col-md-6">
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
                                                    </div> --}}
                                                    {{-- <div class="col-md-6">
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
                                                    </div> --}}
                                                    {{-- <div class="col-md-6">
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
                                                    </div> --}}
                                                    {{-- <div class="col-md-6">
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
                                                    </div> --}}
                                                    {{-- <div class="col-md-12">
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
                                                    </div> --}}

                                                    <div class="col-md-12">
                                                        <div class="my-pro-detail-left">
                                                            <div class="my-pro-detail-para">
                                                                <p>About me</p>
                                                                <div class="my-pro-edit-form">
                                                                    <div class="form-group">
                                                                        <div class="formfield">
                                                                            <textarea name="about" id="about" placeholder="About me"
                                                                                class="form-control @error('about') is-invalid @enderror">{{ $user->userDetail->about ?? '' }}</textarea>
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
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>


    <script>
        jQuery(document).ready(function() {
            $("#save_user").find('button').attr('disabled', false);

            const nameRegex = /^[a-zA-Z\s]+$/;
            const lastNameRegex = /^[a-zA-Z]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            // const addressRegex = /^[a-zA-Z0-9\s,.'-]{3,}$/;
            const dateOfBirthRegex = /^\d{4}-\d{2}-\d{2}$/;
            const accountNumberRegex = /^\d+$/;
            const routingNumberRegex = /^\d+$/;

            $.validator.addMethod("userCompleteAddress", function(value, element) {
                return $('#country').val() !== '' && $('#state').val() !== '' && $('#city').val() !== '';
            }, "Please enter the complete address");

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
                    userCompleteAddress: true,
                },
                // account_holder_first_name: {
                //     required: true,
                //     regex: nameRegex,
                // },
                // account_holder_last_name: {
                //     required: true,
                //     regex: lastNameRegex,
                // },
                // date_of_birth: {
                //     required: true,
                //     regex: dateOfBirthRegex,
                // },
                // account_number: {
                //     required: true,
                //     regex: accountNumberRegex,
                // },
                // routing_number: {
                //     required: true,
                //     regex: routingNumberRegex,
                // }
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
                    userCompleteAddress: 'Please enter the complete address',
                },
                // account_holder_first_name: {
                //     required: 'This field is required.',
                //     regex: 'First name must contain only letters and spaces.',
                // },
                // account_holder_last_name: {
                //     required: 'This field is required.',
                //     regex: 'Last name must not contain space and digits.',
                // },
                // date_of_birth: {
                //     required: 'This field is required.',
                //     regex: 'Date of birth must be in the format YYYY-MM-DD.',
                // },
                // account_number: {
                //     required: 'This field is required.',
                //     regex: 'Account number must contain only digits.',
                // },
                // routing_number: {
                //     required: 'This field is required.',
                //     regex: 'Routing number must contain only digits.',
                // }
            };

            handleValidation('save_user', rules, messages, function(form) {
                $('body').addClass('loading');
                form.submit();
            });

        // Trigger validation when country, state, or city fields change
            $('#country, #state, #city').on('change', function() {
                $('#address').valid();
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


            // google place api 
            $('.address_data').hide();

            $('#address').on('focus', function() {
                $(".address_data").slideDown("slow");
                initAutocomplete();
            });

            $('#address').on('input', function() {
                if ($(this).val() === '') {
                    $(".address_data").slideUp("slow");
                    $('#country, #state, #city').val('');
                }
            });

            function initAutocomplete() {
                var input = document.getElementById('address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                $('#country, #state, #city').prop('readonly', true);

                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    $('#country, #state, #city').val('');

                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];

                        if (addressType === 'country') {
                            $('#country').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'administrative_area_level_1') {
                            $('#state').val(place.address_components[i].long_name);
                        }
                        if (addressType === 'locality') {
                            $('#city').val(place.address_components[i].long_name);
                        }
                    }

                    $(".address_data").slideDown("slow");
                });
            }

        });


    </script>
@endpush
