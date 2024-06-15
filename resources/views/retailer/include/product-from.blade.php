<div class="modal-header">
    <h5 class="modal-title text-center" id="NewProductModal">
        {{ $product->id ? 'Edit Product' : 'Lend your closet' }}
    </h5>
    <button type="button" class="btn-close proclose" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @php
        if ($product->id) {
            $action = route('updateproduct', [jsencode_userdata($product->id)]);
        } else {
            $action = route('saveproduct');
        }
    @endphp
    <form action="{{ $action }}" method="post" id="addProduct" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <input type="hidden" name="location_count" value="{{ old('location_count', 1) }}">
        <input type="hidden" name="non_available_date_count" value="{{ old('non_available_date_count', 1) }}">
        <input type="hidden" name="rentaltype" value="Day">
        <div class="ajax-response"></div>

        <!-- Product upload images -->
        <x-product-upload-image :product="$product" />
        <div class="row lend-closet">
            <div class="col-md-6">
                <div class="formfield">
                    {{-- <label for="text">Product Name<span class="text-danger">*</span></label> --}}
                    <input type="text" class="form-control" name="name" placeholder="Product Name"
                        value="{{ $product->name }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="formfield">
                    {{-- <label for="text">Category/Subcategory<span class="text-danger">*</span></label> --}}
                    <div class="select_box cstm-select">
                        <div class="select-option">
                            <select class="form-select" name="category" aria-label="Default select example">
                                <option value="">Category</option>
                                @foreach (getParentCategory() as $category)
                                    <option value="{{ jsencode_userdata($category->id) }}"
                                        @if ($product->category_id == $category->id) selected @endif>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                {{-- <div class="select-option" id="sub_cat">
                            <select class="form-select" name="sub_cat">
                                <option value="">Sub category</option>
                            </select>
                        </div> --}}

            </div>

            <div class="col-md-4">
                <div class="formfield">
                    {{-- <label for="text">Type/Size<span class="text-danger">*</span></label> --}}
                    <div class="select_box cstm-select">
                        {{-- <div class="select-option" id="type">
                            <select class="form-select" name="type">
                                <option value="">Type</option>
                            </select>
                        </div> --}}
                        <div class="select-option" id="size">
                            <select class="form-select size-required" name="size">
                                <option value="">Size</option>
                                @foreach (getAllsizes() as $size)
                                    <option value="{{ $size->id }}"
                                        @if ($product->size == $size->id) selected @endif>{{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control d-none" name="other_size"
                                placeholder="Enter Size">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="formfield">
                    {{-- <label for="text">Brands</label> --}}
                    <div class="select_box cstm-select">
                        <div class="select-option">
                            <select class="form-select" name="brand">
                                <option value="">Brand</option>
                                @foreach (getBrands() as $brand)
                                    <option value="{{ $brand->id }}"
                                        @if ($product->brand == $brand->id) selected @endif>
                                        {{ $brand->name }}</option>
                                @endforeach
                                {{-- <option value="Other">Other</option> --}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="formfield d-none" id="other_brand">
                    {{-- <label for="text">Other Brand</label> --}}
                    <input type="text" class="form-control" name="other_brand" placeholder="Other Brand">
                </div>
            </div>
            <div class="col-md-4">
                <div class="formfield">
                    {{-- <label for="text">Color</label> --}}
                    <div class="select_box cstm-select">
                        <div class="select-option">
                            <select class="form-select" name="color">
                                <option value="">Color</option>
                                @foreach (getColors() as $color)
                                    <option value="{{ $color->id }}"
                                        @if ($product->color == $color->id) selected @endif>
                                        {{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="formfield">
                    {{-- <label for="text">Condition</label> --}}
                    <div class="select_box cstm-select">
                        <div class="select-option">
                            <select class="form-select" name="product_condition">
                                <option value="">Condition</option>
                                <option value="Excellent" @if ($product->condition == 'Excellent') selected @endif>Excellent
                                </option>
                                <option value="Good" @if ($product->condition == 'Good') selected @endif>Good</option>
                                <option value="Bad" @if ($product->condition == 'Bad') selected @endif>Fine</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="formfield">
                    {{-- <label for="text">City/Neighborhood<span class="text-danger">*</span></label> --}}
                    <div class="select_box cstm-select">
                        <div class="select-option">
                            <select class="form-select get-city" name="neighborhoodcity"
                                aria-label="Default select example">
                                <option value="">City</option>
                                @foreach (neighborhoodcity() as $city)
                                    <option value="{{ $city->id }}"
                                        @if ($product->city == $city->id) selected @endif>{{ ucwords($city->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-option" id="neighborhood">
                            <i id="neighborlist" class="fa-solid fa-circle-notch fa-spin d-none"></i>
                            <select class="neighborhood form-select" name="neighborhood">
                                <option value="">Neighborhood</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add here market prize and url of a product --}}  
            <div class="col-md-12">
                <div class="formfield">
                    {{-- <label for="text">Description<span class="text-danger">*</span></label> --}}
                    <textarea class="form-control" name="description" placeholder="Description">{{ $product->description }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="formfield">
                    <input type="text" class="form-control" name="product_market_value" placeholder="Product market value"
                        value="{{ $product->product_market_value }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="formfield">
                    <input type="text" class="form-control" name="product_link" placeholder="Product link"
                        value="{{ $product->product_link }}">
                </div>
            </div>

            {{-- <div class="col-md-6">
                <div class="formfield">
                    <label for="text">Pickup Location<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="pickup_location" placeholder="Text">{{ $product->locations[0]->map_address ?? '' }}</textarea>
                </div>
            </div> --}}
        </div>

        <!-- Product pickup location -->

        {{-- <x-product-pickup-location :product="$product" /> --}}

        <!-- Product Non Availability Date -->
        <div class="row">
            <div class="col-md-4">
                <x-product-date-avaliable :product="$product" />
            </div>
            <div class="col-md-4">
                <div class="formfield">
                    {{-- <label for="text">Retail Value ($)<span class="text-danger">*</span></label> --}}
                    <input type="text" class="form-control amount-limit doller-input" id = "retailvalue"
                        name="price" placeholder="Retail Value"
                        value="{{ $product->price == 0.0 ? '' : $product->price }}">
                    <div class="doller-symbol">$
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="formfield">
                    {{-- <label for="text">Rental Price/Day ($)<span class="text-danger">*</span></label> --}}
                    <input type="text" class="form-control amount-limit doller-input" name="rent"
                        id="rentprice" placeholder="Rental Price/Day"
                        value="{{ $product->rent == 0.0 ? '' : $product->rent }}">
                    <div class="doller-symbol" id="Rental-price">$</div>
                </div>
                <p id ="amount"></p>
                <label for="" style="width: 254px;font-size:13px">Rental price should be greater than
                    ${{ number_format(adminsetting()->value, 2) }}.</label>
            </div>
        </div>
        <div class="public-with-btn-bx">
            <div class="formfield">
                <div class="checkbox_cstm">
                    <input type="checkbox" class="styled-checkbox" id="status" name="status" checked="checked"
                        value="{{ old('status', $product->status) == '0' ? '0' : '1' }}"
                        @if ($product->disabled_through == 'Admin') disabled @endif>
                    <label for="status">Public</label>
                </div>
            </div>
            <div class="btn-txt-bx">
                {{-- <p>The daily rental price will be reduced by 10% each day upto the 30% of the rental value.</p> --}}
                <div class="btn-holder productsave">
                    <button class="btn btn-dark submit" id="saveProduct">
                        @if (!auth()->user()->vendorBankDetails)
                            {{ 'Next' }}
                        @elseif ($product->id)
                            {{ 'Update' }}
                            @else{{ 'Post' }}
                        @endif &nbsp;
                        <i class="fa-solid fa-circle-notch fa-spin show-loader" style="display:none;"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
