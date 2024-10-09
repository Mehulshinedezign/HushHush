@extends('layouts.email')
@section('title', '')

@section('greet', "Hello, $user->name")

@section('message')
    <p>{{ $data['renter']['name'] }} has sent you a message.</p>
    <p><a href="{{ route('retalerorderchat', [$data['chats']['order_id']]) }}" style="color:black">Click here to access the
            chat.</a></p>

@endsection

@section('wishes', 'With love,')
