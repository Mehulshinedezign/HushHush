@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <x-admin_alert />
                <div class="card author-box commission-card">
                    <div class="card-header">
                        <h4>Commission</h4>
                    </div>
                    <form action="{{ route('admin.commission') }}" method="POST" id="commission">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @foreach ($adminSettings as $adminSetting)
                                    <div class="form-group col-md-6 col-12">
                                        <label>{{ ucwords(str_replace('_', ' ', $adminSetting->key)) }} Type</label>
                                        <div class="input-group">
                                            <div class="formfield symbol-formfield">
                                                <select name="{{ $adminSetting->key }}_type"
                                                    class="form-control checktype @error("{{ $adminSetting->key }}_type") is-invalid @enderror">
                                                    <option value="Percentage"
                                                        @if ($adminSetting->type == 'Percentage') selected @endif>Percentage
                                                    </option>
                                                    <option value="Fixed"
                                                        @if ($adminSetting->type == 'Fixed') selected @endif>
                                                        Fixed</option>
                                                </select>
                                                <div class="symbol"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6 col-12">
                                        <label>{{ ucwords(str_replace('_', ' ', $adminSetting->key)) }}</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control two-decimals @error("$adminSetting->key") is-invalid @enderror"
                                                name="{{ $adminSetting->key }}" value="{{ $adminSetting->value }}"
                                                maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                            @error($adminSetting->key)
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button id="userSave" class="btn btn-primary">{{ __('buttons.saveChanges') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('validation.js_commision')
    <script>
        $(document).ready(function() {

            $(".two-decimals").on("keypress", function(evt) {
                var txtBox = $(this);
                var charCode = (evt.which) ? evt.which : evt.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
                    return false;
                else {
                    var len = txtBox.val().length;
                    var index = txtBox.val().indexOf('.');
                    if (index > 0 && charCode == 46) {
                        return false;
                    }
                    if (index > 0) {
                        var charAfterdot = (len + 1) - index;
                        if (charAfterdot > 3) {
                            return false;
                        }
                    }
                }
                return txtBox;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // change symbols
            var type = $('.checktype').val();

            if (type == 'Percentage') {
                // $('#checkcommission').attr('min', '1');
                // $('#checkcommission').attr('max', '99');
                $('.symbol').text('%');
            } else {
                // $('#checkcommission').removeAttr('min', '1');
                // $('#checkcommission').removeAttr('max', '99');
                $('.symbol').text('$');
            }
            $('.checktype').on('click', function() {
                var type = $('.checktype').val();

                if (type == 'Percentage') {
                    // $('#checkcommission').attr('min', '1');
                    // $('#checkcommission').attr('max', '99');
                    $('.symbol').text('%');

                } else {
                    // $('#checkcommission').removeAttr('min', '1');
                    // $('#checkcommission').removeAttr('max', '99');
                    $('.symbol').text('$');
                }
            });

        });
    </script>
@endpush
