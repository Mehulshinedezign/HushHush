@extends('layouts.retailer')

@section('title', 'Dashboard')

@section('content')
<div class="right-content">
    <div class="d-flex justify-content-between">
        <h2 class="heading_dash">Dashboard</h2>
    </div>
    <div class="box_area">
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon4.svg') }}"> </div>
            <div class="contentbx"> <span>Total items added for rent</span>
                <h3>{{ $data['totalOrdersCount'] }}</h3>
            </div>
        </div>
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon2.svg') }}"> </div>
            <div class="contentbx"> <span>Total completed bookings</span>
                <h3>{{ $data['completedOrder'] }}</h3>
            </div>
        </div>
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon3.svg') }}"> </div>
            <div class="contentbx"> <span>Total active bookings</span>
                <h3>{{ $data['totalactivebooking'] }}</h3>
            </div>
        </div>
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon4.svg') }}"> </div>
            <div class="contentbx"> <span>Total revenue generated</span>
                <h3>${{ $data['totalRevenue'] }}</h3>
            </div>
        </div>
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon5.svg') }}"> </div>
            <div class="contentbx"> <span>Total Product</span>
                <h3>{{ $data['totalProductsCount'] }}</h3>
            </div>
        </div>
        <div class="box_cstm">
            <div class="iconbx"> <img src="{{ asset('front/images/bxicon5.svg') }}"> </div>
            <div class="contentbx"> <span>Total Customers</span>
                <h3>{{ $data['totalCustomersCount'] }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
@if (session()->has('msg'))
<script>
    var success = "{{ session()->get('msg') }}";
    swal(success, "You clicked the button!", "success")
</script>
@endif
@endpush