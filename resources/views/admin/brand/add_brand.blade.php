@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.savebrand') }}" method="post" id="addBrand"
                        enctype="multipart/form-data">
                        <div class="card-header">
                            <h4>{{ __('brand.addBrand') }}</h4>
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('brand.fields.brand_name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="brand_name"
                                        class="form-control @error('brand_name') is-invalid @enderror" value="{{ old('brand_name') }}"
                                        placeholder="{{ __('brand.placeholders.brand_name') }}">
                                    @error('brand_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="form-group col-md-4">
                                        <label>{{ __('brand.fields.status') }}</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="status" name="status"
                                                @if (old('status') == '0') value="0" @else value="1" checked @endif>
                                            <label class="custom-control-label" for="status">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12"> <button class="btn btn-primary"
                                        type="submit">{{ __('buttons.save') }}</button></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery('form#addBrand').validate({
                rules: {
                    brand_name: 'required',

                },
                messages: {
                    brand_name: {
                        required: '{{ __('brand.validations.brand_name') }}'
                    },
                },
                errorPlacement: function(label, element) {
                    if (jQuery(element).hasClass('select2-multiple')) {
                        label.insertAfter($(element).parent())
                    } else {
                        label.insertAfter(element)
                    }

                }
            });
        });

        $(document).ready(function() {
            $('.select2-multiple').select2();
        });
    </script>
@endpush