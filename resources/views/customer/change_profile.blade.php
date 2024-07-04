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
                                        {{-- <img src="{{asset('front/images/pro3.png')}}" alt="img"> --}}
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
                                                    <div class="formfield">
                                                        <input type="email" name="email" id="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Email" value="{{ $user->email }}">
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
                            <div class="col-md-12">
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
                                                            <label for="">Last Name</label>
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
                                                            <label for="">DOB</label>
                                                            <div class="formfield">
                                                                <input type="text" name="date_of_birth"
                                                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                    placeholder="Date of Birth" value="02/01/2002">
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
                                                                    placeholder="Account Number" value="537947293472">
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
                                                                    placeholder="Routing Number" value="000123456789">
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
    </script>
@endpush
