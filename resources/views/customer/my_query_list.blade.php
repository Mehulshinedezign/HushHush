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
    </script>
@endpush
