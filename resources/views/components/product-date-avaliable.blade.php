<div class="cstmdata_wrap">
    <div class="col-12 col-sm-12 col-md-12">
        {{-- <label> <span class="smallFont grey-text">(If any)</span> </label> --}}

        <input type="text" name="non_availabile_dates[0]" class="input-bg mb-3 date-icon form-control non-availability"
            placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off"
            onfocus="initDateRangePicker(this, dateOptions)"
            @if (count($product->nonAvailableDates) > 0) value="{{ date('m/d/Y', strtotime($product->nonAvailableDates[0]->from_date)) . ' ' . $global_date_separator . ' ' . date('m/d/Y', strtotime($product->nonAvailableDates[0]->to_date)) }}" @endif
            readonly>

        @error('non_availabile_dates.0')
            <label class="error-messages">{{ $message }}</label>
        @enderror
    </div>
    <div class="append-non-available-dates col-md-12">
        <span class="add-more-daterangepicker addlocation adddate float-left"> <span><i class="fas fa-plus"></i></span>
            Add</span>
        @for ($i = 1; $i < old('non_available_date_count', $product->nonAvailableDates->count()); $i++)
            <div class="cp-unavailabilities">
                <div class="">

                    <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span>
                        Remove</span>
                    <input type="text" name="non_availabile_dates[{{ $i }}]"
                        class="form-control input-bg mb-3 date-icon non-availability"
                        data-from="{{ date('m/d/Y', strtotime($product->nonAvailableDates[$i]->from_date)) }}"
                        data-to="{{ date('m/d/Y', strtotime($product->nonAvailableDates[$i]->to_date)) }}"
                        placeholder="{{ __('product.placeholders.nonAvailableDates') }}" autocomplete="off"
                        onfocus="initDateRangePicker(this, dateOptions)"
                        value="{{ old('non_availabile_dates.' . $i, date('m/d/Y', strtotime($product->nonAvailableDates[$i]->from_date)) . ' ' . $global_date_separator . ' ' . date('m/d/Y', strtotime($product->nonAvailableDates[$i]->to_date))) }}"
                        readonly>
                    @error('non_availabile_dates.' . $i)
                        <label class="error-messages">{{ $message }}</label>
                    @enderror
                </div>
            </div>
        @endfor
    </div>
    <div class="clone-non-available-date-container hidden cp-unavailabilities">
        <div class="">
            <span class="remove-daterangepicker float-right red-text"> <span><i class="fas fa-minus"></i></span>
                Remove</span>
            <input type="text" class="form-control input-bg mb-3 date-icon" readonly
                placeholder="{{ __('product.placeholders.nonAvailableDates') }}"
                onfocus="initDateRangePicker(this, dateOptions)" autocomplete="off">
        </div>
    </div>
</div>
