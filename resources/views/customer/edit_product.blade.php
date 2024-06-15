@extends('layouts.front')

@section('links')
    <link href="{{ asset('/front/css/product.css') }}" rel="stylesheet" />
    <script>
        dateOptions["minDate"] = new Date();
    </script>
@stop
@section('content')
    <section>
        <div class="detail-page-profile-sec">
            <div class="container">
                <div class="detail-page-profile-tittle">
                    <h4>My Closet</h4>
                </div>
                <div class="detail-page-profile-box">
                    <div class="profile-box-detail">
                        <div class="profile-box-img-box">
                            @if (auth()->user()->frontend_profile_url)
                                <img src="{{ auth()->user()->frontend_profile_url }}">
                            @else
                                <img src="{{ asset('front/images/profile1.png') }}">
                            @endif
                        </div>
                        <h5 class="profile-box-name">{{ auth()->user()->name }}</h5>
                        <div class="profile-detail-location">
                            <img src="images/flag1.png" alt="">
                            <p>
                                @if (isset(auth()->user()->userDetail))
                                    {{ isset(auth()->user()->userDetail->country) ? auth()->user()->userDetail->country->name : '' }},

                                    {{ isset(auth()->user()->userDetail->state) ? auth()->user()->userDetail->state->name : '' }},
                                    {{ isset(auth()->user()->userDetail->city) ? auth()->user()->userDetail->city->name : '' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="profile-box-about-detail">
                        @if (isset(auth()->user()->userDetail->about))
                            <h6>About</h6>
                        @endif
                        {!! isset(auth()->user()->userDetail) ? auth()->user()->userDetail->about : '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <h4 style="text-align: center">My Closet</h4>
    <div class="container">
        <div class="tab-content" id="nav-tabContent">
            <!-- All Tab start-->
            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                @if (count($products) > 0)
                    <section>
                        <div class="slider-section profile-slider-section">
                            <div class="container">
                                <h4>Products</h4>
                                <div class="profile-custom-slider">
                                    <x-product-row :products="$products" />
                                </div>
                                <div class="editProductSlider">
                                    {{ $products->links('pagination::product-list') }}
                                </div>
                            </div>
                        </div>
                    </section>
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
@endsection
@push('scripts')
    @includeFirst(['validation'])
    @includefirst(['validation.js_product'])
    <script>
        var htmlForm = `@include('retailer.include.product-from', ['product' => new App\Models\Product()])`;
    </script>
    <script src="{{ asset('js/custom/product-add-edit.js') }}?ver={{ now() }}"></script>
@endpush
