{{-- sdddddgsdgsdgsdgdsgdsg  --}}
<div class="modal fade bankdetail-modal" id="mutlistepForm2" tabindex="-1" aria-labelledby="mutlistepFormLabel1"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id=errormessage>
                </div>
                <form action="{{ route('bankdetail') }}" method="post" id="bankdetails">
                    @csrf
                    <div class="Mutistep-form-wrapper">
                        <div class="popup-head">
                            <button type="button" class="close" id="close_bankdetails" data-bs-dismiss="modal"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="multistep-box">
                            <div class="form-steps step2">
                                <h6 class="">Bank Details</h6>
                                <div style="font-size: 14px; text-align: center;">Last step! We require your bank
                                    details to deposit your earnings. Your financial data is stored securely.</div>
                                <div class="form-row mt-4">

                                    <div class="form-group">
                                        {{-- <label>Account Holder First Name</label> --}}
                                        <input type="text" name="account_holder_first_name" class="form-control"
                                            value="{{ old('account_holder_first_name', @$bankDetail->account_holder_first_name) }}"
                                            placeholder="Account Holder First Name">
                                        @error('account_holder_first_name')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        {{-- <label>Account Holder Last Name</label> --}}
                                        <input type="text" name="account_holder_last_name" class="form-control"
                                            value="{{ old('account_holder_last_name', @$bankDetail->account_holder_last_name) }}"
                                            placeholder="Account Holder Last Name">
                                        @error('account_holder_last_name')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="order-status p-relative">
                                            {{-- <label for="text">Date of Birth</label> --}}
                                            <div class="formfield">
                                                <input type="text" name="account_holder_dob"
                                                    class="form-control required"
                                                    value="{{ old('account_holder_dob', @$bankDetail->account_holder_dob) }}"
                                                    placeholder="Date of Birth" autocomplete="off">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="11" viewBox="0 0 12 11" fill="none">
                                                        <path
                                                            d="M10.3338 0.166016H9.34992V1.1499C9.34992 1.34668 9.18594 1.47786 9.02196 1.47786C8.85798 1.47786 8.694 1.34668 8.694 1.1499V0.166016H3.44661V1.1499C3.44661 1.34668 3.28263 1.47786 3.11865 1.47786C2.95467 1.47786 2.79069 1.34668 2.79069 1.1499V0.166016H1.8068C1.31486 0.166016 0.954102 0.592366 0.954102 1.1499V2.33056H11.4489V1.1499C11.4489 0.592366 10.8585 0.166016 10.3338 0.166016ZM0.954102 3.01928V9.02098C0.954102 9.61132 1.31486 10.0049 1.8396 10.0049H10.3666C10.8913 10.0049 11.4817 9.57852 11.4817 9.02098V3.01928H0.954102ZM3.87296 8.52904H3.08585C2.95467 8.52904 2.82348 8.43065 2.82348 8.26667V7.44677C2.82348 7.31558 2.92187 7.1844 3.08585 7.1844H3.90576C4.03694 7.1844 4.16813 7.28279 4.16813 7.44677V8.26667C4.13533 8.43065 4.03694 8.52904 3.87296 8.52904ZM3.87296 5.57739H3.08585C2.95467 5.57739 2.82348 5.479 2.82348 5.31502V4.49511C2.82348 4.36393 2.92187 4.23274 3.08585 4.23274H3.90576C4.03694 4.23274 4.16813 4.33113 4.16813 4.49511V5.31502C4.13533 5.479 4.03694 5.57739 3.87296 5.57739ZM6.49666 8.52904H5.67675C5.54557 8.52904 5.41438 8.43065 5.41438 8.26667V7.44677C5.41438 7.31558 5.51277 7.1844 5.67675 7.1844H6.49666C6.62784 7.1844 6.75903 7.28279 6.75903 7.44677V8.26667C6.75903 8.43065 6.66064 8.52904 6.49666 8.52904ZM6.49666 5.57739H5.67675C5.54557 5.57739 5.41438 5.479 5.41438 5.31502V4.49511C5.41438 4.36393 5.51277 4.23274 5.67675 4.23274H6.49666C6.62784 4.23274 6.75903 4.33113 6.75903 4.49511V5.31502C6.75903 5.479 6.66064 5.57739 6.49666 5.57739ZM9.12035 8.52904H8.30045C8.16926 8.52904 8.03808 8.43065 8.03808 8.26667V7.44677C8.03808 7.31558 8.13647 7.1844 8.30045 7.1844H9.12035C9.25154 7.1844 9.38272 7.28279 9.38272 7.44677V8.26667C9.38272 8.43065 9.28433 8.52904 9.12035 8.52904ZM9.12035 5.57739H8.30045C8.16926 5.57739 8.03808 5.479 8.03808 5.31502V4.49511C8.03808 4.36393 8.13647 4.23274 8.30045 4.23274H9.12035C9.25154 4.23274 9.38272 4.33113 9.38272 4.49511V5.31502C9.38272 5.479 9.28433 5.57739 9.12035 5.57739Z"
                                                            fill="#9F9FA0"></path>
                                                    </svg>
                                                </span>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- <div class="form-group">
                                        <label>Account Holder Type</label>
                                        <select name="account_holder_type" class="form-control form-select cstm-select">
                                            <option value="">Select</option>
                                            <option value="Individual"
                                                @if (old('account_holder_type', @$bankDetail->account_holder_type) == 'Individual') selected @endif>
                                                Individual</option>

                                        </select>
                                        @error('account_holder_type')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div> --}}
                                    {{-- <div class="form-group">
                                        <label>Account Type</label>
                                        <select name="account_type" class="form-control form-select">
                                            <option value="">Select</option>
                                            <option value="Standard" @if (old('account_type', @$bankDetail->account_type) == 'Standard') selected @endif>
                                                Standard</option>
                                            <option value="Express" @if (old('account_type', @$bankDetail->account_type) == 'Express') selected @endif>
                                                Express</option>
                                            <option value="Custom" @if (old('account_type', @$bankDetail->account_type) == 'Custom') selected @endif>
                                                Custom
                                            </option>
                                        </select>
                                        @error('account_type')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div> --}}
                                    <div class="form-group">
                                        {{-- <label>Account Number</label> --}}
                                        <input type="text" name="account_number" class="form-control required"
                                            value="{{ old('account_number', @$bankDetail->account_number) }}"
                                            placeholder="Account Number">
                                        @error('account_number')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        {{-- <label>Routing Number</label> --}}
                                        <input type="text" name="routing_number" class="form-control required"
                                            value="{{ old('routing_number', @$bankDetail->routing_number) }}"
                                            placeholder="Routing Number">
                                    </div>


                                </div>
                                <div class="step-btn-box">
                                    {{-- <button type="button" class="cancel-btn">Previous</button> --}}
                                    <button type="submit" id="submit-bankdetails" class="primary-btn">Save
                                        <i class="fa-solid fa-circle-notch fa-spin show-loader"
                                            style="display:none;"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
