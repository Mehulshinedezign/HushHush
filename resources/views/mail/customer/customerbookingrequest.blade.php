@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $user->name ")

@section('message')

    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">Your rental request for {{ $data['product']['name'] }} has
        been sent to {{ $data['product']['retailer']['name'] }}!
    </p>

    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">The next step is for the lender to confirm. We will send a
        separate email confirmation when that happens.</p>

    <p style="font-family: 'Inter', sans-serif;color:#1B1B1B; display:flex; flex-direction-coloumn; gap:5px;">
    <P>Request details:</P>
    <img src="{{ $data['product']['thumbnailImage']['url'] }}" alt="image" width="50px" />
    <P>rental period : {{ $data['order']['from_date'] . ' to ' . $data['order']['to_date'] }}</P>
    <P>price : {{ $data['order_item']['total'] }}</P>
    </p>
@endsection

@section('wishes', 'With love,')
