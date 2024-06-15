@extends('layouts.admin')
@section('content')

    <div class="section-body">
        <x-admin_alert />
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card admin-card">
                    <div class="card-body">
                        <ul class="list-unstyled listing-row">
                            <li class="d-flex align-items-center">
                                <h6 class="mr-2 mb-0">Name :</h6> <span>{{ ucwords(strtolower($customer->name)) ?? '' }}
                                </span>
                            </li>
                            <li class="d-flex align-items-center">
                                <h6 class="mr-2 mb-0">Email :</h6> <span>{{ strtolower($customer->email) ?? '' }} </span>
                            </li>
                            <li>
                                <div class="admin-block">
                                    <h3 class="admin-view-title">Bank Information </h3>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="list-unstyled admin-list listing-row">
                                                @if (is_null($customer->vendorBankDetails))
                                                    <li class="list-col m-0 justify-content-center">
                                                        <h6 class="text-center">No bank information added.</h6>
                                                    </li>
                                                @else
                                                    <li class="list-col"><span class="list-item"> Account Holder First Name
                                                            : </span> <span>
                                                            {{ ucwords(strtolower($customer->vendorBankDetails->account_holder_first_name)) }}
                                                        </span></li>
                                                    <li class="list-col"><span class="list-item"> Account Holder Last Name :
                                                        </span> <span>
                                                            {{ ucwords(strtolower($customer->vendorBankDetails->account_holder_last_name)) }}
                                                        </span></li>
                                                    <li class="list-col"><span class="list-item"> Account Holder DOB :
                                                        </span> <span>
                                                            {{ date($global_date_format, strtotime($customer->vendorBankDetails->account_holder_dob)) }}
                                                        </span></li>
                                                    <li class="list-col"><span class="list-item"> Account Holder Type :
                                                        </span> <span>
                                                            {{ ucwords(strtolower($customer->vendorBankDetails->account_holder_type)) }}
                                                        </span></li>
                                                    <li class="list-col"><span class="list-item"> Account Type : </span>
                                                        <span>
                                                            {{ ucwords(strtolower($customer->vendorBankDetails->account_type)) }}
                                                        </span>
                                                    </li>
                                                    <li class="list-col"><span class="list-item"> Account Number : </span>
                                                        <span> {{ $customer->vendorBankDetails->account_number }} </span>
                                                    </li>
                                                    <li class="list-col"><span class="list-item"> Routing Number : </span>
                                                        <span> {{ $customer->vendorBankDetails->routing_number }} </span>
                                                    </li>
                                                    <li class="list-col"><span class="list-item"> Currency : </span> <span>
                                                            {{ $customer->vendorBankDetails->currency }} </span></li>
                                                    <li class="list-col"><span class="list-item"> Country : </span> <span>
                                                            {{ $customer->vendorBankDetails->country }} </span></li>
                                                    <li class="list-col"><span class="list-item"> Status : </span> <span>
                                                            @if ($customer->vendorBankDetails->is_verified == 'No')
                                                                <div class="badge badge-warning">Verification Pending </div>
                                                            @else
                                                                <span class="badge badge-success">Verified </span>
                                                            @endif
                                                        </span></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                    </div>
                    </li>
                    </ul>
                    <span class="admin-amount">Total Number of Orders : <strong>
                            {{ $retailer->vendorCompletedOrderedItems->count() }}</strong></span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card author-box">
                <div class="card-header justify-content-between">
                    <h4>Completed Order</h4>
                    @if (
                        !is_null($retailer->vendorBankDetails) &&
                            $retailer->vendorBankDetails->is_verified == 'Yes' &&
                            !is_null($retailer->vendorBankDetails->stripe_account_token) &&
                            $retailer->vendorCompletedOrderedItems->isNotEmpty())
                        <button class="btn btn-success small-btn" onclick="payToRetailer(this);">Pay to
                            {{ config('constants.lender') }}</button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Order Total Amount</th>
                                    <th scope="col">Commission Amount </th>
                                    <th scope="col">Amount Pay to {{ config('constants.lender') }} </th>
                                    <th scope="col">Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailer->vendorCompletedOrderedItems as $orderItem)
                                    <tr>
                                        <td scope="row">
                                            @if (
                                                !is_null($retailer->vendorBankDetails) &&
                                                    $retailer->vendorBankDetails->is_verified == 'Yes' &&
                                                    !is_null($retailer->vendorBankDetails->stripe_account_token) &&
                                                    !in_array($orderItem->order_id, $paidOrderIds))
                                                <input type="checkbox" aria-label="Checkbox for following text input"
                                                    class="pointer" data-id="{{ $orderItem->order_id }}"
                                                    data-amount="{{ $orderItem->vendor_received_amount }}">
                                            @endif
                                            {{ $orderItem->order_id }}
                                        </td>
                                        <td>${{ $orderItem->order->total }}</td>
                                        <td>${{ is_null($orderItem->order->order_commission_amount) ? '0' : $orderItem->order->order_commission_amount }}
                                        </td>
                                        <td>${{ is_null($orderItem->vendor_received_amount) ? '0' : $orderItem->vendor_received_amount }}
                                        </td>
                                        <td>{!! in_array($orderItem->order_id, $paidOrderIds)
                                            ? '<div class="badge badge-success">Paid</div>'
                                            : '<div class="badge badge-danger">Not-Paid</div>' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header">
                        <h4>Completed Order</h4>
                        @if (
                            !is_null($customer->vendorBankDetails) &&
                                $customer->vendorBankDetails->is_verified == 'Yes' &&
                                !is_null($customer->vendorBankDetails->stripe_account_token) &&
                                $customer->vendorCompletedOrderedItems->isNotEmpty())
                            <button class="btn btn-success small-btn" onclick="payToRetailer(this);">Pay to
                                {{ config('constants.lender') }}</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Order Total Amount</th>
                                        <th scope="col">Commission Amount </th>
                                        <th scope="col">Amount Pay to {{ config('constants.lender') }} </th>
                                        <th scope="col">Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->vendorCompletedOrderedItems as $orderItem)
                                        <tr>
                                            <td scope="row">
                                                @if (
                                                    !is_null($customer->vendorBankDetails) &&
                                                        $customer->vendorBankDetails->is_verified == 'Yes' &&
                                                        !is_null($customer->vendorBankDetails->stripe_account_token) &&
                                                        !in_array($orderItem->order_id, $paidOrderIds))
                                                    <input type="checkbox" aria-label="Checkbox for following text input"
                                                        class="pointer" data-id="{{ $orderItem->order_id }}"
                                                        data-amount="{{ $orderItem->vendor_received_amount }}">
                                                @endif
                                                {{ $orderItem->order_id }}
                                            </td>
                                            <td>${{ $orderItem->order->total }}</td>
                                            <td>${{ is_null($orderItem->order->order_commission_amount) ? '0' : $orderItem->order->order_commission_amount }}
                                            </td>
                                            <td>${{ is_null($orderItem->vendor_received_amount) ? '0' : $orderItem->vendor_received_amount }}
                                            </td>
                                            <td>{!! in_array($orderItem->order_id, $paidOrderIds)
                                                ? '<div class="badge badge-success">Paid</div>'
                                                : '<div class="badge badge-danger">Not-Paid</div>' !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card author-box">
                    <div class="card-header">
                        <div class="col-md-10 px-0">
                            <h4>Payout Transactions</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Transaction ID</th>
                                        <th scope="col">Order ID </th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->vendorPayout as $payout)
                                        <tr>
                                            <td scope="row">{{ $payout->id }}</td>
                                            <td>{{ $payout->transaction_id }}</td>
                                            <td>{{ implode(',', $payout->order_id) }}</td>
                                            <td>${{ $payout->amount }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- Modal -->
    <div class="modal fade" id="paytoRetailer" tabindex="-1" role="dialog" aria-labelledby="paytoRetailerLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paytoRetailerLabel">Pay to Retailer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="unsetModalFields(this)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="selectedOrders" class="list-unstyled admin-list">
                    </ul>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.pay-to-retailer', [$customer->id]) }}" method="POST"
                        id="retailerForm">
                        @csrf
                        <input type="hidden" name="order_id" value="" id="orderId">
                        <button type="submit" class="btn btn-primary">Pay to {{ config('constants.lender') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery("#retailerForm").submit(function() {
            jQuery(this).find(".btn-primary").prop('disabled', true);
        });


        function payToRetailer(element) {
            var selectedOrder = {};
            var orderId = [];

            jQuery(".pointer:checked").each(function() {
                selectedOrder[jQuery(this).attr('data-id')] = jQuery(this).attr('data-amount');
                orderId.push(jQuery(this).attr('data-id'));
            });
            if (Object.keys(selectedOrder).length <= 0) {
                iziToast.error({
                    title: "Error",
                    message: "Please select at least one order.",
                    position: 'topRight'

                });
                return false;
            }

            var html = "";
            jQuery.each(selectedOrder, function(index, value) {
                html += "<li>Order Id : <b> " + index + " </b> Amount : <b> $" + value + " </b> </li>";
            });
            jQuery("#orderId").val(orderId.join(","));
            jQuery("#selectedOrders").empty().html(html);
            jQuery("#paytoRetailer").modal("show");
        }

        function unsetModalFields(element) {
            jQuery("#orderId").val("");
            jQuery("#selectedOrders").empty();

        }
    </script>
@endpush
