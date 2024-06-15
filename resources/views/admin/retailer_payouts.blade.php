@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header row">
                        <div class="col-sm-7 col-md-9">
                            <h4>{{ config('constants.lender') }} Payouts</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-search-form :statusField="false" :dateField="false" :SearchVia="true" :Export='true' />
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ config('constants.lender') }} Email</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($retailerPayouts as $retailerPayout)
                                        <tr>
                                            <td scope="row">
                                                {{ $retailerPayout->id }}
                                            </td>
                                            <td>{{ $retailerPayout->retailerDetails->email }}</td>
                                            <td>{{ $retailerPayout->transaction_id }}</td>
                                            <td>
                                                @foreach ($retailerPayout->order_id as $orderId)
                                                    <a href="{{ route('admin.view-order', $orderId) }}">{{ '#' . $orderId }}
                                                        @if ($loop->last != $orderId)
                                                            ,
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </td>
                                            <td>${{ $retailerPayout->amount }}</td>
                                            <td>{{ date('m/d/Y h:i A', strtotime($retailerPayout->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">{{ $retailerPayouts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
