@extends('layouts.front')
@section('title', 'My query')
@section('links')
    @php
        $user = auth()->user();
    @endphp
@endsection

@section('content')
    <section class="rental-request-bx">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>Query List</h2>
                    <div class="form-group">
                        <div class="formfield">
                            <input type="text" placeholder="Select Date" class="form-control">
                            <span class="form-icon">
                                <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img" class="cal-icon">
                            </span>
                        </div>
                    </div>
                </div>
                @if ($querydatas->isNotEmpty())
                    <div class="inquiry-list-main mt-4">
                        <div class="db-table">
                            <div class="tb-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Name</th>
                                            <th>Query</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($querydatas as $query)
                                            <tr>
                                                <td>
                                                    <a href="#" class="user-table-profile">
                                                        <div class="table-profile ">
                                                            @if ($query->product)
                                                                <img src="{{ $query->product->thumbnailImage->file_path ?? '' }}"
                                                                    alt="tb-profile" width="26" height="27">
                                                            @else
                                                                <img src="{{ asset('front/images/table-profile1.png') }}"
                                                                    alt="tb-profile" width="26" height="27">
                                                            @endif

                                                        </div>
                                                    </a>

                                                </td>
                                                <td>
                                                    <div class="user-table-head">
                                                        <h5>{{ $query->product->name ?? '' }}</h5>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="Inquiry-desc">{{ $query->query_message ?? '' }}</p>
                                                </td>
                                                <td>{{ $query->date_range ?? '' }}</td>
                                                <td>
                                                    @if ($query->status == 'ACCEPTED')
                                                        ACCEPTED
                                                    @elseif($query->status == 'PENDING')
                                                        PENDING
                                                    @else
                                                        REJECTED
                                                    @endif
                                                </td>

                                                <td class="user-active">
                                                    <div class="inquiry-actions">
                                                        {{-- <a href="#" class="button accept-btn small-btn"><i
                                                                class="fa-solid fa-circle-check"></i> Accept</a>
                                                        <a href="#" class="button reject-btn small-btn"><i
                                                                class="fa-solid fa-ban"></i> Reject</a> --}}
                                                        <a href="#" class="button outline-btn small-btn"><i
                                                                class="fa-solid fa-comments"></i> Chat</a>
                                                        <a href="{{ route('query_view') }}"
                                                            class="button primary-btn small-btn single_query_Modal"
                                                            data-bs-toggle="modal"
                                                            data-product-id="{{ $query->product_id }}">
                                                            <i class="fa-solid fa-eye"></i> View
                                                        </a>
                                                        @if ($query->status == 'PENDING')
                                                            <a href="#" class="button outline-btn small-btn">Status
                                                                Pending...</a>
                                                        @elseif ($query->status == 'REJECTED')
                                                            <a href="#" class="button outline-btn small-btn">Status
                                                                Rejected...</a>
                                                        @else
                                                            {{-- <a href="{{ route('card.details', ['query' => $query->id]) }}"
                                                                class="button outline-btn small-btn">Book now
                                                                for {{ $query->negotiate_price }}$</a> --}}
                                                            @if (is_null($query->negotiate_price))
                                                                @php
                                                                    $price = $query->getCalculatedPrice(
                                                                        $query->date_range,
                                                                    );
                                                                @endphp
                                                                <a href="{{ route('card.details', ['query' => $query->id, 'price' => $price]) }}"
                                                                    class="button outline-btn small-btn" data-toggle="modal"
                                                                    data-price="{{ $price }}">Book now for
                                                                    {{ $price }}$</a>
                                                            @else
                                                                <a href="{{ route('card.details', ['query' => $query->id, 'price' => $query->negotiate_price]) }}"
                                                                    class="button outline-btn small-btn" data-toggle="modal"
                                                                    data-price="{{ $query->negotiate_price }}">Book now for
                                                                    {{ $query->negotiate_price }}$</a>
                                                            @endif
                                                        @endif
                                                        {{-- <a href="javascript:void(0)" class="button outline-btn small-btn"
                                                            data-toggle="modal" data-rating_product_id="{{$query->product_id}}" data-target="#rating_review">Rating &
                                                            Review</a> --}}


                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="list-empty-box">
                        <img src="{{ asset('front/images/no-products.svg') }}">
                        <h3 class="text-center">Your Query is empty</h3>
                    </div>
                @endif
            </div>
        </div>
    </section>


    {{-- Rating modal  --}}
    <div class="modal fade rate_review" id="rating_review" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="cancellation-popup-sec">
                        <div class="popup-head rating_pop_head">
                            <h6>Write Review</h6>
                            <button type="button" class="btn-close query_btn_close close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="pro-tab-contant-row" id="reviewProduct"></div>
                        <form id="product_review">
                            @csrf
                            <input type="hidden" name="product_id" id="rating_product_id" value="">
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
                                        <input type="radio" name="rating" value="2" id="two"
                                            class="star">
                                        <label for="two"><i class="fa fa-star"></i></label>
                                        <input type="radio" name="rating" value="1" id="one"
                                            class="star">
                                        <label for="one"><i class="fa fa-star"></i></label>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control mb-3" name="review" rows="3" placeholder="Please write product review here...."></textarea>
                            <p class="popup-p">By submitting review you give us consent to publish and process personal
                                information in accordance with Term of use and Privacy Policy</p>
                            <button type="submit" class="button full-btn primary-btn submit mt-3" id="rating_btn">Submit&nbsp;<i
                                    class="fa-solid fa-circle-notch fa-spin show-loader"
                                    style="display:none;"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="single_query_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="data-query">


                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @includeFirst(['validation'])
    <script>
        $(document).ready(function() {
            $('.single_query_Modal').on('click', function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var productId = $(this).data('product-id');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        $('#single_query_Modal .modal-body').html(response.data);
                        $('#single_query_Modal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('.my_query_details').hide();
        });


        $('#rating_review').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var productId = button.data('rating_product_id');
            $('#rating_product_id').val(productId);
        });

        $('#rating_btn').click(function(e) {
            e.preventDefault();

            $("#product_review").validate({
                rules: {
                    review: {
                        required: true,
                        minlength: 2,
                        maxlength: 1000,
                    },
                },
                messages: {
                    review: {
                        required: 'This filed is required.',
                        minlength: 'Minumum 2 character are allow.',
                        maxlength: 'Maximum 1000 character are allow.',
                    },
                }
            });

            if ($('#product_review').valid()) {
                var formData = jQuery('form#product_review').serialize();
                $.ajax({
                    type: "POST",
                    url: '/order/add-review',
                    data: formData,
                    beforeSend: function() {
                        $('body').addClass('loading');
                    },
                    complete: function() {
                        $('body').removeClass('loading');
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#rating_review').find('.close').trigger('click');
                            $('#product_review')[0].reset();
                            iziToast.success({
                                message: response.messages,
                                position: 'topRight'
                            });
                        } else {
                            $('#rating_review').find('.close').trigger('click');
                            $('#product_review')[0].reset();
                            iziToast.error({
                                message: response.messages,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        iziToast.error({
                            message: 'An error occurred. Please try again.',
                            position: 'topRight'
                        });
                    },
                });

            }

        });
    </script>
@endpush
