<div class="cstmdata_wrap">
    <div class="col-12 col-sm-12 col-md-12">
        {{-- <label> <span class="smallFont grey-text">(If any)</span> </label> --}}
        <div class="form-group">
            <label for="">Non-Available Dates</label>
            <div class="formfield">
                <input type="text" name="non_availabile_dates[0]"
                    class="input-bg mb-3 date-icon form-control non-availability color-white-bss" readonly
                    placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off"
                    onfocus="initDateRangePicker(this, dateOptions)">
                @error('non_availabile_dates.0')
                    <label class="error-messages">{{ $message }}</label>
                @enderror
            </div>
        </div>

    </div>
    <div class="add-more-daterangepicker-main">
        <span class="add-more-daterangepicker addlocation adddate float-left button outline-btn"> <span><i class="fas fa-plus"></i></span>
            Add
        </span>
        <div class="append-non-available-dates">
        </div>
        <div class="clone-non-available-date-container hidden cp-unavailabilities text-right">

            <input type="text" class="form-control input-bg date-icon color-white-bss" readonly
                placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
                <span class="remove-daterangepicker float-right red-text "> <span><i class="fas fa-minus"></i></span>
                </span>
        </div>
    </div>
</div>
