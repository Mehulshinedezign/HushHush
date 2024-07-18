@extends('layouts.front')
@section('title', 'Chat')
@section('content')
    <section class="chat-sec">
        <div class="container">
            <div class="chat-wrapper">
                <h2>Chat Customer Service</h2>
                @include('components.chat_outer')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script defer src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script defer src="https://www.gstatic.com/firebasejs/8.10.1/firebase-firestore.js"></script>
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
    <script defer src="{{ asset('js/custom/chat.js') }}"></script>
@endpush
