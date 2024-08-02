@extends('layouts.front')
@section('title', 'My query')
@section('links')
    @php
        $user = auth()->user();
    @endphp
@endsection

@section('content')
    <section class="rental-request-bx min-height-100">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>My query List</h2>
                    <div class="form-group">
                        {{-- <div class="formfield">
                            <input type="text" placeholder="Select Date" class="form-control">
                            <span class="form-icon">
                                <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img" class="cal-icon">
                            </span>
                        </div> --}}
                    </div>
                </div>
                <div class="custom-tab">
                    <ul class="custom-tab-list">
                        <li class="tab-item active" data-status="PENDING" data-user="borrower"><a
                                href="javascript:void(0)">Pending</a></li>
                        <li class="tab-item" data-status="ACCEPTED" data-user="borrower"><a
                                href="javascript:void(0)">Accepted</a></li>
                        <li class="tab-item" data-status="REJECTED" data-user="borrower"><a
                                href="javascript:void(0)">Rejected</a></li>
                        <li class="tab-item" data-status="COMPLETED" data-user="borrower"><a
                                href="javascript:void(0)">Completed</a></li>
                    </ul>
                </div>
                <div id="query-list-container">
                    <x-product-query :querydatas="$querydatas" />
                </div>
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
                            <button type="button" class="btn-close query_btn_close close" data-dismiss="modal"
                                aria-label="Close">
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

    <div class="modal fade" id="single_query_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    {{-- @includeFirst(['validation']) --}}
    <script>
        $(document).ready(function() {
            var singleQueryModal = new bootstrap.Modal(document.getElementById('single_query_Modal'));

            $(document).on('click', '.single_query_Modal', function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var queryId = $(this).data('query-id');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        query_id: queryId
                    },
                    success: function(response) {
                        $('#single_query_Modal .modal-body').html(response.data);
                        singleQueryModal.show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading query details:', error);
                    }
                });
            });

            $('.my_query_details').hide();

            $(document).on('click', '.query_btn_close', function() {
                singleQueryModal.hide();
            });
            $('#single_query_Modal').on('hidden.bs.modal', function() {
                $('#single_query_Modal .modal-body').html('');
            });



            // Accept Reject and Pendding
            // function fetchQueries(status) {
            //     $.ajax({
            //         url: '/fetch-queries',
            //         type: 'GET',
            //         data: {
            //             status: status
            //         },
            //         beforeSend: function() {
            //             $('body').addClass('loading');
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 $('#query-list-container').html(response.html);
            //             } else {
            //                 $('#query-list-container').html(
            //                     '<div class="error">Failed to load queries.</div>');
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('Error:', error);
            //             $('#query-list-container').html(
            //                 '<div class="error">An error occurred. Please try again.</div>');
            //         },
            //         complete: function() {
            //             $('body').removeClass('loading');
            //         }
            //     });
            // }

            // $('.tab-item').on('click', function(e) {
            //     e.preventDefault();

            //     $('.tab-item').removeClass('active');
            //     $(this).addClass('active');
            //     var status = $(this).data('status');

            //     fetchQueries(status);
            // });

            // var initialStatus = $('.tab-item.active').data('status');
            // fetchQueries(initialStatus);


        });


        $('#rating_review').on('show.bs.modal', function(event) {
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
