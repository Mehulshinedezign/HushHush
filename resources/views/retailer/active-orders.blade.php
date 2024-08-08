{{-- <div class="order-management">
    <div class="table_left">
        <div class="wrapper_table">
            <table class="rwd-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price per Day</th>
                        <th>Total</th>
                        <th>Rental Period</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $index => $orderItem)
                        @if ($orderItem->order->status == 'Pending' || $orderItem->order->status == 'Picked Up')
                            @include('retailer.order-row')
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (check_order_list_paginate_retailer('Pending') > 10)
            {{ $orderItems->links('pagination::product-list') }}
        @endif
    </div>
</div> --}}


<div class="order-his-card-box">
    <div class="row g-3">
        @foreach ($orders as $order)
            @if ($order->status == 'Waiting' || $order->status == 'Picked Up')
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('retailervieworder', ['order' => $order->id]) }}">
                                    <img src="{{ $order->product->thumbnailImage->file_path }}" alt="profile">
                                </a>
                            </div>
                            <p>{{ $order->product->name }}</p>
                            <div class="pro-desc-prize">
                                <h3>${{ $order->total }}</h3>

                            </div>
                            <div class="order-pro-details">
                                <div class="order-details-list">
                                    <p>Category :</p>
                                    <h4>{{ $order->product->category->name }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Size:</p>
                                    <h4>{{ $order->product->size }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Rental date:</p>
                                    <h4>{{ $order->from_date }} to {{ $order->to_date }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Lender:</p>
                                    <h4>{{ $order->product->retailer->name }}</h4>
                                </div>
                                <div class="order-details-list">
                                    <p>Payment:</p>
                                    <h4>Paid</h4>
                                </div>
                            </div>
                        </div>

                        @if ($order->status == 'Waiting')
                            <div class="order-card-footer">
                                <a href="#" data-url="{{ route('retailer-cancel-order', $order->id) }}"
                                    class="button outline-btn full-btn cancel-order" data-toggle="modal"
                                    data-bs-target="#cancellation-note">Cancel
                                    order</a>
                            </div>
                        @else
                            <div class="order-card-footer">
                                <div class="btn-dispute-holder">
                                    <a href="javascript:void(0);"
                                        data-url="{{ route('retailer.orderdispute', [$order->id]) }}"
                                        class="btn btn-dark justify-content-center dispute-order full-btn"
                                        data-bs-toggle="modal" data-bs-target="#orderDisputeModal">Raise a dispute</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>

<div class="modal fade cencel-order-modal" id="cancellation-note" data-bs-backdrop="static" tabindex="-1"
    aria-labelledby="cancellation-noteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="ajax-response"></div>
                <form method="post" id="cancel-order">
                    @csrf
                    <div class="cancellation-popup-sec">
                        <div class="popup-head">
                            <h6>Cancellation Note</h6>
                            <button type="" class="close" data-bs-dismiss="modal"><i
                                    class="fa-solid fa-xmark"></i></button>
                        </div>
                        <textarea class="form-control mt-3" name="cancellation_note" rows="5"
                            placeholder="Please write cancellation note here"></textarea>
                        <button type="submit" class="button primary-btn full-btn mt-3  submit"
                            id="cancel-order">Submit&nbsp;<i class="fa-solid fa-circle-notch fa-spin show-loader"
                                style="display:none;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    {{-- dispute order --}}
    <div class="modal fade book-product-modal" id="orderDisputeModal" data-bs-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="orderDisputeModalTitle" aria-hidden="true">
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
                                <form class="filter-form" method="post" id="disputeForm" enctype="multipart/form-data">
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

                                    <div class="product-pic-gallery">
                                        <div class="multi-file-upload">
                                            <label for="">upload images</label>
                                            <input type="file" name="images[]" multiple
                                                class="customerImages  upload-pending @error('images') is-invalid @enderror"
                                                id="upload-image" upload-image-count="0" data-order="1" value=""
                                                accept="image/jpeg, image/png, image/jpg">
                                            @error('images')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror

                                        </div>
                                        <div class="upload-img-preview">

                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="btn btn-dark justify-content-center med-btn mt-2 upload-img full-btn">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                var element = $('#orderDisputeModal').modal('show');
            });
        </script>
    @endif
    {{-- end dispute order --}}
    <script>
        $(document).ready(function() {

            $('.cancel-order').on('click', function() {
                // $('.modal fade').addClass('d-none');

                swal({
                        title: 'Cancel Order',
                        text: 'The platform charges will be deducted by stripe',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                        buttons: ["No", "Yes"],
                    })
                    .then((willOpen) => {
                        if (willOpen) {
                            // $('.modal fade').removeClass('d-none');
                            $('#cancellation-note').modal('show');
                        } else {
                            jQuery('body').removeClass('modal-open');
                        }
                    });
            })
        });
    </script>

    <script>
        $('.dispute-order').on('click', function() {
            var url = $(this).attr('data-url');
            $('#disputeForm').attr('action', url);
        });

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
@endpush
