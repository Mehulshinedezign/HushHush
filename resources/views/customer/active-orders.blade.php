<div class="order-his-card-box">
    <div class="row g-3">
        @php $empty = true; @endphp

        @foreach ($orders as $order)
            @if ($order->status == 'Waiting' || $order->status == 'Picked Up')
                @php $empty = false; @endphp

                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('vieworder', ['order' => $order->id]) }}">
                                    <img src={{ $order->product->thumbnailImage->file_path ?? 'n/a' }} alt="profile">
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
                            {{-- <div class="order-card-footer">
                                <a href="#" data-url="{{ route('cancel-order', $order->id) }}"
                                    class="button outline-btn full-btn cancel-order" data-toggle="modal"
                                    data-bs-target="#cancellation-note">Cancel
                                    order</a>
                            </div> --}}

                            <div class="order-card-footer">
                                <a href="#" data-url="{{ route('cancel-order', $order->id) }}"
                                    class="button outline-btn full-btn cancel-order">
                                    Cancel order
                                </a>
                            </div>
                        @else
                            <div class="order-card-footer">
                                <div class="btn-dispute-holder">
                                    <a href="javascript:void(0);" data-url="{{ route('orderdispute', [$order->id]) }}"
                                        class="button btn-dark justify-content-center dispute-order full-btn"
                                        data-bs-toggle="modal" data-bs-target="#orderDisputeModal">Raise a dispute</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
        @if ($empty)
            <div class="list-empty-box">
                <img src="{{ asset('front/images/Empty 1.svg') }}" alt="No orders available">
                <h3 class="text-center">No orders Available</h3>
            </div>
        @endif

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
                    <button type="submit" class="button primary-btn full-btn mt-3  submit">Submit&nbsp;<i
                            class="fa-solid fa-circle-notch fa-spin show-loader" style="display:none;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@push('scripts')
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
                                                    <strong>{{ $message }}</strong>
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


    <script>
        $(document).ready(function() {
            $('.cancel-order').on('click', function(event) {
                event.preventDefault();

                let cancelUrl = $(this).data('url');

                swal({
                        title: 'Cancel Order',
                        text: 'The platform charges will be deducted by stripe',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                        buttons: ["No", "Yes"],
                    })
                    .then((willCancel) => {
                        if (willCancel) {
                            $('#cancellation-note').modal('show');

                            $.ajax({
                                url: cancelUrl,
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    reason: 'Your cancellation reason here'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        swal("Order Cancelled", response.message, "success")
                                            .then(() => {
                                                location
                                            .reload();
                                            });
                                    } else {
                                        swal("Error", response.message, "error");
                                    }
                                },
                                error: function() {
                                    swal("Error",
                                        "An error occurred while cancelling the order.",
                                        "error");
                                }
                            });
                        } else {
                            jQuery('body').removeClass('modal-open');
                        }
                    });
            });
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
