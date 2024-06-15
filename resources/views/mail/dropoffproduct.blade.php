@extends('layouts.email')
{{-- @section('title', 'Chere Confirmation') --}}

@section('greet', "Hello, $name")

@section('message')
    {{-- @dd($product); --}}
    <p>We hope you are loving your rental!</p>
    <p>Just a gentle reminder that you are scheduled to @if ($number == 1)
            dropoff
        @else
            receive
        @endif {{ $data['product_name'] }}
        @if ($number == 1)
            from {{ $data['retailer'] }}
        @else
            to {{ $data['name'] }}
        @endif
        at
        {{ $data['location'] }} on {{ $data['rental_date'] }}
    </p>
    <p>Happy Chering!</p>

@endsection

@section('wishes', 'With love,')
