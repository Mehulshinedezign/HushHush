<div class="modal addproduct-Modal fade" id="accountSetting" tabindex="-1" data-bs-backdrop="static" aria-labelledby="accountSettingLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Your Address</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('Userprofile', ['type' => 'product']) }}" id="saveProfile" method="POST">
                    @csrf
                    <div class="ac-detail">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-pro-detail-left">
                                    <div class="my-pro-detail-para">
                                        <p>Address</p>
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
                                                <input type="text" placeholder="Address1" id="addressline12"
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
                                                <input type="text" placeholder="Address2" id="addressline21"
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
                                                <input type="text" placeholder="Country" id="selectCountry"
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
                                                <input type="text" name="state" placeholder="State"
                                                    value="{{ $user->userDetail->state ?? '' }}" id="selectState"
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
                                                <input type="text" name="city" id="selectCity"
                                                    value="{{ $user->userDetail->city ?? '' }}" placeholder="City"
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
                                    <p>Zipcode/Postal Code</p>
                                    <div class="my-pro-edit-form">
                                        <div class="form-group">
                                            <div class="formfield">
                                                <input type="text" name="zipcode" id="zip-code"
                                                    value="{{ $user->userDetail->zipcode ?? '' }}"
                                                    placeholder="Zipcode"
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
                            <div class="col-md-12">
                                <div class="right-btn-box">
                                    <button class="button primary-btn " id="addProduct">Add <div
                                            class="spinner-border d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function initAutocomplete() {
            var autocomplete = new google.maps.places.Autocomplete($('#address'));

            $('#selectCountry, #selectState, #selectCity, #zip-code').prop('readonly',
                true);
            
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#addressline12, #addressline21, #selectCountry, #selectState, #selectCity, #zip-code').val('');

                var zipcode = null; // Initialize zipcode to null

                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (addressType === 'street_number') {
                        $('#addressline12').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'route') {
                        $('#addressline21').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'country') {
                        $('#selectCountry').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'administrative_area_level_1') {
                        $('#selectState').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'locality') {
                        $('#selectCity').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'postal_code') {
                        console.log($('#zip-code') , place.address_components[i].long_name)
                        $('#zip-code').val(place.address_components[i].long_name); // Assign zipcode if available
                    }
                }
                // $('#zip-code').val(zipcode); // Set zipcode field

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

</script>
    <script>
        jQuery(document).ready(function() {
            $("#saveProfile").find('button').attr('disabled', false);

            const nameRegex = /^[a-zA-Z\s]+$/;
            const lastNameRegex = /^[a-zA-Z]+$/;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


            // $.validator.addMethod("userCompleteAddress", function(value, element) {
            //     return $('#addressline1').val() !== '' && $('#addressline2').val() !== '' && $('#country')
            //         .val() !== '' && $('#state').val() !== '' && $('#city').val() !== '';
            // }, "Please enter the complete address");

            $.validator.addMethod("userCompleteAddress", function(value, element) {
                return $('#selectCountry').val() !== '' && $('#selectState').val() !== '' ;
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
                },

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
                },

            };
            $('#saveProfile').submit(function(e) {
                e.preventDefault();
                handleValidation('saveProfile', rules, messages);
                if ($('#saveProfile').valid()) {
                $('.spinner-border').removeClass('d-none');
                    productformData = new FormData($('form#saveProfile').get(0));
                    var url = jQuery('#saveProfile').attr('action');
                    response = ajaxCall(url, 'post', productformData)
                    response.then(response => {
                        if (response.type == 'product') {
                            $('.spinner-border').addClass('d-none');
                            $('#accountSetting').modal('hide');
                            $('#addproduct-Modal').modal('show');

                        } else {
                            $('.spinner-border').addClass('d-none');
                            $('#accountSetting').modal('hide');
                            // const offcanvasElement = document.getElementById('inquiry-sidebar');
                            // const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                            // offcanvas.show();
                            location.reload();
                        }

                    })
                }
            })



            // Trigger validation when country, state, or city fields change
            // $('#addressline12, #addressline21, #selectCountry, #selectState, ').on('change', function() {
            //     $('#address').valid();
            // });
        });


        $('.address_data').hide();

        $('#address').on('focus', function() {
            $(".address_data").slideDown("slow");
            initAutocomplete();
        });

        $('#address').on('input', function() {
            if ($(this).val() === '') {
                $(".address_data").slideUp("slow");
                $('#addressline12, #addressline21, #selectCountry, #selectState, ').val('');
            }
        });

        
    </script>
@endpush
