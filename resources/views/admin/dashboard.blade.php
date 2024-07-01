@extends('layouts.admin')
@section('content')
    <section class="section dashboard-wrap">
        <div class="row justify-content-end mb-4">
            <div class="col-md-12">
                <div class="fillter-mainbox">
                    <div class="date-range p-relative dash-date position-relative">
                        <span class="icon_box">
                            <svg width="16" height="19" viewBox="0 0 16 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.20312 10.375C5.41406 10.375 5.625 10.1992 5.625 9.95312V8.54688C5.625 8.33594 5.41406 8.125 5.20312 8.125H3.79688C3.55078 8.125 3.375 8.33594 3.375 8.54688V9.95312C3.375 10.1992 3.55078 10.375 3.79688 10.375H5.20312ZM9 9.95312V8.54688C9 8.33594 8.78906 8.125 8.57812 8.125H7.17188C6.92578 8.125 6.75 8.33594 6.75 8.54688V9.95312C6.75 10.1992 6.92578 10.375 7.17188 10.375H8.57812C8.78906 10.375 9 10.1992 9 9.95312ZM12.375 9.95312V8.54688C12.375 8.33594 12.1641 8.125 11.9531 8.125H10.5469C10.3008 8.125 10.125 8.33594 10.125 8.54688V9.95312C10.125 10.1992 10.3008 10.375 10.5469 10.375H11.9531C12.1641 10.375 12.375 10.1992 12.375 9.95312ZM9 13.3281V11.9219C9 11.7109 8.78906 11.5 8.57812 11.5H7.17188C6.92578 11.5 6.75 11.7109 6.75 11.9219V13.3281C6.75 13.5742 6.92578 13.75 7.17188 13.75H8.57812C8.78906 13.75 9 13.5742 9 13.3281ZM5.625 13.3281V11.9219C5.625 11.7109 5.41406 11.5 5.20312 11.5H3.79688C3.55078 11.5 3.375 11.7109 3.375 11.9219V13.3281C3.375 13.5742 3.55078 13.75 3.79688 13.75H5.20312C5.41406 13.75 5.625 13.5742 5.625 13.3281ZM12.375 13.3281V11.9219C12.375 11.7109 12.1641 11.5 11.9531 11.5H10.5469C10.3008 11.5 10.125 11.7109 10.125 11.9219V13.3281C10.125 13.5742 10.3008 13.75 10.5469 13.75H11.9531C12.1641 13.75 12.375 13.5742 12.375 13.3281ZM15.75 4.1875C15.75 3.27344 14.9766 2.5 14.0625 2.5H12.375V0.671875C12.375 0.460938 12.1641 0.25 11.9531 0.25H10.5469C10.3008 0.25 10.125 0.460938 10.125 0.671875V2.5H5.625V0.671875C5.625 0.460938 5.41406 0.25 5.20312 0.25H3.79688C3.55078 0.25 3.375 0.460938 3.375 0.671875V2.5H1.6875C0.738281 2.5 0 3.27344 0 4.1875V16.5625C0 17.5117 0.738281 18.25 1.6875 18.25H14.0625C14.9766 18.25 15.75 17.5117 15.75 16.5625V4.1875ZM14.0625 16.3516C14.0625 16.4922 13.957 16.5625 13.8516 16.5625H1.89844C1.75781 16.5625 1.6875 16.4922 1.6875 16.3516V5.875H14.0625V16.3516Z"
                                    fill="#1372E6"></path>
                            </svg>
                        </span>
                        <div class="date-range-input">
                            <input type="text" id="daterange" class="report-range w-100">
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary filter">
                        <svg class="mr-2" width="19" height="15" viewBox="0 0 19 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19 1.34603C19 2.09183 18.3817 2.69429 17.6163 2.69429H1.38374C0.618316 2.69429 0 2.09183 0 1.34603C0 0.602468 0.618316 6.20119e-06 1.38374 6.20119e-06H17.6163C18.3817 -0.00223343 19 0.602468 19 1.34603Z"
                                fill="white" />
                            <path
                                d="M16.7017 7.01428C16.7017 7.75783 16.0834 8.36254 15.3179 8.36254H3.68257C2.91714 8.36254 2.29883 7.75783 2.29883 7.01428C2.29883 6.27072 2.91714 5.66602 3.68257 5.66602H15.3179C16.0834 5.66602 16.7017 6.26848 16.7017 7.01428Z"
                                fill="white" />
                            <path
                                d="M13.2552 12.6808C13.2552 13.4266 12.6369 14.029 11.8714 14.029H7.13179C6.36636 14.029 5.74805 13.4266 5.74805 12.6808C5.74805 11.9372 6.36636 11.3325 7.13179 11.3325H11.8714C12.6369 11.3325 13.2552 11.935 13.2552 12.6808Z"
                                fill="white" />
                        </svg>Apply
                    </button>
                    <button type="button" class="btn btn-dark clear">Clear Filter</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a href="{{ route('admin.customers') }}">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15"> Total Users</h5>
                                            <h2 class="mb-3 font-18">{{ $users->count() }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('assets/img/banner/2.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Total Commission</h5>
                                        <h2 class="mb-3 font-18">
                                            ${{ number_format($order_commission_amount, '2', '.', '') }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="{{ asset('assets/img/banner/4.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Avg. Products/Lender</h5>
                                        @if ($products->count() != 0 && $users->where('role_id', '2')->count() != 0)
                                            <h2 class="mb-3 font-18">
                                                {{ round($products->count() / $users->where('role_id', '2')->count()) }}
                                            </h2>
                                        @else
                                            <h2 class="mb-3 font-18">0</h2>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="{{ asset('assets/img/banner/1.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Total Sales Amount</h5>
                                        <h2 class="mb-3 font-18">${{ number_format($totalOrderAmount, '2') }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="{{ asset('assets/img/banner/4.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a href="{{ route('admin.orders') }}">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Products Rented</h5>
                                            <h2 class="mb-3 font-18">{{ $orders->count() }}</h2>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="{{ asset('assets/img/banner/1.png') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const date = '{{ request()->get('date') }}';
        //console.log("data",data.split('to')[0]);
        var currentUrl = new URL(window.location.href);
        var setDateRange = '';
        jQuery(function() {
            jQuery('button.filter').click(function() {
                setDateRange = jQuery('#daterange').val();
                currentUrl.searchParams.set("date", setDateRange);
                window.location.replace(currentUrl.href)
            });

            jQuery('button.clear').click(function() {
                currentUrl.searchParams.delete("date");
                window.location.replace(currentUrl.href)
            });

            if (date) {
                var start = date.split('to')[0];
                var end = date.split('to')[1];
            } else {
                var start = moment();
                var end = moment();
            }
            jQuery('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                locale: {
                    format: dateFormat,
                    separator: dateSeparator,
                },
            }).on('change', function() {
                // setDateRange = jQuery('#daterange').val();
                // currentUrl.searchParams.set("date", setDateRange);
                // window.location.replace(currentUrl.href)
            });
        })
    </script>
@endpush
