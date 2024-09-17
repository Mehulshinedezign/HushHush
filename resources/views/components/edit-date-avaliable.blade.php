<div class="cstmdata_wrap mb-3">
    <div class="col-12 col-sm-12 col-md-12">
        <!-- Display the first date range input -->
        <div class="form-group">
            <label for="">Non-Available Dates</label>
            <div class="formfield">
                <input type="text" name="non_availabile_dates[0]"
                class="input-bg date-icon form-control non-availability productForm color-white-bss"
                placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off"
                onfocus="initDateRangePicker(this, dateOptions)" readonly
                @if (isset($product[0])) value="{{ $product[0]}}" @endif>
                @error('non_availabile_dates.0')
                    <label class="error-messages">{{ $message }}</label>
                @enderror
            </div>
        </div>
    </div>

    <span class="add-more-daterangepicker addlocation adddate float-left button outline-btn">
        <span><i class="fas fa-plus"></i></span> Add
    </span>
    <div class="append-non-available-dates col-md-12">
        @for ($i = 1; $i < old('non_available_date_count', count($product)); $i++)
            <div class="cp-unavailabilities">
                <div class="">
                    <span class="remove-daterangepicker float-right red-text">
                        <span><i class="fas fa-minus"></i></span> Remove
                    </span>
                    <input type="text" name="non_availabile_dates[{{ $i }}]"
                        class="form-control input-bg mb-3 date-icon non-availability color-white-bss"
                        placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off"
                        value="{{ old('non_availabile_dates.' . $i, $product[$i]) }}"
                        readonly
                        onfocus="initDateRangePicker(this, dateOptions)">
                    @error('non_availabile_dates.' . $i)
                        <label class="error-messages">{{ $message }}</label>
                    @enderror
                </div>
            </div>
        @endfor
    </div>

    <div class="clone-non-available-date-container hidden cp-unavailabilities">
        <div class="">
            <span class="remove-daterangepicker float-right red-text" style="cursor: pointer;">
                <span><i class="fas fa-minus"></i></span> Remove
            </span>
            <input type="text" class="form-control input-bg mb-3 date-icon color-white-bss" readonly
                placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
        </div>
    </div>
</div>
