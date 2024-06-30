@extends('layouts.front')
@section('content')
    {{-- <section>
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
                            @if (count($payment_history) > 0)
                            @foreach ($payment_history as $history)
                            <tr>
                                <td>
                                    <div class="pro-img-name">
                                    <div class="pro-tab-image">
                                        @if (@$history->item->product->thumbnailImage->url)
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
    </section> --}}
    <section class="rental-request-bx">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="payment-history-haed">
                    <h2>My Order History</h2>
                    <div class="form-group m-0">
                        <div class="formfield">
                            <select name="" id="" class="form">
                                <option value="">Past 2 Months</option>
                                <option value="">Past 5 Months</option>
                                <option value="">Past 1 Year</option>
                            </select>
                            <span class="form-icon">
                                <img src="{{ asset('front/images/dorpdown-icon.svg') }}" alt="dorpdown" width="13">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="payment-history-table-main">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>Product Name</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="user-table-profile">
                                        <div class="table-profile ">
                                            <img src="{{ asset('front/images/table-profile1.png') }}" alt="tb-profile"
                                                width="26" height="27">
                                        </div>
                                        <div class="user-table-head">
                                            <h5>Pennington Dress</h5>
                                        </div>
                                    </a>
                                </td>
                                <td>Jul 18, 2023</td>
                                <td>Invoice</td>
                                <td>$156</td>
                                <td>
                                    <a href="#" class="download-payment-his">
                                        <img src="{{ asset('front/images/download-icon.svg') }}" alt="">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="pagination-main">
                    <a href="javascript:void(0)" class="pagination-box">
                        01
                    </a>
                    <a href="javascript:void(0)" class="pagination-box">
                        02
                    </a>
                    <a href="javascript:void(0)" class="pagination-box active">
                        03
                    </a>
                    <a href="javascript:void(0)" class="pagination-box">
                        04
                    </a>
                    <a href="javascript:void(0)" class="pagination-box">
                        05
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
