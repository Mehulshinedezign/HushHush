@extends('layouts.front')
@section('title', $product->name)
@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('index', ['category[1]' => $product->category->id]) }}">{{ $product->category->name }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-7">
                    <div class="gallery-single">
                        @foreach ($product->allImages as $image)
                            <div class="holder-single">
                                <img src="{{ $image->url }}" class="img-fluid">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div class="content_single">
                        <div class="content_head pro_content">
                            <h3>{{ ucfirst($product->name) }}</h3>
                            {{-- <p>Retail value: ${{ $product->price }} </p> --}}
                            <p>${{ $product->rent }} <span>/day</span></p>
                            <div class="bottom d-flex">
                                <div class="sku_tgs"> <span>Brand:</span> {{ @$product->get_brand->name }}</div>
                                {{-- <div class="sku_tgs"> <span>Color: </span> {{ @$product->get_color->name }} </div>
                                <div class="sku_tgs"> <span>Size: </span> {{ @$product->get_size->name }}</div> --}}
                                <div class="sku_tgs"> <span>Location: </span> {{ $product->locations[0]->map_address }}
                                </div>
                            </div>
                            {{-- <hr> --}}
                            <div class="select_date mt-3">
                                <label>Retal Value: ${{ @$product->price }}</label>
                            </div>

                            <div class="select_date mt-0">
                                {{-- <div class="form-group"> --}}
                                <label>Color: {{ @$product->get_color->name }}</label>
                                {{-- <div class="formfield">
                                        <p></p>
                                    </div> --}}
                                {{-- </div> --}}
                            </div>
                            <div class="select_date mt-0">
                                {{-- <div class="form-group"> --}}
                                <label>Size: {{ @$product->get_size->name }}</label>
                                {{-- <div class="formfield">
                                        <p></p>
                                    </div> --}}
                                {{-- </div> --}}
                            </div>
                            <div class="select_date">
                                <div class="form-group">
                                    {{-- <label>Select your Rental date</label> --}}
                                    <div class="formfield">
                                        <input type="text" @if ($product->user_id == auth()->user()->id) disabled @endif readonly
                                            class="form-control reservation_date" name="reservation_date"
                                            placeholder="Rental Period" onfocus="initDateRangePicker(this, dateOptions)">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20"
                                                viewBox="0 0 19 20" fill="none">
                                                <path
                                                    d="M5.64531 1.4418C5.64531 0.956175 5.25164 0.5625 4.76602 0.5625C4.28039 0.5625 3.88672 0.956175 3.88672 1.4418V2.32109C3.88672 2.80672 4.28039 3.20039 4.76602 3.20039C5.25164 3.20039 5.64531 2.80672 5.64531 2.32109V1.4418Z"
                                                    fill="#DEE0E3" />
                                                <path
                                                    d="M15.3191 1.4418C15.3191 0.956175 14.9255 0.5625 14.4398 0.5625C13.9542 0.5625 13.5605 0.956175 13.5605 1.4418V2.32109C13.5605 2.80672 13.9542 3.20039 14.4398 3.20039C14.9255 3.20039 15.3191 2.80672 15.3191 2.32109V1.4418Z"
                                                    fill="#DEE0E3" />
                                                <path
                                                    d="M0.810547 7.15625V17.2682C0.810547 18.2393 1.59796 19.0268 2.56914 19.0268H16.6379C17.6091 19.0268 18.3965 18.2393 18.3965 17.2682V7.15625H0.810547ZM6.08633 15.9492C6.08633 16.435 5.69284 16.8285 5.20703 16.8285H4.32773C3.84192 16.8285 3.44844 16.435 3.44844 15.9492V15.0699C3.44844 14.5841 3.84192 14.1906 4.32773 14.1906H5.20703C5.69284 14.1906 6.08633 14.5841 6.08633 15.0699V15.9492ZM6.08633 11.1131C6.08633 11.5989 5.69284 11.9924 5.20703 11.9924H4.32773C3.84192 11.9924 3.44844 11.5989 3.44844 11.1131V10.2338C3.44844 9.74798 3.84192 9.35449 4.32773 9.35449H5.20703C5.69284 9.35449 6.08633 9.74798 6.08633 10.2338V11.1131ZM10.9225 15.9492C10.9225 16.435 10.529 16.8285 10.0432 16.8285H9.16387C8.67806 16.8285 8.28457 16.435 8.28457 15.9492V15.0699C8.28457 14.5841 8.67806 14.1906 9.16387 14.1906H10.0432C10.529 14.1906 10.9225 14.5841 10.9225 15.0699V15.9492ZM10.9225 11.1131C10.9225 11.5989 10.529 11.9924 10.0432 11.9924H9.16387C8.67806 11.9924 8.28457 11.5989 8.28457 11.1131V10.2338C8.28457 9.74798 8.67806 9.35449 9.16387 9.35449H10.0432C10.529 9.35449 10.9225 9.74798 10.9225 10.2338V11.1131ZM15.7586 15.9492C15.7586 16.435 15.3651 16.8285 14.8793 16.8285H14C13.5142 16.8285 13.1207 16.435 13.1207 15.9492V15.0699C13.1207 14.5841 13.5142 14.1906 14 14.1906H14.8793C15.3651 14.1906 15.7586 14.5841 15.7586 15.0699V15.9492ZM15.7586 11.1131C15.7586 11.5989 15.3651 11.9924 14.8793 11.9924H14C13.5142 11.9924 13.1207 11.5989 13.1207 11.1131V10.2338C13.1207 9.74798 13.5142 9.35449 14 9.35449H14.8793C15.3651 9.35449 15.7586 9.74798 15.7586 10.2338V11.1131Z"
                                                    fill="#DEE0E3" />
                                                <path
                                                    d="M18.3965 6.2793V3.64141C18.3965 2.67022 17.6091 1.88281 16.6379 1.88281H16.1982V2.32246C16.1982 3.29233 15.4095 4.08105 14.4396 4.08105C13.4698 4.08105 12.6811 3.29233 12.6811 2.32246V1.88281H6.52598V2.32246C6.52598 3.29233 5.73725 4.08105 4.76738 4.08105C3.79752 4.08105 3.00879 3.29233 3.00879 2.32246V1.88281H2.56914C1.59796 1.88281 0.810547 2.67022 0.810547 3.64141V6.2793H18.3965Z"
                                                    fill="#DEE0E3" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn_book btn_black" @if ($product->user_id == auth()->user()->id) disabled @endif>Book
                                Now</button>

                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Description
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample1">
                                    <div class="accordion-body">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Product Condition
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">
                                        {{ $product->condition }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="leave_review">
                            <h4>Lender</h4>
                            <a href="{{ route('lender', jsencode_userdata($product->retailer->id)) }}">
                                <div class="profile_review">
                                    <div class="review_pro">
                                        @if ($product->retailer->frontend_profile_url)
                                            <img src="{{ $product->retailer->frontend_profile_url }}">
                                        @else
                                            <img src="{{ asset('front/images/profile1.png') }}">
                                        @endif
                                    </div>
                                    <div class="img_review">
                                        <h3>{{ $product->retailer->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- product rating -->
                        @include('product-rating')
                        <!-- product rating end -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End  single product page top section-->
    <!--You may like section-->
    <section>
        <div class="slider-section profile-slider-section mt-0">
            <div class="container">
                <h6>You May Also Like</h6>
                <div class="profile-custom-slider ">
                    <x-single-product :products="$relatedProducts" />
                </div>
            </div>
        </div>
    </section>
    <!-- End You may like section-->

    @include('instruction')

    @include('product-booking')
    <div class="payment-fixed-section" id="payment"></div>
@endsection
@push('scripts')
    @includeFirst(['validation'])
    @includeFirst(['validation.js_product_review'])
    @include('layouts.notification-alert')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        rentPrice = "{{ $product->rent }}";
        const paymentjs = "{{ asset('js/custom/payment.js') }}";
        var stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const Auth = "{{ auth()->user()->name }}";
        const intent = "{{ auth()->user()->createSetupIntent()->client_secret }}";
        const is_approved = "{{ auth()->user()->is_approved }}";
        const product = "{{ auth()->user()->products }}";
    </script>
    <script src="{{ asset('js/custom/single-product.js') }}?ver={{ now() }}"></script>
@endpush
