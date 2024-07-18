@extends('layouts.front')
@section('title', 'Chat')
@section('content')
    {{-- <div class="right-content innerpages"> --}}
    <div class="container">
        <h2 class="heading_dash"></h2>
        <div class="wrap_profile">
            <div class="message-wrap d-flex w-100">
                <div class="chat_people">
                    <h3>Messages</h3>
                    <form class="" id="searchmember" role="search">
                        <div class="input-group">
                            <span class="input-group-icon border-0" id="search-addon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="search" class="form-control" placeholder="Search" aria-label="Search"
                                aria-describedby="search-addon" name="search" autocomplete="off">
                        </div>
                    </form>
                    <div class="cht_people" id="chatlist">
                        @include('common-chat-list', [
                            'chatlist' => $chatlist,
                            // 'order' => $order,
                            // 'mergedArray' => $mergedArray,
                        ])
                    </div>
                </div>
                <div class="chat_area">
                    <div class="wrapcht">
                        <div class="boxcht chatwindowheader d-none" id="chatUser"></div>
                        <div class="message_area">
                            <div class="msg_wrap" id="chatWindow"></div>
                            {{-- <div class="chatwindowimage">
                                <img src="{{ asset('img/Mask group.png') }}" alt="" height="400px" width="400px">
                                <h3>Select Chat</h3>
                            </div> --}}
                            <form id="chatForm" class="chatwindowform d-none">
                                <div class="tying_area d-flex">
                                    <div class="left_type_sec">
                                        <textarea class="commentarea" id="message" placeholder="Type a Message"></textarea>
                                        <label class="custom-file-upload icon_type" for="attachment">
                                            <input type="file" id="attachment" />
                                            <img src="{{ asset('front/images/media_msg.svg') }}">
                                        </label>
                                    </div>
                                    <div class="right_type_sec">
                                        <button type="submit" id="sendMessage" class="send_btn btn"><i
                                                class="fa-solid fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
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
