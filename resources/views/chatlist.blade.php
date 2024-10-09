@if (count($chatlist) > 0)
    @if ($type == 'retailer')
        @foreach ($chatlist as $chat)
            <div class="d-flex align-items-center bx_cht chat-list @if (isset($order) && $order->id == $chat->order_id) activecht @endif"
                data-orderId={{ jsencode_userdata(@$chat->order_id) }} data-chatId="{{ @$chat->chat->chatid }}"
                data-receiverId={{ @$chat->customer_id }} data-id="{{ @$chat->chat->id }}">
                <div class="pprofile">
                    @if (@$chat->customer->frontend_profile_url)
                        <img src="{{ @$chat->customer->frontend_profile_url }}">
                    @else
                        <img src="{{ asset('front/img/avatar-small.png') }}">
                    @endif
                </div>
                <div class="d-flex pprofile_text w-100 justify-content-between">
                    <div class="chat-title">
                        <p>{{ @$chat->customer->name }}</p>
                        <small>{{ @$chat->product->name }}</small>
                    </div>
                    {{-- <span class="chttime">{{ date('h:i a', strtotime($chat->chat->last_msg_datetime)) }}</span> --}}
                </div>
            </div>
        @endforeach
    @else
        @foreach ($chatlist as $chat)
            <div class="d-flex align-items-center bx_cht chat-list @if (isset($order) && @$order->id == $chat->order_id) activecht @endif "
                data-orderId={{ jsencode_userdata(@$chat->order_id) }} data-chatId="{{ @$chat->getchat->chatid }}"
                data-receiverId={{ @$chat->retailer_id }} data-id="{{ @$chat->getchat->id }}">

                <div class="pprofile">
                    @if (@$chat->retailer->frontend_profile_url)
                        <img src="{{ @$chat->customer->frontend_profile_url }}">
                    @else
                        <img src="{{ asset('front/img/avatar-small.png') }}">
                    @endif
                </div>

                <div class="d-flex pprofile_text w-100 justify-content-between">
                    <div class="chat-title">
                        <p>{{ @$chat->retailer->name }}</p>
                        <small>{{ @$chat->product->name }}</small>
                    </div>
                    {{-- <span class="chttime">{{ date('h:i a', strtotime($chat->getchat->last_msg_datetime)) }}</span> --}}
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="d-flex align-items-center bx_cht">
        <h5>
            @if (auth()->user()->role_id == '2')
                Lenders
            @else
                Borrowers
            @endif not found
        </h5>
    </div>
@endif
