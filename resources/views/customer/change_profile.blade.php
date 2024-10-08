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
                                            <img id="preview-img" src="{{ asset('img/avatar-small.png') }}"
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
                                            <p>User Name</p>
                                            <div class="my-pro-edit-form">
                                                <div class="form-group">
                                                    <div class="formfield">
                                                        <input type="text" name="name" id="name"
                                                            class="form-control form-class @error('name') is-invalid @enderror"
                                                            placeholder="Name" value="{{ $user->name }}">
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
                                                        <h4>{{ $user->email }}</h4>
                                                    </div>
                                                </div>
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
                                                    <p>Default Address</p>
                                                    <div class="profile-edit-btn">
                                                    <a href="javascript:void(0);" id="manage_address_link" class=" ">Manage Address</a>
                                                    </div>
                                                    <div class="my-pro-edit-form">
                                                        <div class="form-group">
                                                            <div class="formfield">
                                                                <textarea name="complete_address" id="address" placeholder="Address"
                                                                    class="form-control @error('complete_address') is-invalid @enderror">{{ $user->userDetail->complete_address ?? '' }}</textarea>
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
                                        </div>
                                        <div class="col-md-6 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>Address line 1</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" placeholder="address1" id="addressline1"
                                                                name="addressline1"
                                                                class="form-control form-class @error('addressline1') is-invalid @enderror"
                                                                value="{{ $user->userDetail->address1 ?? '' }}">
                                                            @error('addressline1')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>Address line 2</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" placeholder="address2" id="addressline2"
                                                                name="addressline2"
                                                                class="form-control form-class @error('addressline2') is-invalid @enderror"
                                                                value="{{ $user->userDetail->address2 ?? '' }}">
                                                            @error('addressline2')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>Country</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" placeholder="Country" id="country"
                                                                name="country"
                                                                class="form-control form-class @error('country') is-invalid @enderror"
                                                                value="{{ $user->userDetail->country ?? '' }}">
                                                            @error('country')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>State</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" name="state" placeholder="state"
                                                                value="{{ $user->userDetail->state ?? '' }}"
                                                                id="state"
                                                                class="form-control form-class @error('state') is-invalid @enderror">
                                                            @error('state')
                                                                <span class="invalid-feedback" role="alert">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>City</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" name="city" id="city"
                                                                value="{{ $user->userDetail->city ?? '' }}"
                                                                placeholder="City"
                                                                class="form-control form-class @error('city') is-invalid @enderror">
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
                                        <div class="col-md-3 address_data">
                                            <div class="my-pro-detail-para">
                                                <p>Zip-Code/Postal Code</p>
                                                <div class="my-pro-edit-form">
                                                    <div class="form-group">
                                                        <div class="formfield">
                                                            <input type="text" name="zipcode" id="zipcode"
                                                                value="{{ $user->userDetail->zipcode ?? '' }}"
                                                                placeholder="zipcode"
                                                                class="form-control form-class @error('zipcode') is-invalid @enderror">
                                                            @error('zipcode')
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

                            <div class="col-md-12 user_bank_details p-0">
                                <div class="my-pro-detail">
                                    <div class="my-pro-detail-left">
                                        <div class="my-pro-detail-para">
                                            {{-- <p>Bank</p> --}}
                                            <div class="my-pro-edit-form">
                                                <div class="row g-3">

                                                    <div class="col-md-12">
                                                        <div class="my-pro-detail-left">
                                                            <div class="my-pro-detail-para">
                                                                <p>About me</p>
                                                                <div class="my-pro-edit-form">
                                                                    <div class="form-group">
                                                                        <div class="formfield">
                                                                            <textarea name="about" id="about" placeholder="About me"
                                                                                class="form-control @error('about') is-invalid @enderror">{{ $user->userDetail->about ?? '' }}</textarea>
                                                                            @error('about')
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
        @include('modal.addressModal')
    </section>


@endsection

@push('scripts')
    @includeFirst(['validation'])
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            $('.edit-address').on('click', function() {
               $('#addNewAddressBtn').removeClass('d-none');
            })
            
            // Enable the save button on load
            $("#save_user").find('button').attr('disabled', false);

            const nameRegex = /^[a-zA-Z\s]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Custom validation for complete address
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
                about: {
                    required: true,
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
                    userCompleteAddress: 'Please enter the complete address.',
                },
                about: {
                    required: 'This field is required.',
                }
            };

            // Form validation
            handleValidation('save_user', rules, messages, function(form) {
                $('body').addClass('loading');
                form.submit();
            });

            // Trigger validation when address fields change
            $('#addressline1, #addressline2, #country, #state').on('change', function() {
                $('#address').valid();
            });

            // Image upload validation and preview
            $('#upload-my-pro').change(function() {
                var file = this.files[0];
                var fileType = file.type;
                var fileSize = file.size / 1024 / 1024; // Convert to MB

                // Validate file type
                if (fileType !== 'image/jpeg' && fileType !== 'image/png' && fileType !== 'image/jpg') {
                    iziToast.error({
                        title: 'Error',
                        message: 'Only JPEG, PNG, and JPG files are allowed.',
                        position: 'topRight',
                    });
                    $('#upload-my-pro').val(''); // Clear input
                    return;
                }

                // Validate file size
                if (fileSize > 2) {
                    alert('File size exceeds the limit of 2 MB.');
                    $('#upload-my-pro').val(''); // Clear input
                    return;
                }

                // Image preview
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            });

            // Initialize Google Places Autocomplete
            function initAutocomplete() {
                var input = document.getElementById('address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                $('#addressline1, #addressline2, #country, #state, #city, #zipcode').prop('readonly', true);

                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();

                    // Clear address fields
                    $('#addressline1, #addressline2, #country, #state, #city, #zipcode').val('');

                    // Populate address fields based on the selected place
                    place.address_components.forEach(function(component) {
                        var addressType = component.types[0];
                        switch (addressType) {
                            case 'street_number':
                                $('#addressline1').val(component.long_name);
                                break;
                            case 'route':
                                $('#addressline2').val(component.long_name || '');
                                break;
                            case 'country':
                                $('#country').val(component.long_name);
                                break;
                            case 'administrative_area_level_1':
                                $('#state').val(component.long_name);
                                break;
                            case 'locality':
                                $('#city').val(component.long_name || '');
                                break;
                            case 'postal_code':
                                $('#zipcode').val(component.long_name || ''); // Zipcode is optional
                                break;
                        }
                    });

                    // Enable or disable readonly state based on field values
                    function setReadonly(selector) {
                        if ($(selector).val()) {
                            $(selector).prop('readonly', true);
                        } else {
                            $(selector).prop('readonly', false);
                        }
                    }

                    setReadonly('#addressline1');
                    setReadonly('#addressline2');
                    $(".address_data").slideDown("slow");
                });
            }

            // Initialize autocomplete for the address field
            initAutocomplete();

        });
    </script>
    <script>
        $(document).ready(function() {
    $('#manage_address_link').on('click', function() {
        // Open the modal when the link is clicked
        $('#addressModal').modal('show');
    });
});
    </script>
@endpush

