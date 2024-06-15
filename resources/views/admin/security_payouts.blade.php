@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header row">
                        <div class="col-sm-7 col-md-9">
                            <h4>Security Payouts</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-search-form :statusField="false" :dateField="false" :SearchVia="true" :Export='true' />
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ config('constants.renter') }} Email</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Order ID </th>
                                        <th scope="col">Order Amount</th>
                                        <th scope="col">Security Amount</th>
                                        <th scope="col">Security Amount Paid</th>
                                        <th scope="col">Security Payout Processed</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($securityPayouts as $securityPayout)
                                        <tr>
                                            <td scope="row">
                                                {{ $securityPayout->id }}
                                            </td>
                                            <td><a
                                                    href="{{ route('admin.view-customer-completed-orders', [$securityPayout->user_id]) }}">{{ strtolower($securityPayout->order->user->email) }}</a>
                                            </td>
                                            <td>{{ $securityPayout->transaction_id }}</td>
                                            <td><a
                                                    href="{{ route('admin.view-order', [$securityPayout->order_id]) }}">{{ '#' . $securityPayout->order_id }}</a>
                                            </td>
                                            <td>${{ $securityPayout->order->total }}</td>
                                            <td>${{ $securityPayout->order->security_option_amount }}</td>
                                            <td>${{ $securityPayout->paid_amount }}</td>
                                            <td>{{ date('m/d/Y h:i A', strtotime($securityPayout->security_return_date)) }}
                                            </td>
                                            <td>{{ $securityPayout->status = 'Yes' ? 'Completed' : 'Pending' }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">{{ $securityPayouts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
