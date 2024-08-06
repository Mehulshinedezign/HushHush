{{-- @if (count($orders) > 0)
    @foreach ($orders as $order)
        @if ($order->status == 'Pending' || $order->status == 'Picked Up')
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
@if (check_order_list_paginate('Pending') > 10)
    <div class="custom-pagination">
        {{ $orders->links('pagination::product-list') }}
    </div>
@endif --}}
<div class="order-his-card-box">
    <div class="row g-3">
        @foreach ($orders as $order)
            @if ($order->status == 'Waiting' || $order->status == 'Picked Up')
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="order-his-card">
                        <div class="order-card-top">
                            <div class="order-card-img">
                                <a href="{{ route('vieworder', ['order' => $order->id]) }}">
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
                        <div class="order-card-footer">
                            <a href="#" data-url="{{ route('cancel-order', $order->id) }}"
                                class="button outline-btn full-btn cancel-order" data-toggle="modal"
                                data-bs-target="#cancellation-note">Cancel
                                order</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

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
<div class="modal fade cencel-order-modal" id="cancellation-note" tabindex="-1"
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
    <script>
        $(document).ready(function() {

            $('.cancel-order').on('click', function() {
                // $('.modal fade').addClass('d-none');

                swal({
                        title: 'Order Cancelled',
                        text: 'The platform charges has been deducted by stripe',
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
@endpush
