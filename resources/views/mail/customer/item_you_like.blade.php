@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $user->name ")

@section('message')

    <p>We've found some items we think you might like.</p>

    @foreach ($product as $key => $data)
        <p><a style="color: #000; display:flex; flex-direction:column-reverse; gap:10px; font-size: 16px;"
                href="{{ route('wishlist') }}">{{ @$data['product']['name'] }} <img width="50px"
                    src={{ @$data['product']['thumbnailImage']['url'] }} alt="image"></a>
            {{-- <a href="{{ route('wishlist') }}">{{ @$data['product']['name'] }}</a> --}}
        </p>
    @endforeach


@endsection

@section('wishes', 'With love,')
