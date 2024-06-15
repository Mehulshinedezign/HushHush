@extends('layouts.admin')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <form method="post" id="viewCategory">
                    <div class="card-header">
                        <h4>{{ __('category.viewCategory') }}</h4>
                    </div>
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>{{ __('category.fields.name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}"
                                    disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Parent Category</label>
                                <select name="parent_id"
                                    class="form-control @error('main_category') is-invalid @enderror" disabled>
                                    <option value="">Select </option>
                                    @foreach(getParentCategory() as $categories)
                                    @if($category->id == $categories->id)@continue @endif
                                    <option value="{{$categories->id}}" @if($categories->id == $category->parent_id)
                                        selected @endif>{{$categories->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>{{ __('Size Type') }} <span class="text-danger">*</span></label>
                                <select name="type[]" class="form-control select2-multiple" multiple data-placeholder="Select Size Type">
                                    <option value="">Select Size Type</option>
                                    @foreach(getTypes() as $key => $type)
                                    <option value="{{$key}}" @foreach($category->size_type as $selected_type) @if($selected_type->size_type == $key) selected @endif @endforeach>{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                                @if($category->category_image_url)
                            <div class="form-group col-md-4">
                                <label>Icon</label>
                                <br>
                                <img src="{{ $category->category_image_url }}" width="44" height="44"
                                    style="border-radius: 50%;" />
                            </div>
                            @endif
                        </div>
                        <div class="form-row"> 
                            <div class="form-group col-md-4">
                                <label>{{ __('category.fields.status') }}</label>
                                <div class="input-group">
                                    <input type="checkbox" name="status" value="Active" @if($category->status ==
                                    'Active') checked @endif disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary" href="{{ route('admin.categories') }}">{{ __('buttons.back') }}</a>
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
    $(document).ready(function() {
        $('.select2-multiple').select2();
    });
</script>
@endpush