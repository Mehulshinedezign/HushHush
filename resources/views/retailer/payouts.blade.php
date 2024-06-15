@extends('layouts.retailer')

@section('title', 'Payouts')

@section('links')
    <script>
        dateOptions['maxDate'] = moment();
    </script>
@stop

@section('content')
    <div class="right-content innerpages">
        <div class="d-flex justify-content-between">
            <h2 class="heading_dash">Payment transaction history</h2>
            <div class="select-option">
                <select class="form-select border-0" aria-label="Default select example">
                    <option selected="">Past 2 Months</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
        <div class="cardtab pytab">
            <nav class="d-flex justify-content-between align-items-center">
                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="earned-tab" data-bs-toggle="tab" data-bs-target="#earned" type="button" role="tab" aria-controls="earned" aria-selected="true"><i class="fa-solid fa-circle" style="color: #517B5D;"></i> Earned</button>
                    {{-- <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false"><i class="fa-solid fa-circle" style="color: #CC9C55;"></i>Pending</button> --}}
                </div>
                <div class="input-group-btn">
                       <a href="{{ route('retailer.payouts_list.export') }}" class="btn btn-dark">Export Data</a>
                   </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- Earned Tab start-->
                <div class="tab-pane fade active show" id="earned" role="tabpanel" aria-labelledby="earned-tab">
                    <div class="p_h-wrap">
                        <div class="wrapper_table">
                            @if(count($transactions) > 0)
                            <table class="rwd-table table-fixed">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        {{-- <th class="text-end">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>                                    
                                    @foreach($transactions as $index => $transaction)
                                        <tr>
                                            <td data-th="product_name">
                                                @foreach($transaction->order_id as $orderId) 
                                                    @if ($loop->last)
                                                        <a href="{{ route('retailer.vieworder', $orderId) }}"> #{{ $orderId }} </a>
                                                    @else
                                                        <a href="{{ route('retailer.vieworder', $orderId) }}"> #{{ $orderId }}</a>,
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td data-th="date">
                                                {{ $transaction->transaction_id }}
                                            </td>
                                            <td data-th="amount">
                                                ${{ $transaction->amount }}
                                            </td>
                                            <td>
                                                {{date('m/d/Y',strtotime($transaction->created_at))}}
                                            </td>
                                        </tr> 
                                    @endforeach                                                                                                         
                                </tbody>
                            </table>
                            @else
                                Data not found
                            @endif 
                        </div> 
                            {{ $transactions->links('pagination::product-list') }} 
                    </div>
                </div>
                <!-- Earned Tab end-->
                <!-- Pending Tab start-->
                <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="p_h-wrap">
                        <div class="wrapper_table">
                           Data not found
                        </div>
                    </div>
                </div>
                <!-- Pending Tab end-->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function(){
        jQuery('#filterPayouts').click(function(e) {
            e.preventDefault();
            let searchText = (jQuery('input[name="search"]').val()).trim();
            jQuery('input[name="search"]').val(searchText);
            jQuery(this).parents('form').submit();
        }); 
    });
</script>
@endpush