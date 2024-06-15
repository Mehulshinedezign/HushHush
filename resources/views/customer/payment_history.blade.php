@extends('layouts.front')

@section('title', 'Payment History')

@section('content')
    <section>
       @include('category')
    </section>
    <section>
        <div class="payment-transaction-history-section section-space">
            <div class="container">
                <div class="payment-transaction-history-title">
                    <h4>Payment transaction history</h4>
                    <div class="select-option">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Past 2 Months</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="custom-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($payment_history) > 0)
                            @foreach($payment_history as $history)
                            <tr>
                                <td>
                                    <div class="pro-img-name">
                                    <div class="pro-tab-image">
                                        @if(@$history->item->product->thumbnailImage->url)
                                            <img src="{{ $history->item->product->thumbnailImage->url }}" alt="">
                                        @else
                                            <img src="{{ asset('front/images/pro-1.png') }}" alt="">
                                        @endif
                                    </div>
                                        <p>{{ $history->item->product->name }}</p>
                                    </div>
                                </td>
                                <td>{{ $history->created_at->format('M d, Y') }}</td>
                                <td>${{$history->total}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="width:100%;">
                                    <h3>Payment history not found</h3>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="custom-pagination">{{ $payment_history->links('pagination::product-list') }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
