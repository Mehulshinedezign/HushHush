@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.savecategory') }}" method="post" id="addCategory"
                        enctype="multipart/form-data">
                        <div class="card-header">
                            <h4>{{ __('category.addCategory') }}</h4>
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        placeholder="{{ __('category.placeholders.name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Parent Category</label>
                                    <select name="parent_id"
                                        class="form-control @error('main_category') is-invalid @enderror">
                                        <option value="">Select </option>
                                        @foreach (getParentCategory() as $categories)
                                            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Icon<span class="text-danger">*</span></label>
                                    <input type="file" name="category_image" class="form-control" />
                                    @error('category_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.status') }}</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status"
                                            @if (old('status') == '0') value="0" @else value="1" checked @endif>
                                        <label class="custom-control-label" for="status">Active</label>
                                    </div>
                                </div>
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
            jQuery('form#addCategory').validate({
                rules: {
                    name: {
                        required: true,
                        // pattern: /^[a-zA-Z0-9]+( [a-zA-Z0-9]+)*$/

                    },
                    // category_image: 'required',
                    'type[]': 'required',
                },
                messages: {
                    name: {
                        required: '{{ __('category.validations.name') }}',
                        // pattern: 'Category name only accept alphanumeric values.'
                    },
                    // category_image: {
                    //     required: '{{ __('category.validations.image') }}'
                    // },
                    'type[]': {
                        required: '{{ __('category.validations.type') }}'
                    }
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
