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
                            <div class=" tb-table">
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
                                                                <img src="{{ $query->product->thumbnailImage->file_path ?? ''}}"
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
                                                    @if($query->status == 'ACCEPTED')
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
                                                        @if($query->status == 'PENDING' || $query->status == 'REJECTED')
                                                            <a href="#" class="button outline-btn small-btn">Status Pendding...</a>
                                                        @else
                                                            <a href="#" class="button outline-btn small-btn">Book now for {{$query->negotiate_price}}$</a>
                                                        @endif

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
    </script>
@endpush
