<div class="address-list">
    <button id="addNewAddressBtn" class="adress-btm d-none">
        <i class="fa-solid fa-plus"></i> Add New Address
    </button>

    @foreach ($addresses as $index => $address)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="address-card col-md-12">
                <div class="complete-address" data-index="{{ $index }}">
                    {{ $address->complete_address }}
                </div>
                <div class="address-details d-none" id="address-details-{{ $index }}"
                    data-address='@json($address)'>
                    <p>{{ $address->address1 }}</p>
                    <p>{{ $address->address2 }}</p>
                    <p>{{ $address->state }}</p>
                    <p>{{ $address->city }}</p>
                    <p>{{ $address->country }}</p>
                    <p>{{ $address->zipcode }}</p>
                    <div class="edit-delete-address-btn">
                        @if($flag == "profile")
                        <a href="javascript:void(0)" class="edit-address" data-index="{{ $index }}">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.3862 7.28073L16.9766 3.6903C17.4186 3.24831 18.0181 3 18.6432 3C19.9448 3 21 4.05519 21 5.35684C21 5.98192 20.7517 6.58139 20.3097 7.02338L16.7193 10.6138C15.1584 12.1747 13.2026 13.282 11.0611 13.8174L10.3671 13.9909C10.1509 14.045 9.95503 13.8491 10.0091 13.6329L10.1826 12.9389C10.718 10.7974 11.8253 8.84163 13.3862 7.28073Z"
                                    fill="#363853" fill-opacity="0.15" />
                                <path
                                    d="M20.4445 6.88859C18.7779 7.4441 16.5559 5.22205 17.1114 3.55551M16.9766 3.6903L13.3862 7.28073C11.8253 8.84163 10.718 10.7974 10.1826 12.9389L10.0091 13.6329C9.95503 13.8491 10.1509 14.045 10.3671 13.9909L11.0611 13.8174C13.2026 13.282 15.1584 12.1747 16.7193 10.6138L20.3097 7.02338C20.7517 6.58139 21 5.98192 21 5.35684C21 4.05519 19.9448 3 18.6432 3C18.0181 3 17.4186 3.24831 16.9766 3.6903Z"
                                    stroke="#363853" stroke-width="1.5" />
                                <path
                                    d="M12 3C10.9767 3 9.95334 3.11763 8.95043 3.35288C6.17301 4.00437 4.00437 6.17301 3.35288 8.95043C2.88237 10.9563 2.88237 13.0437 3.35288 15.0496C4.00437 17.827 6.17301 19.9956 8.95044 20.6471C10.9563 21.1176 13.0437 21.1176 15.0496 20.6471C17.827 19.9956 19.9956 17.827 20.6471 15.0496C20.8824 14.0466 21 13.0233 21 12"
                                    stroke="#363853" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </a>
                        <a href="javascript:void(0)" class="delete-address" data-id="{{ $address->id }}">
                            <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" id="delete"
                                xmlns="http://www.w3.org/2000/svg" class="icon multi-color">
                                <rect id="secondary-fill" x="6" y="7" width="9" height="14"
                                    style="fill: rgb(44, 169, 188); stroke-width: 2;"></rect>
                                <path id="primary-stroke"
                                    d="M4,7H20M16,7V4a1,1,0,0,0-1-1H9A1,1,0,0,0,8,4V7M18,20V7H6V20a1,1,0,0,0,1,1H17A1,1,0,0,0,18,20Z"
                                    style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;" />
                            </svg>
                        </a>
                        @endif
                        @if($flag == "query")
                            <button type="button" class="btn btn-primary btn-sm"
                                onclick="selectAddress('{{ $address->id }}', '{{ $address->complete_address }}')"
                                data-bs-dismiss="modal">Select</button>
                        @endif
                    </div>
                </div>
            </div>
        </li>
    @endforeach
</div>
<div id="addEditAddressForm">
    <div class="add-adress-bx">
        <h4 id="formTitle">Add Address</h4>

    </div>
    <form id="addressForm">
        <div class="row g-3">
            <div class="col-md-12">
                <label for="autocomplete" class="form-label">Enter
                    your address*</label>
                <input id="autocomplete" name ="complete_address" placeholder="Start typing your address"
                    class="form-control" type="text">

            </div>
            <input type="hidden" id="address_id" name="address_id" value="">
            <div class="col-lg-6">
                <label for="street_number">Address Line 1*</label>
                <input id="street_number" name="address1" class="form-control" placeholder="Street number"
                    type="text" />
            </div>
            <div class="col-lg-6">
                <label for="route">Address Line 2</label>
                <input id="route" name="address2" class="form-control" placeholder="Street name" type="text" />
            </div>
            <div class="col-lg-6">
                <label for="locality">City*</label>
                <input id="locality" name="city" class="form-control" placeholder="City" type="text"readonly />
            </div>
            <div class="col-lg-6">
                <label for="administrative_area_level_1">State*</label>
                <input id="administrative_area_level_1" name="state" class="form-control" placeholder="State"
                    type="text" readonly />
            </div>
            <div class="col-lg-6">
                <label for="country">Country*</label>
                <input id="country1" name="country" class="form-control" placeholder="Country" readonly />
            </div>
            <div class="col-lg-6">
                <label for="postal_code">Postal Code*</label>
                <input id="postal_code" name="zipcode" class="form-control" placeholder="Postal Code" type="text"
                    readonly />
            </div>
            <div class="col-lg-">
                <div class="checkbox-field">
                    <input type="checkbox" name="is_default" id="is_default" class="form-check-input" />
                    <label for="is_default">Make Default</label>
                </div>
            </div>
        </div>
        <div class="right-btn-bx">
            <button type="button" id="submitAddressBtn" class="btn btn-success">Submit
                Address</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#addNewAddressBtn').click(function() {
            $('#formTitle').text('Add Address');
            $('#submitAddressBtn').text('Add Address');
            $('#addEditAddressForm').find(
                    'input[type="text"], input[type="file"], input[type="email"],input[type="hidden"],select, textarea'
                )
                .val('');
        });

        $('#autocomplete').on('keyup', function() {
            console.log('sdfsdf');
            var autocomplete = new google.maps.places.Autocomplete(this);

            autocomplete.addListener('place_changed', () => {
                var place = autocomplete.getPlace();
                var addressComponents = place.address_components.reduce((acc, component) => {
                    acc[component.types[0]] = component.long_name;
                    return acc;
                }, {});

                // Use jQuery to set the values in the respective fields
                $('#street_number').val(addressComponents.street_number || '');
                $('#route').val(addressComponents.route || '');
                $('#locality').val(addressComponents.locality || '');
                $('#administrative_area_level_1').val(addressComponents
                    .administrative_area_level_1 || '');
                $('#country1').val(addressComponents.country || '');
                $('#postal_code').val(addressComponents.postal_code || '');
            });
        });

        $('#submitAddressBtn').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var submitBtn = $('#submitAddressBtn'); // Select the submit button
            submitBtn.attr('disabled', true); // Disable the button to prevent multiple submissions

            // Collect form data
            var formData = {
                complete_address: $('#autocomplete').val(),
                address_id: $('#address_id').val(),
                address1: $('#street_number').val(),
                address2: $('#route').val(),
                city: $('#locality').val(),
                state: $('#administrative_area_level_1').val(),
                country: $('#country1').val(),
                zipcode: $('#postal_code').val(),
                is_default: $('#is_default').is(':checked') ? 1 :
                0, // Check if is_default is checked
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                flag:'{{ $flag }}'
            };

            // Perform the AJAX request
            $.ajax({
                type: 'POST',
                url: '/address/store',
                data: formData,
                success: function(response) {
                    submitBtn.removeAttr('disabled'); // Re-enable the submit button

                    if (response.data) {
                        $('#address-list-class').html(response.data
                            .list); // Update the address list
                        Swal.fire('Success', response.message, 'success');
                    }

                    // Display success message using Swal
                },
                error: function(xhr) {
                    submitBtn.removeAttr(
                        'disabled'); // Re-enable the submit button in case of error

                    // Display error message using Swal
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(".complete-address").on('click', function() {
            console.log('collapse');

            // Toggle visibility of the address details based on the index
            $('div.address-details').addClass('d-none');
            $(this).parent('div').find('div.address-details').toggleClass('d-none');

        });


        $('.edit-address').on('click', function(e) {
            e.preventDefault();
            $('#addNewAddressBtn').removeClass('d-none');
            console.log('edit');

            // Retrieve the address ID from the clicked element
            var index = this.dataset.index;
            var addressData = JSON.parse(document.getElementById(
                `address-details-${index}`).dataset.address);
            document.getElementById('submitAddressBtn').textContent =
                'Update Address';
            populateAddressForm(addressData);

        });


        var populateAddressForm = (data) => {
            console.log(data);

            // Update form title
            $('#formTitle').text('Edit Address');

            // Show the address form
            $('#addEditAddressForm').removeClass('d-none');

            // Populate the form fields with the address data
            $('#address_id').val(data.id);
            $('#autocomplete').val(data.complete_address);
            $('#street_number').val(data.address1);
            $('#route').val(data.address2);
            $('#locality').val(data.city);
            $('#administrative_area_level_1').val(data.state);
            $('#country1').val(data.country);
            $('#postal_code').val(data.zipcode);

            // Set the default checkbox
            $('#is_default').prop('checked', data.is_default === '1');
        };





        $('.delete-address').on('click', function(e) {
            e.preventDefault();
            console.log('delete');


            var addressId = $(this).data('id'); // Use jQuery to get the address ID

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this address?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteAddress(addressId); // Call delete function
                }
            });


            var deleteAddress = (id) => {
                var url = APP_URL + `/address/${id}?flag=`+"{{ $flag }}"
                $.ajax({
                    url:url ,
                    method: 'DELETE',
                    success: (response) => {
                        $('#address-list-class').html(response.data
                            .list);
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                        }).then(() => {
                            fetchAddress
                                (); // Fetch addresses after successful deletion
                        });
                    },
                    error: (response) => {
                        Swal.fire({
                            title: 'Error',
                            text: response.responseJSON.message,
                            icon: 'error',
                        });
                    }
                });
            };

        });









    });
</script>
