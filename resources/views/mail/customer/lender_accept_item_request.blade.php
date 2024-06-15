@extends('layouts.email')

@section('title', '')

@section('greet', "Hello, $user->name")

@section('message')
    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">{{ $data['product']['retailer']['name'] }} approved your
        rental request! Your rental for
        {{ $data['product']['name'] }} is confirmed.</p>
    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">Order details:
        <img src="{{ $data['product']['thumbnailImage']['url'] }}" alt="image" width="50px" />
        rental period : {{ $data['order']['from_date'] . ' to ' . $data['order']['to_date'] }}
        price : {{ $data['order_item']['total'] }}
    </p>
    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">Be sure to communicate with the lender to coordinate a pick-up
        and drop-off.</p>
@endsection

@section('wishes', 'With love,')
