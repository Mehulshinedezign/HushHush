@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.updatecategory', [$category->id]) }}" method="post" id="editCategory"
                        enctype="multipart/form-data">
                        <div class="card-header">
                            <h4>{{ __('category.editCategory') }}</h4>
                        </div>
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $category->name }}" placeholder="{{ __('category.placeholders.name') }}">
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
                                            @if ($category->id == $categories->id)
                                                @continue
                                            @endif
                                            <option value="{{ $categories->id }}"
                                                @if ($categories->id == $category->parent_id) selected @endif>{{ $categories->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-row">

                                <div class="form-group col-md-4">
                                    <label>Icon</label>
                                    <input type="file" name="category_image" class="form-control mb-3" />
                                    @if ($category->category_image_url)
                                        <img src="{{ $category->category_image_url }}" width="44" height="44"
                                            style="border-radius: 50%;" />
                                    @endif
                                </div>
                            </div> --}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>{{ __('category.fields.status') }}</label>
                                    <div class="custom-control custom-checkbox ">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status"
                                            value="1" @if ($category->status == '1') checked @endif>
                                        <label class="custom-control-label" for="status">Active</label>
                                    </div>
                                    @if ($errors->has('status'))
                                        <label class="error">{{ $errors->first('status') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-primary mr-2" type="submit">{{ __('buttons.update') }}</button>
                                <a class="btn btn-dark"
                                    href="{{ route('admin.categories') }}">{{ __('buttons.back') }}</a>
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
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery('form#editCategory').validate({
                rules: {
                    name: {
                        required: true,
                        // pattern: /^[a-zA-Z0-9]+( [a-zA-Z0-9]+)*$/
                    },
                    'type[]': 'required',
                },
                messages: {
                    name: {
                        required: '{{ __('category.validations.name') }}',
                        // pattern: 'Category name only accept alphanumeric values.',
                    },
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
        })
        $(document).ready(function() {
            $('.select2-multiple').select2();
        });
    </script>
@endpush
