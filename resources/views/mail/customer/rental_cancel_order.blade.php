@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $customer->name ")

@section('message')

    <p>Oh no! We are sorry to share that {{ $data['product']['retailer']['name'] }} has cancelled their request for
        {{ Str::lower($data['product']['name']) }}.</p>
    <p>We apologize for any inconvenience this may have caused. We have released the request dates so that other users can
        rent the item.</p>
    <p>Let us know if you have any questions, comments or concerns.</p>

@endsection

@section('wishes', 'With love,')
