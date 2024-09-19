{{-- @if (count($orders) > 0)
    @foreach ($orders as $order)
        @if ($order->status == 'Completed')
            @include('customer.order-row')
        @endif
    @endforeach
@else
    <div class="pro-tab-contant">
        <div class="pro-tab-contant-row">
            <h3></h3>
        </div>
    </div>
@endif
@if (check_order_list_paginate('Completed') > 10)
    <div class="custom-pagination">
        {{ $orders->links('pagination::product-list') }}
    </div>
@endif --}}
<div class="order-his-card-box">
    <div class="row g-3">
        @php $empty = true; @endphp
        @foreach ($orders as $order)
            @if ($order->status == 'Completed')
                @php $empty = false; @endphp
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('vieworder', ['order' => $order->id]) }}">
                                    <img src="{{ @$order->product->thumbnailImage->file_path ?? 'N/a' }}" alt="profile">
                                </a>
                            </div>
                            <p>{{ $order->product->name }}</p>
                            <div class="pro-desc-prize">
                                <h3>${{ $order->total }}</h3>
                                {{-- <div class="badge day-badge">
                                    Per day
                                </div> --}}

                            </div>
                            <div class="order-pro-details">
                                <div class="order-details-list">
                                    <p>Order Id :</p>
                                    <h4>{{ $order->id }}</h4>
                                </div>
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

                        <div class="order-card-footer">
                            <a href="javascript:void(0)" data-orderId="{{ $order->id }}"
                                data-productId="{{ @$order->product->id }}"
                                class="button outline-btn full-btn productReview" data-bs-toggle="modal"
                                data-bs-target="#rating_review">Write
                                Review </a>
                        </div>
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
    {{-- <div class="pagination-main">
        <a href="javascript:void(0)" class="pagination-box">
            01
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            02
        </a>
        <a href="javascript:void(0)" class="pagination-box active">
            03
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            04
        </a>
        <a href="javascript:void(0)" class="pagination-box">
            05
        </a>
    </div> --}}
</div>

<div class="modal fade rate_review" id="rating_review" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="cancellation-popup-sec">
                    <div class="popup-head rating_pop_head">
                        <h6>Write Review</h6>
                        <button type="button" class="btn-close query_btn_close close" data-bs-dismiss="modal"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="pro-tab-contant-row" id="reviewProduct"></div>
                    <form id="product_review">
                        @csrf
                        <input type="hidden" name="product_id" id="rating_product_id" value="">
                        <input type="hidden" name="order_id" id="rating_order_id" value="">
                        <div class="feedback-review">
                            <h4 class="mb-2">Add Rating</h4>
                            <div class="rating mb-3">
                                <div class="stars">
                                    <input type="radio" name="rating" value="5" id="five" class="star">
                                    <label for="five"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="4" id="four" class="star">
                                    <label for="four"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="3" id="three" class="star">
                                    <label for="three"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="2" id="two" class="star">
                                    <label for="two"><i class="fa fa-star"></i></label>
                                    <input type="radio" name="rating" value="1" id="one" class="star">
                                    <label for="one"><i class="fa fa-star"></i></label>
                                </div>
                            </div>
                        </div>
                        <textarea class="form-control mb-3" name="review" rows="3" placeholder="Please write product review here...."></textarea>
                        <p class="popup-p">By submitting review you give us consent to publish and process personal
                            information in accordance with Term of use and Privacy Policy</p>
                        <button type="submit" class="button full-btn primary-btn submit mt-3"
                            id="rating_btn">Submit&nbsp;<i class="fa-solid fa-circle-notch fa-spin show-loader"
                                style="display:none;"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Show modal and populate data
        $('.productReview').on('click', function() {
            var product_id = $(this).attr('data-productId');
            var order_id = $(this).attr('data-orderId');
            $('#rating_product_id').val(product_id);
            $('#rating_order_id').val(order_id);
        });

        // Submit form with validation and show iziToast messages
        $('#rating_btn').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Ensure a rating is selected
            var rating = $('input[name="rating"]:checked').val();
            if (!rating) {
                iziToast.error({
                    message: "Please select a rating before submitting.",
                    position: 'topRight'
                });
                return;
            }

            // Form validation rules
            $("#product_review").validate({
                rules: {
                    rating: {
                        required: true,
                    },
                    review: {
                        minlength: 2,
                        maxlength: 1000,
                    },
                },
                messages: {
                    rating: {
                        required: 'Please select a rating.',
                    },
                    review: {
                        minlength: 'Review must be at least 2 characters.',
                        maxlength: 'Review can be a maximum of 1000 characters.',
                    },
                }
            });

            // Submit form via AJAX if valid
            if ($('#product_review').valid()) {
                var formData = $('#product_review').serialize();

                $.ajax({
                    type: "POST",
                    url: APP_URL + '/order/add-review',
                    data: formData,
                    beforeSend: function() {
                        $('body').addClass('loading'); // Show loader
                    },
                    success: function(response) {
                        $('body').removeClass('loading'); // Hide loader
                        if (response.success === true) {
                            iziToast.success({
                                message: response.messages,
                                position: 'topRight'
                            });

                            // Close the modal
                            $('#rating_review').modal('hide');

                            // Reset form fields
                            $('#product_review')[0].reset();

                            // Optionally refresh the page after success
                            setTimeout(function() {
                                location.reload();
                            }, 1500);

                        } else {
                            iziToast.error({
                                message: response.messages,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            message: 'An error occurred. Please try again.',
                            position: 'topRight'
                        });
                    },
                    complete: function() {
                        $('body').removeClass('loading'); // Always hide the loader
                    }
                });
            }
        });
    </script>
@endpush
