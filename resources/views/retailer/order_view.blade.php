@extends('layouts.front')
@section('content')
    <section class="od-table-section">
        <div class="container">
            <div class="od-table-wrapper">
                <div class="back-block mb-2">
                    <a href="{{ route('retailercustomer') }}"><i class="fa-solid fa-angle-left"></i> Back</a>
                </div>
                <div class="order-detail-box">
                    {{-- @dd($order->toArray()) --}}

                    <div class="order-detail-summary d-flex justify-content-between">
                        <div class="detail-summary left-detail">
                            <h6 class="order-detail-heading">Customer Details</h6>
                            <div class="od-profile-bx">
                                <div class="product-picture">
                                    <img src="{{ $order->product->thumbnailImage->file_path }}" alt="tent">
                                </div>
                                <div class="order-detail-name">
                                    <span class=""><b>Name:</b> {{ @$order->user->name }}</span>
                                </div>
                            </div>



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
                                    <span>{{ diffBetweenToDate(@$order->from_date, @$order->to_date) }} </span>
                                </li>
                                {{-- <li class="summary-item">
                                    <span class="summery-name">Rental Amount: </span>
                                    <span>$90.00</span>
                                </li> --}}
                                {{-- <li class="summary-item">
                                    <span class="summery-name"> Security: </span>
                                    <span>$272.40</span>
                                </li> --}}

                                <li class="summary-item price-total">
                                    <span class="summery-name">Total: </span>
                                    <span>${{ $order->total }}</span>
                                </li>

                            </ul>
                        </div>
                    </div>


                    <div class="order-detail-photo">
                        <div class="row g-4">
                            @if ('No' == $order->dispute_status && $order->status == 'Waiting' && $order->customer_confirmed_pickedup == 0)
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form id="orderDetail" method="post"
                                        action="{{ route('retailerorderpickup', [$order->id]) }}"
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
                                            <div class="upload-img-preview">
                                                @foreach ($order->retailerPickedUpImages as $index => $image)
                                                    <li>
                                                        {{-- <span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                            data-id="{{ $image->id }}"><i
                                                                class="fas fa-times"></i></span> --}}
                                                        <div class="product-image-box "><img
                                                                src="{{ Storage::url($image->url) }}" alt="img"
                                                                height="100px" width="100px" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>

                                            <button class="btn btn-dark " type="submit"><i class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                            @elseif ($order->retailerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6  mb-4">
                                    <h6 class="largeFont w-600 mb-3">Picked Up (Attached Files by You)</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->retailerPickedUpImages as $retailerPickedUpImage)
                                                <div>
                                                    <img src="{{ Storage::url($retailerPickedUpImage->url) }}"
                                                        alt="retailer-pickedup-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('retailer.downloadattachment', [$retailerPickedUpImage->order_id, $retailerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if ($order->customerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6  mb-4">
                                    <h6 class="largeFont w-600 mb-3">Picked Up (Attached Files by
                                        {{ config('constants.renter') }})</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerPickedUpImages as $customerPickedUpImage)
                                                <div>
                                                    <img src="{{ Storage::url($customerPickedUpImage->url) }}"
                                                        alt="picked-up-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('retailer.downloadattachment', [$customerPickedUpImage->order_id, $customerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($order->dispute_status == 'No' && $order->status == 'Waiting' && $order->retailer_confirmed_pickedup == 0)
                                        <a href="{{ route('retailer.confirmpickup', [$order->id]) }}"
                                            class="verify-product btn justify-content-center btn-dark mt-3">Verify
                                            Product</a>
                                    @endif
                                </div>
                            @endif


                            {{-- return order  --}}

                            @if ('No' == $order->dispute_status && $order->status == 'Picked Up' && $order->customer_confirmed_returned == 0)
                                <!-- Retailer uploaded returned images also update the image till customer confirmed the returned -->
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form id="orderReturn" method="post"
                                        action="{{ route('retailerorderreturn', [$order->id]) }}"
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
                                            <div class="upload-img-preview">
                                                @foreach ($order->retailerReturnedImages as $index => $image)
                                                    <li>
                                                        {{-- <span class="remove-preview-img" data-index="{{ $index + 1 }}"
                                                            data-id="{{ $image->id }}"><i
                                                                class="fas fa-times"></i></span> --}}
                                                        <div class="product-image-box"><img
                                                                src="{{ Storage::url($image->url) }}" alt="img"
                                                                height="100px" width="100px" />
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>

                                            <button class="btn btn-dark " type="submit"><i
                                                    class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- End of upload returned image -->
                            @elseif ($order->retailerReturnedImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Returned (Attached Files by You)</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->retailerReturnedImages as $retailerReturnedImage)
                                                <div>
                                                    <img src="{{ Storage::url($retailerReturnedImage->url) }}"
                                                        alt="retailer-returned-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('retailer.downloadattachment', [$retailerReturnedImage->order_id, $retailerReturnedImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif




                            @if ($order->customerReturnedImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Returned (Attached Files by
                                        {{ config('constants.renter') }})</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerReturnedImages as $customerReturnedImage)
                                                <div>
                                                    <img src="{{ Storage::url($customerReturnedImage->url) }}"
                                                        alt="customer-returned-img" />
                                                    {{-- <a class="download-uploaded-file"
                                                        href="{{ route('retailer.downloadattachment', [$customerReturnedImage->order_id, $customerReturnedImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a> --}}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($order->dispute_status == 'No' && $order->status == 'Picked Up' && $order->retailer_confirmed_returned == 0)
                                        <a href="{{ route('retailerconfirmreturn', [$order->id]) }}"
                                            class="verify-product btn btn-dark mt-3 justify-content-center">Verify
                                            Product</a>
                                    @endif
                                </div>
                            @endif
                            {{-- end --}}

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
        // $(function() {
        //     // Multiple images preview with JavaScript
        //     var previewImages = function(input, imgPreviewPlaceholder) {
        //         if (input.files) {
        //             var filesAmount = input.files.length;
        //             for (i = 0; i < filesAmount; i++) {
        //                 var reader = new FileReader();
        //                 reader.onload = function(event) {
        //                     var element =
        //                         '<div class="upload-img-box"><img src="' + event.target.result +
        //                         '" alt="img"> <div class = "upload-img-cross" > <i class = "fa-regular fa-circle-xmark remove_uploaded"></i></div></div>';
        //                     // console.log(element);
        //                     jQuery(element).appendTo(imgPreviewPlaceholder);
        //                 }
        //                 reader.readAsDataURL(input.files[i]);
        //             }

        //             var noimage = $('#upload-image').attr('upload-image-count');

        //             totalimg = parseInt(noimage) + filesAmount;
        //             console.log(noimage, totalimg, filesAmount, 'hererre');
        //             $('#upload-image').attr('upload-image-count', totalimg)
        //         }
        //     };
        //     $('#upload-image').on('click', function() {
        //         previewImages(this, 'div.upload-img-preview');
        //     });

        //     $(document).on('click', '.remove_uploaded', function() {
        //         totalimage = $('#upload-image').attr('upload-image-count');
        //         remaningimg = parseInt(totalimage) - 1;
        //         totalimage = $('#upload-image').attr('upload-image-count', remaningimg);

        //         $(this).parent('div').parent('div').remove();

        //     });

        // });
    </script>
    <script>
        $(document).ready(function() {
            const rules = {
                'images[]': {
                    required: true,
                    accept: "image/*",
                    minfiles: 1,
                    maxfiles: 5,
                },
            };
            const messages = {
                'images[]': {
                    required: "Please upload at least one image",
                    accept: "Please upload only image files",
                    minfiles: "You can upload a maximum of 5 images",
                    maxfiles: "You can upload a maximum of 5 images",
                },
            };

            handleValidation('orderDetail', rules, messages);

            $('#orderDetail').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                // Perform form validation

                // Check if the form is valid
                if ($('#orderDetail').valid()) {
                    $('#orderDetail').submit(); // Submit form
                }
            });

            handleValidation('orderReturn', rules, messages);
            $('#orderReturn').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                // Perform form validation

                // Check if the form is valid
                if ($('#orderReturn').valid()) {
                    $('#orderReturn').submit(); // Submit form
                }
            });

        })
    </script>
@endpush
