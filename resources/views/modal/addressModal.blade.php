<div class="modal fade" id="addressModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h3 class="modal-title">Manage Your Addresses

                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button id="addNewAddressBtn" class="adress-btm d-none"><i class="fa-solid fa-plus"></i> Add New
                    Address</button>
                <div class="address-list-main">
                <div class="address-list">
                    @foreach ($user->addresses as $index => $address)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="address-card col-md-12">
                            <div class="complete-address" data-index="{{ $index }}">
                                {{ $address->complete_address }}</div>
                            <div class="address-details d-none" id="address-details-{{ $index }}"
                                data-address='@json($address)'>
                                {{-- <p>{{ $address->complete_address }}</p> --}}
                                <p>{{ $address->address1 }}</p>
                                <p>{{ $address->address2 }}</p>
                                <p>{{ $address->state }}</p>
                                <p>{{ $address->city }}</p>
                                <p>{{ $address->country }}</p>
                                <p>{{ $address->zipcode }}</p>
                                <div class="edit-delete-address-btn">
                                    <a href="javascript:void(0)" class="edit-address"
                                    data-index="{{ $index }}">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.3862 7.28073L16.9766 3.6903C17.4186 3.24831 18.0181 3 18.6432 3C19.9448 3 21 4.05519 21 5.35684C21 5.98192 20.7517 6.58139 20.3097 7.02338L16.7193 10.6138C15.1584 12.1747 13.2026 13.282 11.0611 13.8174L10.3671 13.9909C10.1509 14.045 9.95503 13.8491 10.0091 13.6329L10.1826 12.9389C10.718 10.7974 11.8253 8.84163 13.3862 7.28073Z" fill="#363853" fill-opacity="0.15"/>
                                        <path d="M20.4445 6.88859C18.7779 7.4441 16.5559 5.22205 17.1114 3.55551M16.9766 3.6903L13.3862 7.28073C11.8253 8.84163 10.718 10.7974 10.1826 12.9389L10.0091 13.6329C9.95503 13.8491 10.1509 14.045 10.3671 13.9909L11.0611 13.8174C13.2026 13.282 15.1584 12.1747 16.7193 10.6138L20.3097 7.02338C20.7517 6.58139 21 5.98192 21 5.35684C21 4.05519 19.9448 3 18.6432 3C18.0181 3 17.4186 3.24831 16.9766 3.6903Z" stroke="#363853" stroke-width="1.5"/>
                                        <path d="M12 3C10.9767 3 9.95334 3.11763 8.95043 3.35288C6.17301 4.00437 4.00437 6.17301 3.35288 8.95043C2.88237 10.9563 2.88237 13.0437 3.35288 15.0496C4.00437 17.827 6.17301 19.9956 8.95044 20.6471C10.9563 21.1176 13.0437 21.1176 15.0496 20.6471C17.827 19.9956 19.9956 17.827 20.6471 15.0496C20.8824 14.0466 21 13.0233 21 12" stroke="#363853" stroke-width="1.5" stroke-linecap="round"/>
                                        </svg>
                                </a>
                                <a href="javascript:void(0)" class="delete-address"
                                    data-id="{{ $address->id }}">
                                    <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24" id="delete" xmlns="http://www.w3.org/2000/svg" class="icon multi-color"><rect id="secondary-fill" x="6" y="7" width="9" height="14" style="fill: rgb(44, 169, 188); stroke-width: 2;"></rect><path id="primary-stroke" d="M4,7H20M16,7V4a1,1,0,0,0-1-1H9A1,1,0,0,0,8,4V7M18,20V7H6V20a1,1,0,0,0,1,1H17A1,1,0,0,0,18,20Z" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></svg>
                                </a>
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="selectAddress('{{ $address->id }}', '{{ $address->complete_address }}')"
                                    data-bs-dismiss="modal">Select</button>
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
                                <input id="autocomplete" name ="complete_address" placeholder="Start typing your address" class="form-control"
                                    type="text" >
                                    
                            </div>
                            <input type="hidden" id="address_id" name="address_id">
                            <div class="col-lg-6">
                                <label for="street_number">Address Line 1*</label>
                                <input id="street_number" name="address1" class="form-control"
                                    placeholder="Street number" />
                            </div>
                            <div class="col-lg-6">
                                <label for="route">Address Line 2</label>
                                <input id="route" name="address2" class="form-control" placeholder="Street name" />
                            </div>
                            <div class="col-lg-6">
                                <label for="locality">City*</label>
                                <input id="locality" name="city" class="form-control" placeholder="City" readonly />
                            </div>
                            <div class="col-lg-6">
                                <label for="administrative_area_level_1">State*</label>
                                <input id="administrative_area_level_1" name="state" class="form-control"
                                    placeholder="State" readonly />
                            </div>
                            <div class="col-lg-6">
                                <label for="country">Country*</label>
                                <input id="country" name="country" class="form-control" placeholder="Country"
                                    readonly />
                            </div>
                            <div class="col-lg-6">
                                <label for="postal_code">Postal Code*</label>
                                <input id="postal_code" name="zipcode" class="form-control" placeholder="Postal Code"
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
                           <button type="button" id="submitAddressBtn" class="btn btn-success">Submit Address</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
      document.addEventListener('DOMContentLoaded', () => {
            const manageAddressLink = document.getElementById('manage_address_link');
            const selectedValueInput = document.getElementById('selected_value');

            const initializeAddressManagement = () => {
                const addressList = document.querySelectorAll('.complete-address');

                addressList.forEach((address, index) => {
                    address.addEventListener('click', () => {
                        document.querySelectorAll('.address-details').forEach((details, i) => {
                            details.classList.toggle('d-none', i !== index);
                        });
                    });
                });

                document.getElementById('addNewAddressBtn').addEventListener('click', showAddAddressForm);
                setupEditAndDeleteButtons();
                setupAddressForm();
            };

            $(document).ready(function() {
                $('#addNewAddressBtn').click(function() {
                    $('#addEditAddressForm').find(
                            'input[type="text"], input[type="file"], input[type="email"], select, textarea'
                        )
                        .val('');
                });
            });

            const showAddAddressForm = () => {
                document.getElementById('formTitle').textContent = 'Add Address';
                document.getElementById('addEditAddressForm').classList.remove('d-none');
                document.getElementById('addressForm').reset();
                document.getElementById('address_id').value = '';
                document.getElementById('submitAddressBtn').textContent = 'Save Address';

            };

            const setupEditAndDeleteButtons = () => {
                document.querySelectorAll('.edit-address').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = this.dataset.index;
                        const addressData = JSON.parse(document.getElementById(
                            `address-details-${index}`).dataset.address);
                            document.getElementById('submitAddressBtn').textContent='Update Address'
                        populateAddressForm(addressData);
                    });
                });

                document.querySelectorAll('.delete-address').forEach(button => {
                    button.addEventListener('click', function() {
                        const addressId = this.dataset.id;

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
                                deleteAddress(addressId);
                            }
                        });
                    });
                });
            };

            const populateAddressForm = (data) => {
                document.getElementById('formTitle').textContent = 'Edit Address';
                document.getElementById('addEditAddressForm').classList.remove('d-none');
                document.getElementById('address_id').value = data.id;
                document.getElementById('autocomplete').value = data.complete_address;
                document.getElementById('street_number').value = data.address1;
                document.getElementById('route').value = data.address2;
                document.getElementById('locality').value = data.city;
                document.getElementById('administrative_area_level_1').value = data.state;
                document.getElementById('country').value = data.country;
                document.getElementById('postal_code').value = data.zipcode;
                const isDefaultCheckbox = document.getElementById('is_default');
                if (data.is_default === '1') {
                    isDefaultCheckbox.checked = true;
                } else {
                    isDefaultCheckbox.checked = false;
                }

            };

            const deleteAddress = (id) => {
                $.ajax({
                    url: `/address/${id}`,
                    method: 'DELETE',
                    success: (response) => {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                        }).then(() => {
                            setTimeout(() => location.reload(), 1500);
                        });
                    },
                    error: (response) => {
                        Swal.fire({
                            title: 'Error',
                            text: response.responseJSON
                                .message,
                            icon: 'error',
                        });
                    }
                });
            };

            const setupAddressForm = () => {
                document.getElementById('submitAddressBtn').addEventListener('click', function() {
                    const submitBtn = $(this); // Store the button in a variable
                    submitBtn.attr('disabled', true);
                    const formData = {
                        complete_address: document.getElementById('autocomplete').value,
                        address_id: document.getElementById('address_id').value,
                        address1: document.getElementById('street_number').value,
                        address2: document.getElementById('route').value,
                        city: document.getElementById('locality').value,
                        state: document.getElementById('administrative_area_level_1').value,
                        country: document.getElementById('country').value,
                        zipcode: document.getElementById('postal_code').value,
                        is_default: document.getElementById('is_default').checked ? 1 : 0,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $.ajax({
                        type: 'POST',
                        url: '/address/store',
                        data: formData,
                        success: (response) => {
                            submitBtn.removeAttr('disabled');

                            Swal.fire('Success', response.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        },
                        error: (xhr) => {
                            submitBtn.removeAttr('disabled');

                            Swal.fire('Error', xhr.responseJSON.message, 'error');
                        }
                    });
                });
            };

            const initAutocomplete = () => {
                var input = document.getElementById('autocomplete');
                const autocomplete = new google.maps.places.Autocomplete(input);

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    const addressComponents = place.address_components.reduce((acc, component) => {
                        acc[component.types[0]] = component.long_name;
                        return acc;
                    }, {});

                    $('#street_number').val(addressComponents.street_number || '');
                    $('#route').val(addressComponents.route || '');
                    $('#locality').val(addressComponents.locality || '');
                    $('#administrative_area_level_1').val(addressComponents
                        .administrative_area_level_1 || '');
                    $('#country').val(addressComponents.country || '');
                    $('#postal_code').val(addressComponents.postal_code || '');
                    $('#postal_code').val(addressComponents.postal_code || '');
                });
            };

            manageAddressLink.addEventListener('click', () => {
                initializeAddressManagement();
                initAutocomplete();
                const offcanvasElement = document.getElementById('inquiry-sidebar');
                const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                offcanvas.show();
            });
        });
</script>
