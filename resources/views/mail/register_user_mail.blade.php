@extends('layouts.email')

@section('greet', "Hello, $data->name")

@section('message')
    {{-- @dd($product); --}}
    <p>Have any clothes, shoes, or accessories lying dormant in your closet? Want to start making a passive income?</p>
    <p><button><a href="{{ route('index') }}">Start Chering Now</a></button></p>
@endsection

@section('wishes', 'With love,')
