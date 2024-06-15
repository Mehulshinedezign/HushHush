@extends('layouts.admin')
@section('content')
    <div class="section-body">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.updateproduct', [$product->category_id, $product->id]) }}" method="post" id="editCategoryProduct" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>{{ __('product.title') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>{{ __('product.fields.name') }}</label>
                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" placeholder="{{ __('product.placeholders.productName') }}" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>{{ __('product.fields.category') }}</label>
                                    <select name="category" class="form-control form-control-sm" disabled>
                                        <option value="">Select</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>{{ __('product.fields.description') }}</label>
                                    <textarea name="description" readonly>{{ $product->description }}</textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>Rent <small>(per day)</small></label>
                                    <input type="text" name="rent" class="form-control" value="{{ $product->rent }}" placeholder="{{ __('product.placeholders.rentPerDay') }}" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>Product Estimated Value</label>
                                    <input type="text" name="price" class="form-control" value="{{ $product->price }}" placeholder="{{ __('product.placeholders.productPrice') }}" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>Product Images</label>
                                    <ul class="product-img-preview">
                                        @foreach($product->allImages as $index => $image)
                                            <li>
                                                <div class="preview-img">
                                                    <img src="{{ $image->url }}">
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            @for($i = 0; $i < $product->locations->count(); $i++)
                                <div class="form-row">
                                    <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                        <label>{{ __('product.fields.location') }}</label>
                                        <input type="text" name="location[{{ $i }}]" class="form-control" placeholder="{{ __('product.placeholders.location') }}" value="{{ $product->locations[$i]->map_address }}" readonly>                                   
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                        <label>Exact Location</label>
                                        <input type="text" name="location[{{ $i }}]" class="form-control" placeholder="{{ __('product.placeholders.location') }}" value="{{ $product->locations[$i]->custom_address }}" readonly>                                   
                                    </div>
                                </div>
                            @endfor

                            <div class="form-row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-6">
                                    <label>Non Availability Date</label>
                                    @for($i = 0; $i < $product->nonAvailableDates->count(); $i++)
                                        <input type="text" name="non_availabile_dates[{{ $i }}]" class="date-icon form-control mb-2" placeholder="{{ __('product.placeholders.nonAvailableDates') }}" value="{{ date($global_date_format, strtotime($product->nonAvailableDates[$i]->from_date)) . ' ' .$global_date_separator. ' '. date($global_date_format, strtotime($product->nonAvailableDates[$i]->to_date)) }}" readonly>                                   
                                    @endfor                            
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-8 col-lg-8 col-xl-6">
                                    <label>{{  __('product.fields.status') }}</label>
                                    <div class="custom-control custom-checkbox ">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="Active"  @if($product->status == 'Active') checked @endif>
                                        <label class="custom-control-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">{{  __('buttons.update') }}</button>
                            <a class="btn btn-dark" href="{{ url()->previous() }}">{{  __('buttons.back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.tiny.cloud/1/rcqfj1cr6ejxsyuriqt95tnyc64joig2nppf837i8qckzy90/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script>
        tinymce.init({
            selector: 'textarea',
            readonly: 1,
            branding: false,
        });
        
        jQuery(document).ready(function() {
            
            jQuery('form#editCategoryProduct').validate({
                ignore: ':hidden:not(textarea)',
                ignore: [],       
                rules: {
                    name: 'required',
                    category: 'required',
                    description: 'required',
                    location: 'required',
                    rent: {
                        required: true,
                        numeric: /^[+-]?\d+(\.\d+)?$/
                    },
                    security: {
                        required: true,
                        numeric: /^[+-]?\d+(\.\d+)?$/
                    },
                    quantity: {
                        required: true,
                        digits: true
                    },
                    price: {
                        required: true,
                        numeric: /^[+-]?\d+(\.\d+)?$/
                    },
                    thumbnail_image: {
                        accept: "image/jpeg,image/jpg,image/png",
                        filesize: 2000000
                    },
                    "gallery_image[]": { 
                        accept: "image/jpeg,image/jpg,image/png",
                        filesize: 2000000
                    }

                },
                messages: {
                    name: {
                        required: '{{ __("product.validations.productNameRequired") }}'
                    },
                    category: {
                        required: '{{ __("product.validations.productCategory") }}',
                    },
                    description: {
                        required: '{{ __("product.validations.productDescription") }}',
                    },
                    location: {
                        required: '{{ __("product.validations.locationRequired") }}',
                    },
                    rent: {
                        required: '{{ __("product.validations.rentRequired") }}',
                        numeric: '{{ __("product.validations.rentRegex") }}',
                    },
                    security: {
                        required: '{{ __("product.validations.securityRequired") }}',
                        numeric: '{{ __("product.validations.securityRegex") }}',
                    },
                    quantity: {
                        required: '{{ __("product.validations.quantityRequired") }}',
                        digits: '{{ __("product.validations.quantityRegex") }}',
                    },
                    price: {
                        required: '{{ __("product.validations.priceRequired") }}',
                        numeric: '{{ __("product.validations.priceRegex") }}',
                    },
                    thumbnail_image: {
                        accept: '{{ __("product.validations.thumbnailExtenstion") }}',
                        filesize: '{{ __("product.validations.thumbnailSize") }}',
                    },
                    "gallery_image[]": {
                        accept: '{{ __("product.validations.galleryExtenstion") }}',
                        filesize: '{{ __("product.validations.gallerySize") }}',
                    }
                }
            });
        })
    </script>
@stop
