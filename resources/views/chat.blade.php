@extends('layouts.front')
@section('title', 'Chat')
@section('content')
    <section class="chat-sec">
        <div class="container">
            <div class="chat-wrapper">
                <h2>Chat </h2>
                @include('components.chat_outer')
            </div>
        </div>
    </section>
@endsection
@section('custom_variables')
<script>
    const sel_reciever = "{{request()->has('reciever_id') ?request()->reciever_id :''}}";
    const sel_sender = "{{auth()->id()}}";
</script>
@endsection

