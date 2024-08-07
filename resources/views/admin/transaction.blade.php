@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header row">
                        <div class="col-sm-7 col-md-9">
                            <h4>Transaction</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-search-form :statusField="false" :dateField="false" :SearchVia="true" :Export='false' />
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">User Email</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td scope="row">
                                                {{ $order->transaction->id }}
                                            </td>
                                            <td>{{ $order->user->email }}</td>
                                            <td>{{ $order->transaction->payment_id }}</td>
                                            <td>
                                                {{ $order->id }}
                                            </td>
                                            <td>${{ $order->transaction->total }}</td>
                                            <td>{{ date('m/d/Y h:i A', strtotime($order->transaction->date)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="card-footer text-right">{{ $retailerPayouts->links() }}</div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
