@extends('layouts.front')
@section('title', 'Receive Query')
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
                                            <th>date</th>
                                            <th>Set price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($querydatas as $query)
                                            <tr class="user_query-{{ $query->id }}">
                                            <tr class="user_query-{{ $query->id }}">
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
                                                    <input type="text" id="negotiate_price_{{ $query->id }}"
                                                        placeholder="Enter negotiate price">
                                                </td>
                                                <td class="user-active">
                                                    <div class="inquiry-actions">

                                                        <a href="javascript:void(0)" class="button accept-btn small-btn"
                                                            onclick="acceptQuery('{{ $query->id }}')">
                                                            <i class="fa-solid fa-circle-check"></i> Accept
                                                        </a>
                                                        <a href="javascript:void(0)" class="button reject-btn small-btn"
                                                            onclick="confirmReject(event, '{{ $query->id }}')">
                                                            <i class="fa-solid fa-circle-check"></i> Reject
                                                        </a>

                                                        <a href="javascript:void(0)"
                                                            class="button outline-btn small-btn chat-list-profile"
                                                            data-senderId="{{ auth()->user()->id }}"
                                                            data-receverId="{{ $query->user_id }}"
                                                            data-receverName = "{{ $query->user->name }}"
                                                            data-receverImage="{{ isset($query->user->profile_file) ? Storage::url($query->user->profile_file) : asset('img/avatar.png') }}"
                                                            data-profile="{{ isset(auth()->user()->profile_file) ? Storage::url(auth()->user()->profile_file) : asset('img/avatar.png') }}"
                                                            data-name="{{ auth()->user()->name }}"><i
                                                                class="fa-solid fa-comments"></i>
                                                            Chat</a>
                                                        <a href="{{ route('query_view') }}"
                                                            class="button primary-btn small-btn single_query_Modal"
                                                            data-bs-toggle="modal"
                                                            data-product-id="{{ $query->product_id }}">
                                                            <i class="fa-solid fa-eye"></i> View
                                                        </a>

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
                        <h3 class="text-center">Receive Query is empty</h3>
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
    {{-- @includeFirst(['validation']) --}}

    {{-- @includeFirst(['validation']) --}}

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
        });

        // accept query
        function acceptQuery(queryId) {
            var price = document.getElementById('negotiate_price_' + queryId).value;
            var encodedPrice = (price);
            var url = `{{ url('/accept_query') }}/${queryId}?negotiate_price=${encodedPrice}`;
            window.location.href = url;
        }

        // reject query
        function confirmReject(event, queryId) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to reject this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1B1B1B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('body').addClass('loading');
                    rejectQuery(queryId);
                }
            });
        }

        function rejectQuery(queryId) {
            var url = `{{ url('/reject_query') }}/${queryId}`;
            window.location.href = url;
            $('.user_query-' + queryId).remove();
            $('.user_query-' + queryId).remove();
        }
    </script>

    {{-- chat --}}

    {{-- chat --}}
@endpush
