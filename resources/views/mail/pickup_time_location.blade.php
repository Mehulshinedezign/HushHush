@extends('layouts.email')
{{-- @section('title', 'Hotness pending') --}}

@section('greet', "Hello, $name")

@section('message')

    <p>We hope you are excited for your rental.</p>

    <p>Just a gentle reminder that you are scheduled to
        @if ($number == 1)
            pick up
        @else
            hand off
        @endif

        {{ $data['product_name'] }}
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
