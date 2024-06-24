@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box order-wrapper">
                    <div class="card-header">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <x-search-form :nameField="false" :statusField="false" :dateField="false" :ordersField="true" />
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">{{ config('constants.renter') }} Email</th>
                                        <th scope="col">{{ config('constants.lender') }} Email</th>
                                        <th scope="col">Payment ID</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Order Status</th>
                                        <th scope="col">Reservation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td scope="row">
                                                <a
                                                    href="{{ route('admin.view-order', [$order->id]) }}">#{{ $order->id }}</a>
                                            </td>
                                            <td>{{ $order->user->email }}</td>
                                            <td>{{ !is_null($order->item) ? $order->item->retailer->email : "n/a" }} </td>
                                            <td>{{ $order->transaction->payment_id ?? '0' }}</td>
                                            <td>${{ $order->total }}</td>
                                            <td>{{ $order->status }}</td>
                                            <td>{{ date('m/d/Y', strtotime($order->from_date)) . ' - ' . date('m/d/Y', strtotime($order->to_date)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($orders->count() == 0)
                                        <tr>
                                            <td colspan="7" class="text-center text-dark    ">No Orders</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">{{ $orders->appends($_GET)->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
