@extends('layouts.retailer')
@section('title', 'Products')
@section('links')
    <link href="{{ asset('/front/css/product.css') }}" rel="stylesheet" />
    <script>
        dateOptions["minDate"] = new Date();
    </script>
@stop
@section('content')
    <div class="right-content innerpages">
        <div class="d-flex justify-content-between align-items-center">
            <form action="{{ route('retailer.products') }}" method="get" class="search_bx d-flex align-items-center">
                <div class="d-flex align-items-center w-100 gap-3">
                    <h2 class="heading_dash">Products</h2>
                    <div class="input-group"> <span class="input-group-icon border-0" id="search-addon">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="search" class="form-control" placeholder="Search By Name" aria-label="Search"
                            aria-describedby="search-addon" name="search" value="{{ request()->search }}" />
                    </div>
                </div>
            </form>
            <div class="btn-right">
                <button type="button" class="btn btn-dark open-product-popup" data-bs-toggle="modal"
                    data-bs-target="#NproductModal"> <img src="{{ asset('front/images/plus.svg') }}"> Add Products </button>
            </div>
        </div>
        <!-- product tabing-->
        <div class="cardtab">
            <nav>
                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">All</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Currently Booked for
                        Renting</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- All Tab start-->
                <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    @if (count($products) > 0)
                        <x-product-row :products="$products" />
                        {{ $products->links('pagination::product-list') }}
                    @else
                        <h3 class="text-center">Products not found</h3>
                    @endif
                </div>
                <!-- All Tab end-->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="d-flex align-items-cener not-found">
                        <img src="https://cdn-icons-png.flaticon.com/512/7465/7465664.png">
                        <h3 class="text-center">Not found</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="NproductModal" tabindex="-1" aria-labelledby="NewProductModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="ajax-form-html">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @includeFirst(['validation'])
    @includefirst(['validation.js_product'])
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places">
    </script>
    <script src="{{ asset('js/custom/google-map.js') }}"></script>
    <script src="{{ asset('js/custom/product-add-edit.js') }}"></script>
    <script>
        var htmlForm = `@include('retailer.include.product-from', ['product' => new App\Models\Product()])`;
    </script>
@endpush
