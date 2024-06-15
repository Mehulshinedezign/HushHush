@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $retaileruser->name ")

@section('message')
    {{-- @dd($product); --}}
    <p>Congratulations! You have confirmed your lease of {{ $data['product']['name'] }} and will be earning
        price : {{ $data['order_item']['total'] }},
    </p>
    <p>Order details:
        <img src="{{ $data['product']['thumbnailImage']['url'] }}" alt="image" width="50px" />, rental
        period :
        {{ $data['order']['from_date'] . ' to ' . $data['order']['to_date'] }}
    </p>
    <p>Be sure to communicate with the lender to coordinate a pick-up and drop-off.</p>
    <p>Happy Chering!</p>

@endsection

@section('wishes', 'With love,')
