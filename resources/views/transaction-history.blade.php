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
            <div class="rental-request-wrapper order-trans-wrap">
                <div class="rental-header">
                    <h2>Transaction</h2>
                    <div class="form-group">
                    </div>
                </div>

                <div id="query-list-container">
                    <div class="inquiry-list-main mt-4">
                        <div class="db-table">
                            <div class="tb-table">
                                <div class="table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Order</th>
                                                <th>Name</th>
                                                <th>Spent</th>
                                                <th style="width: 200px;">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($orders as $order)
                                                <tr class="user_query-{{ $order->id }}">
                                                    <td>
                                                        <a href="#" class="user-table-profile">
                                                            <div class="table-profile">
                                                                @if ($order->product->name)
                                                                    <img src="{{ $order->product->thumbnailImage->file_path ?? '' }}"
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
                                                            <h5>{{ $order->product->name ?? '' }}</h5>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="user-table-head">
                                                            <h5 class="plus-minus">
                                                                <i class="fa-solid fa-minus"></i>
                                                                ${{ $order->transaction->total ?? '0' }}
                                                            </h5>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="user-table-head">
                                                            <h5>{{ $order->transaction->date }}</h5>
                                                        </div>
                                                    </td>

                                                </tr>


                                            @empty
                                                <div class="no-transation-txt">Transaction Not Exist</div>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
