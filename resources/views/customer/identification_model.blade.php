<div class="modal fade identification-modal" id="mutlistepForm1" tabindex="-1" aria-labelledby="mutlistepFormLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('identification') }}" id="identificationprofile" enctype="multipart/form-data">
                    @csrf
                    <div class="Mutistep-form-wrapper">
                        <div class="popup-head">
                            <button type="button" class="close" id="close_identification" data-bs-dismiss="modal"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="multistep-box">
                            <div class="form-steps step1">
                                <h6 class="mb-2">Id proof</h6>
                                <div class="form-group">
                                    <label></label>
                                    <div class="form-control input-file-uploader">
                                        <input type="file" name="proof" class="form-control"
                                            accept="image/jpeg, image/png, image/jpg">
                                    </div>
                                    @error('proof')
                                        <label class="error-messages">{{ $message }}</label>
                                    @enderror
                                </div>
                                <div class="step-btn-box single-btn-bx">
                                    <button type="submit" id="submit-identification" class="cancel-btn">Next</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
