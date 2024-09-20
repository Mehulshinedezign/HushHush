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
@push('scripts')
    {{-- <script defer src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script defer src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "{{ env('APIKEY') }}",
            authDomain: "{{ env('AUTHDOMAIN') }}",
            databaseURL: "{{ env('DATABASEURL') }}",
            projectId: "{{ env('PROJECTID') }}",
            storageBucket: "{{ env('STORAGEBUCKET') }}",
            messagingSenderId: "{{ env('MESSAGINGSENDERID') }}",
            appId: "{{ env('APPID') }}",
            measurementId: "{{ env('MEASUREMENTID') }}"
        };
        var senderId = "{{ auth()->user()->id }}";
        var userImage = "{{ route('retaileruserimage') }}";
        var imagePath = "{{ asset('storage/') }}";
        var chat_store_url = "{{ route('retailerstore.chat') }}";
        var get_chat_url = APP_URL + '/retailer/chat/messages';
        var get_user_names = APP_URL + '/retailer/getuser/names';
        var last_msg_update_url = APP_URL + '/retailer/lastchat/update';
        var search_url = APP_URL + '/retailer/chat/search/';
        var chat_image_store_url = APP_URL + '/retailer/chat/image';
    </script>
    <script defer src="{{ asset('js/custom/chat2.js') }}"></script>
    <script defer src="{{ asset('js/custom/chatlist.js') }}"></script> --}}

    {{-- <script type="module">
        import {
            database
        } from './js/test.js';

        // function sendMessage() {
        //     const message = document.getElementById('message').value;
        //     const newMessageRef = database.ref('messages').push();
        //     newMessageRef.set({
        //         text: message
        //     });
        //     document.getElementById('message').value = '';
        // }
        database.ref('\users/3').child('11').set({

            id: "3",
            name: "CHUTIYA",
            image: "URL",
            lastmsg: "LUND LELE MERA",
            created: "timestamp",
            sender: "2",
            isSeen: '1',
            a_remove: '0',
            msgtype: 'one',
        });
        // database.ref('\users').on('child_added', (snapshot) => {
        //     const message = snapshot.val();
        //     const messagesDiv = document.getElementById('messages');
        //     messagesDiv.innerHTML += `<p>${message.text}</p>`;
        // });
    </script> --}}
@endpush
