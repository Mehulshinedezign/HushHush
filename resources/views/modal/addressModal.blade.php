<div class="modal fade" id="addressModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Manage Your Addresses</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <button id="addNewAddressBtn" class="btn btn-primary">Add New
                        Address</button>
                </div>
                <div class="address-list">
                    @foreach ($user->addresses as $index => $address)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="address-card col-md-12">
                            <div class="complete-address" data-index="{{ $index }}">
                                {{ $address->complete_address }}</div>
                            <div class="address-details d-none" id="address-details-{{ $index }}"
                                data-address='@json($address)'>
                                <p>{{ $address->complete_address }}</p>
                                <p>{{ $address->address1 }}</p>
                                <p>{{ $address->address2 }}</p>
                                <p>{{ $address->state }}</p>
                                <p>{{ $address->city }}</p>
                                <p>{{ $address->country }}</p>
                                <p>{{ $address->zipcode }}</p>
                                <a href="javascript:void(0)" class="edit-address"
                                    data-index="{{ $index }}">Edit</a>
                                <a href="javascript:void(0)" class="delete-address"
                                    data-id="{{ $address->id }}">Delete</a>
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="selectAddress('{{ $address->id }}', '{{ $address->complete_address }}')"
                                    data-bs-dismiss="modal">Select</button>
                            </div>
                        </div>
                        </li>
                    @endforeach
                </div>
                <div id="addEditAddressForm" class="d-none">
                    <h4 id="formTitle">Add Address</h4>
                    <form id="addressForm">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="autocomplete" class="form-label">Enter
                                    your address*</label>
                                <input id="autocomplete" placeholder="Start typing your address" class="form-control"
                                    type="text" />
                            </div>
                            <input type="hidden" id="address_id" name="address_id">
                            <div class="col-lg-6 mb-3">
                                <label for="street_number">Address Line 1*</label>
                                <input id="street_number" name="address1" class="form-control"
                                    placeholder="Street number" />
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="route">Address Line 2</label>
                                <input id="route" name="address2" class="form-control" placeholder="Street name" />
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="locality">City*</label>
                                <input id="locality" name="city" class="form-control" placeholder="City" readonly />
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="administrative_area_level_1">State*</label>
                                <input id="administrative_area_level_1" name="state" class="form-control"
                                    placeholder="State" readonly />
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="country">Country*</label>
                                <input id="country" name="country" class="form-control" placeholder="Country"
                                    readonly />
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="postal_code">Postal Code*</label>
                                <input id="postal_code" name="zipcode" class="form-control" placeholder="Postal Code"
                                    readonly />
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="is_default">Make Default</label>
                                <input type="checkbox" name="is_default" id="is_default" class="form-check-input" />
                            </div>
                        </div>
                        <button type="button" id="submitAddressBtn" class="btn btn-success">Submit Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
