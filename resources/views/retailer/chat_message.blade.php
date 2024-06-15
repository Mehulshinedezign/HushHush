<div class="msgBox sender">
    <div class="chat-img">
        @if(isset(auth()->user()->profile_pic_url))
            <img src="{{ auth()->user()->profile_pic_url }}">
        @else
            <img src="{{ asset('img/avatar-small.png') }}">
        @endif
    </div>
    <div class="receiver-msg-container">
        @if(!is_null($chat->message))
            <div class="receiver-msg">
                <div class="msg-text sender-text">
                    <p>{!! nl2br($chat->message) !!}</p>
                </div>
            </div>
        @endif
        @if(!is_null($chat->url))
            <div class="uploaded-file">
                <img src="{{ $chat->url }}" alt="uploaded file"/>
                <a href="{{ route('retailer.downloadchatattachment', [$order->id, $chat->id]) }}" target="_blank">
                    <span class="download-file-icon"><span class="icon-box small-icon"><i class="fas fa-arrow-down"></i></span></span>
                </a>
            </div>
        @endif
        <span class="msg-time">now</span>
    </div>
</div>