@if (count($chatlist) > 0)
    {{-- @if ($type == 'retailer') --}}
    @foreach ($chatlist as $chat)
        <div class="d-flex align-items-center bx_cht chat-list @if ($loop->first) activecht @endif"
            data-orderId={{ jsencode_userdata(@$chat->order_id) }} data-chatId="{{ @$chat->chatid }}"
            data-receiverId=@if (@$chat->retailer->id == auth()->user()->id) {{ @$chat->customer->id }} @else {{ @$chat->retailer->id }} @endif
            data-id="{{ @$chat->id }}">
            {{-- <div class="d-flex align-items-center bx_cht chat-list"> --}}
            <div class="pprofile">
                @if (@$chat->retailer->id == auth()->user()->id)
                    @if (@$chat->customer->profile_url)
                        <img src="{{ @$chat->customer->profile_url }}">
                    @else
                        <img src="{{ asset('img/avatar-small.png') }}">
                    @endif
                @else
                    @if (@$chat->retailer->profile_url)
                        <img src="{{ @$chat->retailer->profile_url }}">
                    @else
                        <img src="{{ asset('img/avatar-small.png') }}">
                    @endif
                @endif
            </div>
            <div class="d-flex pprofile_text w-100 justify-content-between">
                <div class="chat-title">
                    <p>
                        @if (@$chat->retailer->id == auth()->user()->id)
                            {{ @$chat->customer->name }}
                        @else
                            {{ @$chat->retailer->name }}
                        @endif
                    </p>
                    {{-- <small>{{ @$chat->product->name }}</small> --}}
                </div>
                {{-- <span class="chttime">{{ date('h:i a', strtotime($chat->chat->last_msg_datetime)) }}</span> --}}
            </div>
        </div>
        {{-- </div> --}}
    @endforeach
@else
    <div class="d-flex align-items-center bx_cht">
        <h5>
            @if (auth()->user()->role_id == '2')
                Lenders
            @else
                Users
            @endif not found
        </h5>
    </div>
@endif
