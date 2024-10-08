@extends('layouts.front')
@section('content')
    <section class="od-table-section min-height-100">
        <div class="container">
            <div class="od-table-wrapper">
                <div class="back-block mb-3  back-order-status ">
                    <a href="{{ route('orders') }}"><i class="fa-solid fa-angle-left"></i> Back</a>

                    @if ($order->status == 'Cancelled')
                        <p class="cancelled-txt">Cancelled</p>
                    @endif

                    @if ($order->status == 'Picked Up' && $order->dispute_status == 'Yes')
                        <p class="cancelled-txt">{{ $order->disputeDetails->status=='resolved' ? 'Resolved' : 'Dispute'}}</p>
                    @endif

                </div>   
                <div class="order-detail-box">
                    <div class="order-detail-summary d-flex justify-content-between">
                        <div class="detail-summary left-detail">
                            <h6 class="order-detail-heading">Lender Details</h6>
                            <div class="od-profile-bx mb-3">
                                <div class="product-picture">
                                    <img src="{{ $order->product->thumbnailImage->file_path }}" alt="tent">
                                </div>
                                <div class="order-detail-name">
                                    <span class=""><b>Name:</b> {{ @$order->retailer->name }}</span>
                                </div>
                            </div>
                            @if (@$order->status == 'Cancelled')
                                <div>
                                    <span><b>Cancellation Note: </b>{{ $order->cancellation_note }}</span>
                                </div>
                            @endif
                            @if (@$order->status == 'Picked Up' && @$order->dispute_status == 'Yes')
                                <div>
                                    <span><b>Subject : </b>{{ $order->disputeDetails->subject }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="detail-summary right-detail">
                            <h6 class="order-detail-heading">Order Summary</h6>
                            <ul class="listing-row">
                                <li class="summary-item">
                                    <span class="summery-name">Rental Period: </span>
                                    <span>{{ @$order->from_date }}
                                        -
                                        {{ @$order->to_date }} </span>
                                </li>
                                {{-- <li class="summary-item">
                                    <span class="summery-name">Service Fee: </span>
                                    <span>$15 </span>
                                </li> --}}

                                <li class="summary-item">
                                    <span class="summery-name">Rental Days: </span>
                                    <span>{{ diffBetweenToDate(@$order->from_date, @$order->to_date) == 1 ? 1 : diffBetweenToDate(@$order->from_date, @$order->to_date) +1  }} </span>
                                </li>
                                {{-- <li class="summary-item">
                                    <span class="summery-name">Rental Amount: </span>
                                    <span>$90.00</span>
                                </li> --}}
                                <li class="summary-item">
                                    <span class="summery-name"> Order Id: </span>
                                    <span>{{ $order->id ?? 'N/A' }}</span>
                                </li>

                                <li class="summary-item price-total">
                                    <span class="summery-name">Total: </span>
                                    <span>${{ $order->total }}</span>
                                </li>

                            </ul>
                        </div>
                    </div>


                    <div class = "order-detail-photo">
                        <div class="row g-4">
                            @if ('No' == $order->dispute_status && $order->status == 'Waiting' && $order->retailer_confirmed_pickedup == 0)
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form id="orderDetail" method="post" action="{{ route('orderpickup', [$order->id]) }}"
                                        enctype="multipart/form-data" id="imageForm">
                                        @csrf
                                        <h6 class="order-detail-heading">Picked Up (Attached Files by You)</h6>
                                        <div class="product-pic-gallery">

                                            <div class="multi-file-upload">
                                                <input type="file" name="images[]" multiple
                                                    class="customerImages  upload-pending @error('images') is-invalid @enderror"
                                                    id="upload-image" upload-image-count="0" data-order="1" value=""
                                                    accept="image/jpeg, image/png, image/jpg">
                                                @error('images')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                {{-- <span><img src="http://192.168.0.59:8000/img/upload-multi.svg"
                                                    alt="upload-multi">
                                            </span> --}}
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">
                                                        Select File to upload...
                                                    </span>
                                                    <span class="smallFont">(Min upload:
                                                        1, Max upload:
                                                        5, Max file size:
                                                        5MB)</span>

                                                </p>

                                            </div>

                                            <div class="upload-img-preview mb-3">
                                                @foreach ($order->customerPickedUpImages as $index => $image)
                                                    <li>
                                                        {{-- <span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                            data-id="{{ $image->id }}"><i
                                                                class="fas fa-times"></i></span> --}}
                                                        <div class="product-image-box"><img src="{{ $image->url }}"
                                                                alt="img" height="100px" width="100px" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>

                                            <button class="btn btn-dark upload-image" type="submit"><i class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                            @elseif($order->customerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Picked Up (Attached Files by You)</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerPickedUpImages as $customerPickedUpImage)
                                                <div>
                                                    <img src="{{ $customerPickedUpImage->url }}" alt="picked-up-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$customerPickedUpImage->order_id, $customerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if ($order->retailerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Picked Up (Attached Files by
                                        {{ config('constants.lender') }})</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->retailerPickedUpImages as $retailerPickedUpImage)
                                                <div>
                                                    <img src="{{ $retailerPickedUpImage->url }}"
                                                        alt="retailer-pickedup-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$retailerPickedUpImage->order_id, $retailerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($order->dispute_status == 'No' && $order->status == 'Waiting' && $order->customer_confirmed_pickedup == 0)
                                        <a href="{{ route('confirmpickup', [$order->id]) }}"
                                            class="verify-product btn btn-dark mt-3  justify-content-center">Verify
                                            Product</a>
                                    @endif
                                </div>
                            @endif

                            {{-- return --}}
                            @if ('No' == $order->dispute_status && $order->status == 'Picked Up' && $order->retailer_confirmed_returned == 0)
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form id="orderReturn" method="post" action="{{ route('orderreturn', [$order->id]) }}"
                                        enctype="multipart/form-data" id="imageForm">
                                        @csrf
                                        <h6 class="order-detail-heading">Picked Up (Attached Files by You)</h6>
                                        <div class="product-pic-gallery">

                                            <div class="multi-file-upload">
                                                <input type="file" name="images[]" multiple
                                                    class="customerImages  upload-pending @error('images') is-invalid @enderror"
                                                    id="upload-image" upload-image-count="0" data-order="1" value=""
                                                    accept="image/jpeg, image/png, image/jpg">

                                                @error('images')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">
                                                        Select File to upload...
                                                    </span>
                                                    <span class="smallFont">(Min upload:
                                                        1, Max upload:
                                                        5, Max file size:
                                                        5MB)</span>

                                                </p>
                                            </div>
                                            <div class="upload-img-preview mb-3">
                                                @foreach ($order->customerReturnedImages as $index => $image)
                                                    <li>
                                                        {{-- <span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                            data-id="{{ $image->id }}"><i
                                                                class="fas fa-times"></i></span> --}}
                                                        <div class="product-image-box"><img src="{{ $image->url }}"
                                                                alt="img" height="100px" width="100px" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>

                                            <button class="btn btn-dark upload-image" type="submit"><i
                                                    class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                            @elseif ($order->customerReturnedImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Returned (Attached Files by You)</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerReturnedImages as $customerReturnedImage)
                                                <div>
                                                    <img src="{{ $customerReturnedImage->url }}"
                                                        alt="customer-returned-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$customerReturnedImage->order_id, $customerReturnedImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Retailer uploaded picked up images -->
                            @if ($order->retailerReturnedImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Returned (Attached Files by
                                        {{ config('constants.lender') }})</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->retailerReturnedImages as $retailerReturnedImage)
                                                <div>
                                                    <img src="{{ $retailerReturnedImage->url }}"
                                                        alt="retailer-returned-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$retailerReturnedImage->order_id, $retailerReturnedImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($order->dispute_status == 'No' && $order->status == 'Picked Up' && $order->customer_confirmed_returned == 0)
                                        <a href="{{ route('confirmreturn', [$order->id]) }}"
                                            class="verify-product btn btn-dark mt-3 justify-content-center">Verify
                                            Product</a>
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
                                            {{-- <a class="download-uploaded-file"
                                                href="{{ route('downloadattachment', [$disputedOrderImage->order_id, $disputedOrderImage->id]) }}"
                                                target="_blank"> <i class="fas fa-download"></i></a> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- End of dispute images -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // const MAX_IMAGES = 5;
            let selectedFiles = [];

            function previewImages(input, imgPreviewPlaceholder) {
                const files = Array.from(input.files);
                const currentCount = selectedFiles.length;

                // if (currentCount + files.length > MAX_IMAGES) {
                //     alert(`You can upload up to ${MAX_IMAGES} images.`);
                //     return;
                // }

                files.forEach((file) => {
                    selectedFiles.push(file);
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const element = `
            <div class="upload-img-box">
                <img src="${event.target.result}" alt="img">
                <div class="upload-img-cross">
                    <i class="fa-regular fa-circle-xmark remove_uploaded"></i>
                </div>
            </div>`;
                        $(imgPreviewPlaceholder).append(element);
                    };
                    reader.readAsDataURL(file);
                });

                updateFileInput();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach((file) => dataTransfer.items.add(file));
                $('#upload-image')[0].files = dataTransfer.files;
            }

            function updateImageCount(change) {
                const $uploadImage = $('#upload-image');
                let currentCount = parseInt($uploadImage.attr('upload-image-count') || 0);
                currentCount += change;
                $uploadImage.attr('upload-image-count', currentCount);
            }

            $('#upload-image').on('change', function() {
                previewImages(this, 'div.upload-img-preview');
            });

            $(document).on('click', '.remove_uploaded', function() {
                const index = $(this).closest('.upload-img-box').index();
                selectedFiles.splice(index, 1);
                $(this).closest('.upload-img-box').remove();
                updateFileInput();
                updateImageCount(-1);
            });
        });

       
    </script>
    <script>
       $(document).ready(function() {
    // Custom validation methods
    $.validator.addMethod("minfiles", function(value, element, param) {
        const fileCount = $(element).get(0).files.length;
        return fileCount >= param;
    }, "Please select at least {0} files.");

    $.validator.addMethod("maxfiles", function(value, element, param) {
        const fileCount = $(element).get(0).files.length;
        return fileCount <= param;
    }, "Please select no more than {0} files.");

    const rules = {
        'images[]': {
            required: true,
            accept: "image/*",
            minfiles: 1,  // Minimum number of files required
            maxfiles: 5,  // Maximum number of files allowed
        },
    };
    const messages = {
        'images[]': {
            required: "Please upload at least one image",
            accept: "Please upload only image files",
            minfiles: "You can upload a minimum of 1 image",
            maxfiles: "You can upload a maximum of 5 images",
        },
    };

    handleValidation('orderDetail', rules, messages);
    handleValidation('orderReturn', rules, messages);

    
    $('#orderDetail').submit(function(e) {
        e.preventDefault(); 

        const submitButton = $(this).find('button[type="submit"]');
       
        if ($('#orderDetail').valid()) {
            submitButton.prop('disabled', true); // Disable the button      
            this.submit(); 
        }
    });

    
    $('#orderReturn').submit(function(e) {
        e.preventDefault(); 

        const submitButton = $(this).find('button[type="submit"]');
       
        if ($('#orderReturn').valid()) {
            submitButton.prop('disabled', true); // Disable the button   
            this.submit();
        }
    });
});

    </script>
@endpush
