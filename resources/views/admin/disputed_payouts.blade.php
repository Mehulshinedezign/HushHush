@extends('layouts.admin')
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header row">
                        <div class="col-sm-7 col-md-9">
                            <h4>Disputed Order Payouts</h4>
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
                                        <th scope="col">Payout Amount</th>
                                        <th scope="col">Dispute Date</th>
                                        <th scope="col">Resolve Date</th>
                                        <th scope="col">Dispute Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customerPayouts as $customerPayout)
                                        <tr>
                                            <td scope="row">
                                                {{ $customerPayout->id }}
                                            </td>
                                            <td>{{ strtolower($customerPayout->order->user->email) }}</td>
                                            <td>{{ is_null($customerPayout->transaction_id) && is_null($customerPayout->resolved_date) ? 'Not processed' : (is_null($customerPayout->transaction_id) && !is_null($customerPayout->resolved_date) ? 'Dispute Rejected' : $customerPayout->transaction_id) }}
                                            </td>
                                            <td><a
                                                    href="{{ route('admin.view-order', [$customerPayout->order_id]) }}">{{ '#' . $customerPayout->order_id }}</a>
                                            </td>
                                            <td>${{ $customerPayout->refund_amount ?? 0 }}</td>
                                            <td>{{ date('m/d/Y h:i:a', strtotime($customerPayout->order->dispute_date)) }}
                                            </td>
                                            <td>{{ is_null($customerPayout->resolved_date) ? 'Not-resolved' : date('m/d/Y h:i:a', strtotime($customerPayout->resolved_date)) }}
                                            </td>
                                            <td>{{ $customerPayout->order->dispute_status }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">{{ $customerPayouts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
