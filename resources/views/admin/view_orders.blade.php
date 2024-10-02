@extends('layouts.admin')
@section('content')

    <div class="section-body">
        <div class="row">

            <div class="col-md-12">
                <x-admin_alert />
                <div class="card admin-card">
                    <div class="card-body">
                        @if($data =='viewOrder')
                            <a href="{{ route('admin.orders') }}"><span><i class="fa-solid fa-angle-left"></i>Back</span></a>
                        @else
                            <a href="{{ route('admin.disputed-orders') }}"><span><i class="fa-solid fa-angle-left"></i>Back</span></a>
                        @endif
                            <ul class="list-unstyled listing-row">
                            <li class="d-flex align-items-center">
                                <h6 class="mr-2 mb-0"> {{ config('constants.renter') }} Email :</h6>
                                <span>{{ strtolower($order->user->email) }}</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <h6 class="mr-2 mb-0"> {{ config('constants.lender') }} Email :</h6>
                                <span>{{ strtolower($order->retailer->email) }}</span>
                            </li>
                            <li>
                                <div class="admin-block">
                                    <h3 class="admin-view-title">Order Detail : </h3>
                                    <div class="row">
                                        {{-- <div class="col-md-6"> --}}
                                        {{-- <ul class="list-unstyled admin-list listing-row"> --}}
                                        {{-- <li class="list-col"><span class="list-item">Security Option </span> <span>
                                                        {{ ucwords($order->security_option) }} </span></li>
                                                <li class="list-col"><span class="list-item">Security Option Type </span>
                                                    <span> {{ ucwords($order->security_option_type) }} </span>
                                                </li>
                                                <li class="list-col"><span class="list-item">Security Option Value </span>
                                                    <span> {{ $order->security_option_value }} </span>
                                                </li> --}}
                                        {{-- <li class="list-col"><span
                                                        class="list-item">{{ config('constants.renter') }} Transaction Fee
                                                        Type </span> <span> {{ $order->customer_transaction_fee_type }}
                                                    </span></li>
                                                <li class="list-col"><span
                                                        class="list-item">{{ config('constants.renter') }} Transaction Fee
                                                        Value </span> <span> {{ $order->customer_transaction_fee_value }}
                                                    </span></li>
                                                <li class="list-col"><span
                                                        class="list-item">{{ config('constants.renter') }} Transaction Fee
                                                        Amount </span> <span>
                                                        ${{ $order->customer_transaction_fee_amount }} </span></li> --}}
                                        {{-- <li class="list-col"><span class="list-item">Order Commission Type </span>
                                                    <span> {{ $order->order_commission_type }} </span>
                                                </li>
                                                <li class="list-col"><span class="list-item">Order Commission Value </span>
                                                    <span> {{ $order->order_commission_value }} </span>
                                                </li>
                                                <li class="list-col"><span class="list-item">Order Commission Amount </span>
                                                    <span> ${{ $order->order_commission_amount }} </span>
                                                </li>
                                                <li class="list-col"><span
                                                        class="list-item">{{ config('constants.lender') }} Receieved Amount
                                                    </span> <span> ${{ $order->item->vendor_received_amount }} </span></li> --}}

                                        {{-- </ul> --}}
                                        {{-- </div> --}}
                                        <div class="col-md-6">
                                            <ul class="list-unstyled admin-list listing-row">
                                                <li class="list-col"><span class="list-item"> Order Status :</span> <span>
                                                        {!! $order->status == 'Pending'
                                                            ? '<span class="badge badge-primary">' . $order->status . '</span>'
                                                            : ($order->status == 'Cancelled'
                                                                ? '<span class="badge badge-danger">' . $order->status . '</span>'
                                                                : ($order->status == 'Picked Up'
                                                                    ? '<span class="badge badge-warning">' . $order->status . '</span>'
                                                                    : '<span class="badge badge-success">' . $order->status . '</span>')) !!} </span></li>
                                                <li class="list-col"><span class="list-item">
                                                        {{ config('constants.renter') }} Confirmed Pickup :</span> <span>
                                                        {!! $order->customer_confirmed_pickedup == '0'
                                                            ? '<span class="badge badge-warning">Confirmation Pending</span>'
                                                            : '<span class="badge badge-success">Confirmed</span>' !!} </span></li>
                                                <li class="list-col"><span class="list-item">
                                                        {{ config('constants.lender') }} Confirmed Pickup :</span> <span>
                                                        {!! $order->retailer_confirmed_pickedup == '0'
                                                            ? '<span class="badge badge-warning">Confirmation Pending</span>'
                                                            : '<span class="badge badge-success">Confirmed</span>' !!} </span></li>
                                                <li class="list-col"><span class="list-item">
                                                        {{ config('constants.renter') }} Confirmed Return :</span> <span>
                                                        {!! $order->customer_confirmed_returned == '0'
                                                            ? '<span class="badge badge-warning">Confirmation Pending</span>'
                                                            : '<span class="badge badge-success">Confirmed</span>' !!} </span></li>
                                                <li class="list-col"><span class="list-item">
                                                        {{ config('constants.lender') }} Confirmed Return :</span> <span>
                                                        {!! $order->retailer_confirmed_returned == '0'
                                                            ? '<span class="badge badge-warning">Confirmation Pending</span>'
                                                            : '<span class="badge badge-success">Confirmed</span>' !!} </span></li>
                                                @if (!is_null($order->disputeDetails))
                                                    <li class="list-col"><span class="list-item"> Disputed Date - Time
                                                            :</span> <span>
                                                            {{ date('m/d/Y h:i:a', strtotime($order->dispute_date)) }}
                                                        </span></li>
                                                    <li class="list-col"><span class="list-item"> Disputed Status :</span>
                                                        <span> {!! $order->dispute_status == 'Yes'
                                                            ? '<span class="badge badge-danger">Disputed</span>'
                                                            : '<span class="badge badge-success">Resolved</span>' !!} </span>
                                                    </li>
                                                    @if (!is_null($order->disputeDetails->resolved_date))
                                                        <li class="list-col"><span class="list-item"> Disputed Resolved Date
                                                                - Time :</span> <span>
                                                                {{ date('m/d/Y h:i:a', strtotime($order->disputeDetails->resolved_date)) }}
                                                            </span></li>
                                                    @endif
                                                @endif
                                                @if ($order->status == 'Cancelled')
                                                    <li class="list-col"><span class="list-item">Cancellation Note :</span>
                                                        <span> {{ $order->cancellation_note }} </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        </ul>
                        <span class="admin-amount">Order Total : <strong> ${{ $order->total }}</strong></span>
                    </div>
                </div>
                <div class="row">
                    @if ($order->customerPickedUpImages->isNotEmpty())
                        <div class="col-12 col-sm-12 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Picked Up Attached Files by {{ config('constants.renter') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="disputed-img-outer">
                                                @foreach ($order->customerPickedUpImages as $customerPickedUpImage)
                                                    <img class="dispute-admin-img"
                                                        src="{{ ($customerPickedUpImage->url) }}"
                                                        height="25%" width="25%">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($order->retailerPickedUpImages->isNotEmpty())
                        <div class="col-12 col-sm-12 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Picked Up Attached Files by {{ config('constants.lender') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="disputed-img-outer">
                                                @foreach ($order->retailerPickedUpImages as $retailerPickedUpImage)
                                                    <img class="dispute-admin-img"
                                                        src="{{ ($retailerPickedUpImage->url) }}"
                                                        height="25%" width="25%">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($order->customerReturnedImages->isNotEmpty())
                        <div class="col-12 col-sm-12 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Returned Attached Files by {{ config('constants.renter') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="disputed-img-outer">
                                                @foreach ($order->customerReturnedImages as $customerReturnedImage)
                                                    <img class="dispute-admin-img"
                                                        src="{{ ($customerReturnedImage->url) }}"
                                                        height="25%" width="25%">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($order->retailerReturnedImages->isNotEmpty())
                        <div class="col-12 col-sm-12 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Returned Attached Files by {{ config('constants.lender') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="disputed-img-outer">
                                                @foreach ($order->retailerReturnedImages as $retailerReturnedImage)
                                                    <img class="dispute-admin-img"
                                                        src="{{($retailerReturnedImage->url) }}"
                                                        height="25%" width="25%">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($order->status == 'Completed' && !is_null($order->rating))
                        <div class="col-12 col-sm-12 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Review</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="given-review">
                                                <div class="form-group m-0">
                                                    <div class="rating">
                                                        <div class="stars">
                                                            <input type="radio" name="rating" value="5"
                                                                id="five" class="star"
                                                                @if ($order->rating->rating == '5') checked @endif>
                                                            <label for="five"><i class="fa fa-star"></i></label>
                                                            <input type="radio" name="rating" value="4"
                                                                id="four" class="star"
                                                                @if ($order->rating->rating == '4') checked @endif>
                                                            <label for="four"><i class="fa fa-star"></i></label>
                                                            <input type="radio" name="rating" value="3"
                                                                id="three" class="star"
                                                                @if ($order->rating->rating == '3') checked @endif>
                                                            <label for="three"><i class="fa fa-star"></i></label>
                                                            <input type="radio" name="rating" value="2"
                                                                id="two" class="star"
                                                                @if ($order->rating->rating == '2') checked @endif>
                                                            <label for="two"><i class="fa fa-star"></i></label>
                                                            <input type="radio" name="rating" value="1"
                                                                id="one" class="star"
                                                                @if ($order->rating->rating == '1') checked @endif>
                                                            <label for="one"><i class="fa fa-star"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{ $order->rating->review }}
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- @dd($order->disputeDetails); --}}
                @if (!is_null($order->disputeDetails))
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-12 col-md-12">
                            <div class="card ">
                                <div class="card-header dispute-details-head">
                                    <h4>Dispute Details</h4>
                                    <form action="{{route('admin.resolve-dispute-order', [$order->id])}}" method="post">
                                        @csrf
                                    <select name="status" id="" class="form-control">
                                        <option value="new" @if(isset($order->disputeDetails->status) && $order->disputeDetails->status == 'new') selected @endif>New</option>
                                        <option value="Viewed" @if(isset($order->disputeDetails->status) && $order->disputeDetails->status == 'viewed') selected @endif>Viewed</option>
                                        <option value="Resolved"@if(isset($order->disputeDetails->status) && $order->disputeDetails->status == 'resolved') selected @endif>Resolved</option>
                                    </select>
                                <button type="submit" class="btn btn-primary mt-4">Resolve Dispute</button>
                                </form>
                                    {{-- @if (!is_null($order->disputeDetails) && $order->dispute_status == 'Yes')
                                        <button class="btn btn-success small-btn" data-toggle="modal"
                                            data-target="#resolveDispute">Resolve Dispute</button>
                                    @endif --}}
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <ul class="list-unstyled admin-list listing-row">
                                                <li class="list-col">
                                                    <span class="list-item">Disputed By : </span>
                                                    <span>{{ ucwords(strtolower($order->disputeDetails->reported_by)) }}
                                                    </span>
                                                </li>
                                                <li class="list-col">
                                                    <span class="list-item">Disputed Subject : </span>
                                                    <span>{{ ucwords(strtolower($order->disputeDetails->subject)) }}
                                                    </span>
                                                </li>
                                                <li class="list-col">
                                                    <span class="list-item">Disputed description : </span>
                                                    <span>{{ $order->disputeDetails->description }} </span>
                                                </li>
                                                <li class="list-col">
                                                    <span class="list-item">Disputed Date - Time : </span>
                                                    <span>{{ date('m/d/Y h:i:a', strtotime($order->dispute_date)) }}
                                                    </span>
                                                </li>
                                                <li class="list-col">
                                                    <span class="list-item">Disputed Status : </span>
                                                    {!! $order->dispute_status == 'Yes'
                                                        ? '<span class="badge badge-danger">Disputed</span>'
                                                        : '<span class="badge badge-success">Resolved</span>' !!}
                                                </li>
                                                {{-- <li class="list-col"><span class="list-item">Amount to Refund : </span>
                                                    ${{ $order->security_option == 'security' && $order->status == 'Picked Up' ? '0.00' : ($order->dispute_status == 'Resolved' && $order->disputeDetails->refund_amount > '0.00' ? $order->disputeDetails->refund_amount : ($order->disputeDetails->refund_amount == '0.00' ? $order->disputeDetails->refund_amount : $order->total)) }}
                                                </li> --}}
                                                @if (!is_null($order->disputeDetails->resolved_date))
                                                    <li class="list-col"><span class="list-item">Disputed Resolved Date -
                                                            Time : </span>
                                                        <span>{{ date('m/d/Y h:i:a', strtotime($order->disputeDetails->resolved_date)) }}
                                                        </span>
                                                    </li>
                                                    {{-- <li class="list-col">
                                                        <span class="list-item">Refunded Amount : </span>
                                                        <span>
                                                            ${{ is_null($order->disputeDetails->refund_amount) ? '0' : $order->disputeDetails->refund_amount }}
                                                        </span>
                                                    </li> --}}
                                                @endif
                                            </ul>

                                        </div>
                                        <div class="col-md-12 col-lg-6">
                                            <div class="disputed-img-outer">
                                                @foreach ($order->disputedOrderImages as $disputedOrderImage)
                                                    <img class="dispute-admin-img"
                                                        src="{{ ($disputedOrderImage->url) }}" height="100"
                                                        width="100">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (!is_null($order->disputeDetails) && $order->dispute_status == 'Yes')
        <!-- Modal -->
        <div class="modal fade" id="resolveDispute" tabindex="-1" role="dialog" aria-labelledby="resolveDisputeLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resolveDisputeLabel">Dispute Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="list-unstyled admin-list listing-row">
                                    <li class="list-col"><span class="list-item">Disputed By :</span> <span>
                                            {{ ucwords(strtolower($order->disputeDetails->reported_by)) }} </span></li>
                                    <li class="list-col"><span class="list-item">Disputed Subject :</span> <span>
                                            {{ ucwords(strtolower($order->disputeDetails->subject)) }} </span></li>
                                    <li class="list-col"><span class="list-item">Disputed description :</span> <span>
                                            {{ $order->disputeDetails->description }} </span></li>
                                    <li class="list-col"><span class="list-item">Disputed Date - Time :</span> <span>
                                            {{ date(request()->global_date_time_format, strtotime($order->dispute_date)) }}
                                        </span></li>
                                    <li class="list-col"><span class="list-item">Disputed Status :</span> <span>
                                            {!! $order->dispute_status == 'Yes'
                                                ? '<span class="badge badge-danger">Disputed</span>'
                                                : '<span class="badge badge-success">Resolved</span>' !!} </span></li>
                                    <li class="list-col"><span class="list-item">Amount to Refund :</span> <span>
                                            ${{ $order->security_option == 'security' && $order->status == 'Picked Up' ? '0.00' : ($order->dispute_status == 'Resolved' && $order->disputeDetails->refund_amount > '0.00' ? $order->disputeDetails->refund_amount : ($order->disputeDetails->refund_amount == '0.00' ? $order->disputeDetails->refund_amount : $order->total)) }}
                                        </span></li>
                                </ul>
                                {{-- @foreach ($order->disputedOrderImages as $disputedOrderImage)
                                    <img class="dispute-admin-img" src="{{ $disputedOrderImage->url }}" height="100"
                                        width="100">
                                @endforeach --}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('admin.resolve-dispute-order', [$order->id]) }}" method="POST"
                                id="disputeForm">
                                @csrf
                                <label> Change Order Status :</label>
                                <select class="form-control" name="resolved_status">
                                    <option value="Completed"> Completed </option>
                                    <option value="Cancelled"> Cancelled </option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-4">Resolve Dispute</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script type="text/javascript">
        jQuery("#disputeForm").submit(function() {
            jQuery(this).find(".btn-primary").prop('disabled', true);
        });
    </script>
@endpush
