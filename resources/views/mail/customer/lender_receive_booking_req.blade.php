@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $lender->name")

@section('message')

    <p>{{ $user->name }} wants to borrow {{ $data['product']['name'] }} from your closet!</p>
    <p>Approve, decline, or chat with the user now <a href="{{ route('retailercustomer') }}">here</a></p>

    <p>Request details:
        <img src="{{ $data['product']['thumbnailImage']['url'] }}" alt="image" width="50px"> , rental period :
        {{ $data['order']['from_date'] . ' to ' . $data['order']['to_date'] }}, and price :
        {{ $data['order_item']['total'] }}
    </p>

@endsection

@section('wishes', 'With love,')
