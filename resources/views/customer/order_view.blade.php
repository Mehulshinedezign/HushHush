@extends('layouts.front')
@section('content')
    <section class="od-table-section">
        <div class="container">
            <div class="od-table-wrapper">
                <div class="order-detail-box">
                    {{-- @dd($order->toArray()) --}}

                    <div class="order-detail-summary d-flex justify-content-between">
                        <div class="detail-summary left-detail">
                            <h6 class="order-detail-heading">Lender Details</h6>
                            <div class="od-profile-bx">
                                <div class="product-picture">
                                    <img src="{{ $order->product->thumbnailImage->file_path }}" alt="tent">
                                </div>
                                <div class="order-detail-name">
                                    <span class=""><b>Name:</b> {{ @$order->retailer->name }}</span>
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
                            @if ('No' == $order->dispute_status && $order->status == 'Waiting' && $order->retailer_confirmed_pickedup == 0)
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form method="post" action="{{ route('orderpickup', [$order->id]) }}"
                                        enctype="multipart/form-data" id="imageForm">
                                        @csrf
                                        <h6 class="order-detail-heading">Picked Up Attached Files by You</h6>
                                        <div class="product-pic-gallery">

                                            <div class="multi-file-upload">
                                                <input type="file" name="images[]" multiple
                                                    class="customerImages  upload-pending" id="upload-image"
                                                    upload-image-count="0" data-order="1" value=""
                                                    accept="image/jpeg, image/png, image/jpg">

                                                {{-- <span><img src="http://192.168.0.59:8000/img/upload-multi.svg"
                                                    alt="upload-multi">
                                            </span> --}}
                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">
                                                        Select File to upload...
                                                    </span>
                                                    <span class="smallFont">(Min upload:
                                                        2, Max upload:
                                                        5, Max file size:
                                                        5MB)</span>

                                                </p>
                                            </div>
                                            <div class="upload-img-preview">

                                            </div>

                                            <button class="btn btn-dark " type="submit"><i class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                            @elseif($order->customerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Picked Up Attached Files by You</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerPickedUpImages as $customerPickedUpImage)
                                                <div>
                                                    <img src="{{ Storage::url($customerPickedUpImage->url) }}"
                                                        alt="picked-up-img" />
                                                    <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$customerPickedUpImage->order_id, $customerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if ($order->retailerPickedUpImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Picked Up Attached Files by
                                        {{ config('constants.lender') }}</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->retailerPickedUpImages as $retailerPickedUpImage)
                                                <div>
                                                    <img src="{{ Storage::url($retailerPickedUpImage->url) }}"
                                                        alt="retailer-pickedup-img" />
                                                    <a class="download-uploaded-file"
                                                        href="{{ route('downloadattachment', [$retailerPickedUpImage->order_id, $retailerPickedUpImage->id]) }}"
                                                        target="_blank"> <i class="fas fa-download"></i></a>
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
                            {{-- <div class="col-12 col-sm-12 col-md-6">
                                <form>

                                    <h6 class="order-detail-heading">Picked Up Attached Files by You</h6>
                                    <div class="product-pic-gallery">


                                        <div class="multi-file-upload">
                                            <input type="file" name="image1" class="customerImages  upload-pending  "
                                                data-order="1" value="" accept="image/jpeg, image/png, image/jpg">

                                            <span><img src="http://192.168.0.59:8000/img/upload-multi.svg"
                                                    alt="upload-multi">
                                            </span>
                                            <p class="medFont m-0">
                                                <span class="limit-reached-text">
                                                    Select File to upload...
                                                </span>
                                                <span class="smallFont">(Min upload:
                                                    2, Max upload:
                                                    5, Max file size:
                                                    5MB)</span>

                                            </p>
                                        </div>


                                        <button class="btn btn-dark " type="submit"><i class="fa-solid fa-upload"></i>
                                            Upload</button>
                                    </div>
                                </form>
                            </div> --}}

                            @if ('No' == $order->dispute_status && $order->status == 'Picked Up' && $order->retailer_confirmed_returned == 0)
                                <div class="col-12 col-sm-12 col-md-6">
                                    <form method="post" action="{{ route('orderreturn', [$order->id]) }}"
                                        enctype="multipart/form-data" id="imageForm">
                                        @csrf
                                        <h6 class="order-detail-heading">Picked Up Attached Files by You</h6>
                                        <div class="product-pic-gallery">

                                            <div class="multi-file-upload">
                                                <input type="file" name="images[]" multiple
                                                    class="customerImages  upload-pending" id="upload-image"
                                                    upload-image-count="0" data-order="1" value=""
                                                    accept="image/jpeg, image/png, image/jpg">


                                                <p class="medFont m-0">
                                                    <span class="limit-reached-text">
                                                        Select File to upload...
                                                    </span>
                                                    <span class="smallFont">(Min upload:
                                                        2, Max upload:
                                                        5, Max file size:
                                                        5MB)</span>

                                                </p>
                                            </div>
                                            <div class="upload-img-preview">

                                            </div>

                                            <button class="btn btn-dark " type="submit"><i class="fa-solid fa-upload"></i>
                                                Upload</button>
                                        </div>
                                    </form>
                                </div>
                            @elseif ($order->customerReturnedImages->isNotEmpty())
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6 class="largeFont w-600 mb-3">Returned Attached Files by You</h6>
                                    <div class="product-pic-gallery">
                                        <div class="gallery-box">
                                            @foreach ($order->customerReturnedImages as $customerReturnedImage)
                                                <div>
                                                    <img src="{{ Storage::url($customerReturnedImage->url) }}"
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



                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- function readURL(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
    // Here I need to use $(this) to target only the second list item's img.preview
    $('.preview').attr('src', e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
    }
    } --}}
    <script>
        $(function() {
            // Multiple images preview with JavaScript
            var previewImages = function(input, imgPreviewPlaceholder) {
                if (input.files) {
                    var filesAmount = input.files.length;
                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var element =
                                '<div class="upload-img-box"><img src="' + event.target.result +
                                '" alt="img"> <div class = "upload-img-cross" > <i class = "fa-regular fa-circle-xmark remove_uploaded"></i></div></div>';
                            // console.log(element);
                            jQuery(element).appendTo(imgPreviewPlaceholder);
                        }
                        reader.readAsDataURL(input.files[i]);
                    }

                    var noimage = $('#upload-image').attr('upload-image-count');

                    totalimg = parseInt(noimage) + filesAmount;
                    console.log(noimage, totalimg, filesAmount, 'hererre');
                    $('#upload-image').attr('upload-image-count', totalimg)
                }
            };
            $('#upload-image').on('change', function() {
                previewImages(this, 'div.upload-img-preview');
            });

            $(document).on('click', '.remove_uploaded', function() {
                totalimage = $('#upload-image').attr('upload-image-count');
                remaningimg = parseInt(totalimage) - 1;
                totalimage = $('#upload-image').attr('upload-image-count', remaningimg);

                $(this).parent('div').parent('div').remove();
            });

        });
    </script>
@endpush
