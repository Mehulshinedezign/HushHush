@extends('layouts.front')
@section('title', 'Order Details')
@section('links')
    <link href="{{ asset('/front/css/product.css') }}" rel="stylesheet" />
@stop
@section('content')
    <section class="order-detail-section section-space">
        <div class="container">
            <div class="heading-outerbox d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Rental Details</h5>
                <a href="{{ route('orders') }}" class="backbtn"><i class="fas fa-arrow-left"></i> &nbsp; Back to Orders</a>
            </div>
            <hr class="h-border">
            {{-- <x-alert /> --}}
            <div class="order-detail-box">
                <div class="order-detail-id d-flex align-items-center justify-content-between">
                    {{-- <h3>Order <span class="grey-text">#{{ $order->id }}</span> </h3> --}}
                    <div class="order-detail-status">
                        <span class="mr-2 black-text w-500">Status:</span>
                        @if ($order->dispute_status == 'No' || $order->dispute_status == 'Resolved')
                            @if ($order->status == 'Pending')
                                <span class="info status">{{ $order->status }}</span>
                            @elseif ($order->status == 'Completed')
                                <span class="success status">{{ $order->status }}</span>
                            @elseif ($order->status == 'Picked Up')
                                <span class="warning status">{{ $order->status }}</span>
                            @elseif ($order->status == 'Cancelled')
                                <span class="error status">{{ $order->status }}</span>
                            @endif
                        @elseif($order->dispute_status == 'Yes')
                            <span class="dispute status">Disputed</span>
                        @endif
                    </div>
                </div>
                <div class="order-detail-name">
                    <div class="product-order-detail d-flex align-items-center">
                        <div class="product-picture">
                            <img src="{{ @$order->item->product->thumbnailImage->url }}" alt="tent" />
                        </div>
                        <div class="product-name">
                            <p class="black-text w-500 mb-2">{{ $order->item->product->name }}</p>
                            <ul class="listing-row">
                                {{-- <li class="list-col auto-width mb-2">
                                    <span class="list-item w-500">Reservation Date:</span>
                                    <span>{{ date($global_date_format, strtotime($order->from_date)) }}
                                        {{ $global_date_separator }}
                                        {{ date($global_date_format, strtotime($order->to_date)) }}</span>
                                </li> --}}
                                {{-- <li class="list-col auto-width mb-2">
                                    <span class="list-item w-500">Product Location:</span>
                                    <span>{{ $neighborhoodcity->name ?? '' }}</span>
                                </li> --}}
                                {{-- <li class="list-col auto-width mb-2"> 
                                    <span class="list-item w-500">Exact Location:</span>
                                    <span>{{ $order->location->custom_address ?? '' }}</span>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    {{-- @if (in_array($order->status, ['Pending', 'Picked Up']) && $order->dispute_status == 'No')
                        <div class="btn-dispute-holder">
                            <a href="javascript:void(0);" class="btn btn-dark justify-content-center" data-bs-toggle="modal"
                                data-bs-target="#orderDisputeModal">Raise a dispute</a>
                        </div>
                    @endif --}}
                </div>
                <div class="order-detail-summary d-flex justify-content-between">
                    <div class="detail-summary left-detail">
                        <h6 class="largeFont w-600">Retailer Details</h6>
                        <ul class="listing-row">
                            <li class="list-col">
                                <span class="list-item w-500">Name: </span>
                                <span>{{ $order->item->retailer->name }}</span>
                            </li>
                            {{-- <li class="list-col">
                                <span class="list-item w-500">Phone Number:</span>
                                <span>{{ $order->item->retailer->phone_number }}</span>
                            </li>
                            <li class="list-col">
                                <span class="list-item w-500">Email:</span>
                                <span>{{ $order->item->retailer->email }}</span>
                            </li> --}}
                        </ul>
                        <div><a href="{{ route('orderchat', [$order->id]) }}"
                                class="btn mt-3 btn-dark justify-content-center">Contact Retailer</a></div>
                    </div>
                    <div class="detail-summary right-detail">
                        <h6 class="largeFont w-600">Order Summary</h6>
                        <ul class="listing-row">
                            <li class="summary-item">
                                <span class="list-item w-500">Rental Period: </span>
                                <span>{{ date('m-d-Y', strtotime($order->from_date)) }}
                                    {{ $global_date_separator }}
                                    {{ date('m-d-Y', strtotime($order->to_date)) }} </span>
                            </li>
                            <li class="summary-item">
                                <span class="list-item w-500">Rental Days: </span>
                                <span>{{ $order->item->total_rental_days }} Days </span>
                            </li>
                            <li class="summary-item">
                                <span class="list-item w-500">Rental Amount: </span>
                                <span>${{ number_format((float) ($order->item->rent_per_day * $order->item->total_rental_days), 2, '.', '') }}</span>
                            </li>
                            <li class="summary-item">
                                <span class="list-item w-500"> {{ ucfirst($order->security_option) }}: </span>
                                <span>${{ $order->security_option_amount }}</span>
                            </li>
                            {{-- <li class="summary-item">
                                <span class="list-item w-500">Shipping: </span>
                                <span>Self Pickup</span>
                            </li> --}}

                            {{-- <li class="summary-item">
                                <span class="list-item w-500">Transaction Fee: </span>
                                <span>${{ $order->customer_transaction_fee_amount }}</span>
                            </li> --}}

                            <li class="summary-item price-total">
                                <span class="list-item w-500">Total Earnings: </span>
                                <span>${{ $order->total }}</span>
                            </li>

                        </ul>
                    </div>
                </div>


                <div class="order-detail-photo">
                    <div class="row g-4">
                        @if (!is_null($transaction))
                            <!-- Start of picked up section -->
                            @if ('No' == $order->dispute_status && $order->status == 'Pending' && $order->retailer_confirmed_pickedup == 0)
                                <!-- Customer uploaded picked up images also update the image till retailer confirmed the picked up -->
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form method="post" action="{{ route('orderpickup', [$order->id]) }}"
                                        enctype="multipart/form-data" id="imageForm">
                                        @csrf
                                        <h6 class="largeFont w-600 mb-3">Picked Up Attached Files by You<small
                                                class="smallFont w-400 grey-text"> (Allowed File Extensions:
                                                {{ $global_php_image_extension }})</small></h6>
                                        <div class="product-pic-gallery gallery-upload">
                                            <div class="multi-file-upload">
                                                @for ($i = 1; $i <= $global_max_picked_up_image_count; $i++)
                                                    <input type="file" name="image{{ $i }}"
                                                        class="customerImages @if (isset($order->customerPickedUpImages[$i - 1]->file)) upload-done @else upload-pending @endif {{ $order->customerPickedUpImages->count() + 1 == $i ? '' : 'hidden' }}"
                                                        data-order="{{ $i }}"
                                                        value="{{ @$order->customerPickedUpImages[$i - 1]->file }}"
                                                        accept="{{ str_replace(['[', "'", ']'], ['', '', ''], $global_js_image_extension) }}">
                                                @endfor
                                                <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi">
                                                </span>
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">
                                                        @if ($order->customerPickedUpImages->count() < $global_max_picked_up_image_count)
                                                            Select File to upload...
                                                        @else
                                                            Upload file limit reached
                                                        @endif
                                                    </span>
                                                    <span class="d-block smallFont">(Min upload:
                                                        {{ $global_min_picked_up_image_count }}, Max upload:
                                                        {{ $global_max_picked_up_image_count }}, Max file size:
                                                        {{ $global_php_file_size / 1000 }}MB)</span>
                                                    <span class="uploaded-file-name"></span>
                                                </p>
                                            </div>
                                            <input type="hidden" name="removed_images" id="removedImages">
                                            <input type="hidden" name="uploaded_image" id="uploadedImage">
                                            <input type="hidden" name="uploaded_image_count" id="uploadedImageCount"
                                                value="{{ $order->customerPickedUpImages->count() }}">
                                            @for ($i = 1; $i <= $global_max_picked_up_image_count; $i++)
                                                @if ($errors->has('image.' . $i))
                                                    <label
                                                        class="error-messages">{{ $errors->first('image.' . $i) }}</label>
                                                @endif
                                            @endfor

                                            @error('error')
                                                <label class="error-messages">{{ $message }}</label>
                                            @enderror

                                            <ul class="product-img-preview">
                                                @foreach ($order->customerPickedUpImages as $index => $image)
                                                    <li><span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                            data-id="{{ $image->id }}"><i
                                                                class="fas fa-times"></i></span>
                                                        <div class="product-image-box"><img src="{{ $image->url }}"
                                                                alt="img" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <button
                                                class="btn btn-dark justify-content-center upload-img @if ($order->customerPickedUpImages->count() >= $global_max_picked_up_image_count) d-none @endif"
                                                type="submit"><i class="fa-solid fa-upload"></i> Upload</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- End of upload picked up image -->
                            @elseif($order->customerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Picked Up Attached Files by You</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerPickedUpImages as $customerPickedUpImage)
                                                <div>
                                                    <img src="{{ $customerPickedUpImage->url }}" alt="picked-up-img" />
                                                    <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$customerPickedUpImage->order_id, $customerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <!-- Retailer uploaded picked up images -->
                        {{-- @dd($order); --}}
                        @if ($order->retailerPickedUpImages->isNotEmpty())
                            <div class="col-12 col-sm-12 col-md-6">
                                <h6 class="largeFont w-600 mb-3">Picked Up Attached Files by
                                    {{ config('constants.lender') }}</h6>
                                <div class="product-pic-gallery">
                                    <div class="gallery-box">
                                        @foreach ($order->retailerPickedUpImages as $retailerPickedUpImage)
                                            <div>
                                                <img src="{{ $retailerPickedUpImage->url }}"
                                                    alt="retailer-pickedup-img" />
                                                <a class="download-uploaded-file"
                                                    href="{{ route('downloadattachment', [$retailerPickedUpImage->order_id, $retailerPickedUpImage->id]) }}"
                                                    target="_blank"> <i class="fas fa-download"></i></a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($order->dispute_status == 'No' && $order->status == 'Pending' && $order->customer_confirmed_pickedup == 0)
                                    <a href="{{ route('confirmpickup', [$order->id]) }}"
                                        class="verify-product btn btn-dark mt-3  justify-content-center">Verify Product</a>
                                @endif
                            </div>
                        @endif
                        <!-- End of picked up section -->

                        <!-- Start of returned section -->
                        @if ('No' == $order->dispute_status && $order->status == 'Picked Up' && $order->retailer_confirmed_returned == 0)
                            <!-- Customer uploaded returned images also update the image till retailer confirmed the returned -->
                            <div class="col-12 col-sm-12 col-md-6">
                                <form method="post" action="{{ route('orderreturn', [$order->id]) }}"
                                    enctype="multipart/form-data" id="imageForm">
                                    @csrf
                                    <h6 class="largeFont w-600 mb-3">Returned Attached Files by You<small
                                            class="smallFont w-400 grey-text"> (Allowed File Extensions:
                                            {{ $global_php_image_extension }})</small></h6>
                                    <div class="product-pic-gallery gallery-upload">
                                        <div class="multi-file-upload">
                                            @for ($i = 1; $i <= $global_max_returned_image_count; $i++)
                                                <input type="file" name="image{{ $i }}"
                                                    class="customerImages @if (isset($order->customerReturnedImages[$i - 1]->file)) upload-done @else upload-pending @endif {{ $order->customerReturnedImages->count() + 1 == $i ? '' : 'hidden' }}"
                                                    data-order="{{ $i }}"
                                                    value="{{ @$order->customerReturnedImages[$i - 1]->file }}"
                                                    accept="{{ str_replace(['[', "'", ']'], ['', '', ''], $global_js_image_extension) }}">
                                            @endfor
                                            <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi">
                                            </span>
                                            <p class="medFont m-0">
                                                <span class="limit-reached-text">
                                                    @if ($order->customerReturnedImages->count() < $global_max_returned_image_count)
                                                        Select File to upload...
                                                    @else
                                                        Upload file limit reached
                                                    @endif
                                                </span>
                                                <span class="d-block smallFont">(Min upload:
                                                    {{ $global_min_returned_image_count }}, Max upload:
                                                    {{ $global_max_returned_image_count }}, Max file size:
                                                    {{ $global_php_file_size / 1000 }}MB)</span>
                                                <span class="uploaded-file-name"></span>
                                            </p>
                                        </div>
                                        <input type="hidden" name="removed_images" id="removedImages">
                                        <input type="hidden" name="uploaded_image" id="uploadedImage">
                                        <input type="hidden" name="uploaded_image_count" id="uploadedImageCount"
                                            value="{{ $order->customerReturnedImages->count() }}">
                                        @for ($i = 1; $i <= $global_max_returned_image_count; $i++)
                                            @if ($errors->has('image.' . $i))
                                                <label class="error-messages">{{ $errors->first('image.' . $i) }}</label>
                                            @endif
                                        @endfor

                                        @error('error')
                                            <label class="error-messages">{{ $message }}</label>
                                        @enderror

                                        <ul class="product-img-preview">
                                            @foreach ($order->customerReturnedImages as $index => $image)
                                                <li><span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                        data-id="{{ $image->id }}"><i class="fas fa-times"></i></span>
                                                    <div class="product-image-box"><img src="{{ $image->url }}"
                                                            alt="img" height="100px" width="100px" /></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <button
                                            class="btn btn-dark justify-content-center upload-img @if ($order->customerReturnedImages->count() >= $global_max_returned_image_count) d-none @endif"
                                            type="submit"> <i class="fa-solid fa-upload"></i> Upload</button>
                                    </div>
                                </form>
                            </div>
                            <!-- End of upload returned image -->
                        @elseif ($order->customerReturnedImages->isNotEmpty())
                            <div class="col-12 col-sm-12 col-md-6">
                                <h6 class="largeFont w-600 mb-3">Returned Attached Files by You</h6>
                                <div class="product-pic-gallery">
                                    <div class="gallery-box">
                                        @foreach ($order->customerReturnedImages as $customerReturnedImage)
                                            <div>
                                                <img src="{{ $customerReturnedImage->url }}"
                                                    alt="customer-returned-img" />
                                                <a class="download-uploaded-file"
                                                    href="{{ route('downloadattachment', [$customerReturnedImage->order_id, $customerReturnedImage->id]) }}"
                                                    target="_blank"> <i class="fas fa-download"></i></a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Retailer uploaded picked up images -->
                        @if ($order->retailerReturnedImages->isNotEmpty())
                            <div class="col-12 col-sm-12 col-md-6">
                                <h6 class="largeFont w-600 mb-3">Returned Attached Files by
                                    {{ config('constants.lender') }}</h6>
                                <div class="product-pic-gallery">
                                    <div class="gallery-box">
                                        @foreach ($order->retailerReturnedImages as $retailerReturnedImage)
                                            <div>
                                                <img src="{{ $retailerReturnedImage->url }}"
                                                    alt="retailer-returned-img" />
                                                <a class="download-uploaded-file"
                                                    href="{{ route('downloadattachment', [$retailerReturnedImage->order_id, $retailerReturnedImage->id]) }}"
                                                    target="_blank"> <i class="fas fa-download"></i></a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($order->dispute_status == 'No' && $order->status == 'Picked Up' && $order->customer_confirmed_returned == 0)
                                    <a href="{{ route('confirmreturn', [$order->id]) }}"
                                        class="verify-product btn btn-dark mt-3 justify-content-center">Verify Product</a>
                                @endif
                            </div>
                        @endif

                        <!-- Dispute images -->
                        @if ($order->disputedOrderImages->isNotEmpty())
                            <div class="col-12 col-sm-12 col-md-6">
                                @if ('customer' == $order->disputedOrderImages[0]->uploaded_by)
                                    <h6 class="largeFont w-600 mb-3">Disputed Images by You</h6>
                                @else
                                    <h6 class="largeFont w-600 mb-3">Disputed Images by Retailer</h6>
                                @endif
                                <div class="product-pic-gallery">
                                    <div class="gallery-box">
                                        @foreach ($order->disputedOrderImages as $disputedOrderImage)
                                            <div>
                                                <img src="{{ $disputedOrderImage->url }}" alt="disputed-image" />
                                                <a class="download-uploaded-file"
                                                    href="{{ route('downloadattachment', [$disputedOrderImage->order_id, $disputedOrderImage->id]) }}"
                                                    target="_blank"> <i class="fas fa-download"></i></a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- End of dispute images -->
                    </div>
                </div>

                {{-- <div class="order-billing-info">
                    <h6 class="largeFont w-600 mb-4">Billing information</h6>
                    <ul class="billing-info">
                        <li class="billing-status">
                            <p>Payment Id</p>
                            <span>{{ $order->transaction->payment_id }}</span>
                        </li>
                        <li class="billing-status">
                            <p>Payment Method</p>
                            <span>Stripe</span>
                        </li>
                        <li class="billing-status">
                            <p>Payment Status</p>
                            <span>{{ $order->transaction->status }}</span>
                        </li>
                    </ul>
                </div> --}}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- Raise a dispute modal -->
    <div class="modal fade book-product-modal" id="orderDisputeModal" tabindex="-1" role="dialog"
        aria-labelledby="orderDisputeModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row no-gutters">
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="modal-body-content">
                                <div class="modal-head popup-head">
                                    <h5 class="mb-0">Order Dispute</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                                    </button>
                                </div>
                                @php
                                    $openDisputeModal = 'No';
                                @endphp
                                <form class="filter-form" action="{{ route('orderdispute', [$order->id]) }}"
                                    method="post" id="disputeForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <select name="subject" class="form-control">
                                            <option value="">Select Reason</option>
                                            <option value="product damage">Product damage</option>
                                            <option value="product lost">Product lost</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('subject')
                                            <label class="error-messages">{{ $message }}</label>
                                            @php
                                                $openDisputeModal = 'Yes';
                                            @endphp
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                                        @error('description')
                                            <label class="error-messages">{{ $message }}</label>
                                            @php
                                                $openDisputeModal = 'Yes';
                                            @endphp
                                        @enderror
                                    </div>

                                    <h6 class="largeFont w-600 mb-3">Attached Dispute Files<small
                                            class="smallFont w-400 grey-text"> (Allowed File Extensions:
                                            {{ $global_php_image_extension }})</small></h6>
                                    <div class="product-pic-gallery gallery-upload">
                                        <div class="multi-file-upload">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <input type="file" name="dispute_image{{ $i }}"
                                                    class="disputeImages @if (1 < $i) hidden @endif"
                                                    data-order="{{ $i }}"
                                                    accept="{{ str_replace(['[', "'", ']'], ['', '', ''], $global_js_image_extension) }}">
                                            @endfor
                                            <span><img src="{{ asset('img/upload-multi.svg') }}" alt="upload-multi">
                                            </span>
                                            <p class="medFont m-0">
                                                <span class="dispute-limit-reached-text">Select File to upload...</span>
                                                <span class="d-block smallFont">(Min upload:
                                                    {{ $global_min_dispute_image_count }}, Max upload:
                                                    {{ $global_max_dispute_image_count }}, Max file size:
                                                    {{ $global_php_file_size / 1000 }}MB)</span>
                                                <span class="dispute-uploaded-file-name"></span>
                                            </p>
                                        </div>
                                        <input type="hidden" name="dispute_uploaded_image" id="disputeUploadedImage">
                                        <ul class="dispute-img-preview"></ul>
                                        @for ($i = 1; $i <= $global_max_dispute_image_count; $i++)
                                            @error('dispute_image' . $i)
                                                <label class="error-messages">{{ $message }}</label>
                                                @php
                                                    $openDisputeModal = 'Yes';
                                                @endphp
                                            @enderror
                                        @endfor

                                        @error('error')
                                            <label class="error-messages">{{ $message }}</label>
                                            @php
                                                $openDisputeModal = 'Yes';
                                            @endphp
                                        @enderror
                                    </div>
                                    <input type="hidden" id="openDisputeModal" value="{{ $openDisputeModal }}">
                                    <button type="submit"
                                        class="btn btn-dark justify-content-center med-btn mt-2 upload-img fullwidth">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of dispute modal -->
    <script>
        @if ($order->status == 'Pending')
            const orderMinImageCount = minPickedUpImageCount;
            const orderMaxImageCount = maxPickedUpImageCount;
        @else
            const orderMinImageCount = minReturnedImageCount;
            const orderMaxImageCount = maxReturnedImageCount;
        @endif

        jQuery(document).ready(function() {
            let openDisputeModal = jQuery('#openDisputeModal').val();
            if (openDisputeModal == 'Yes') {
                jQuery('a[data-target="#orderDisputeModal"]').trigger('click');
            }
        });
    </script>
    <script src="{{ asset('js/custom/order-detail.js') }}"></script>
@endpush
