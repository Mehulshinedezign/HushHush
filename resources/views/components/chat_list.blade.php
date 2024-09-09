<div class="chat-list-box">
    <p>Messages</p>
    <form class="" id="searchmember" role="search">
        <div class="form-group">
            <div class="formfield search-field">
                <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                    aria-describedby="search-addon" name="search" value="" autocomplete="off">
            </div>
        </div>
    </form>
    <ul>
        {{-- @foreach ($chatlist as $chat) --}}
        <div class="chatlist">
            {{-- <li>

                <div class="chat-list-profile @if ($loop->first) activecht @endif"
                    data-orderId="{{ jsencode_userdata(@$chat->order_id) }}" data-chatId="{{ @$chat->chatid }}"
                    data-receiverId=@if (@$chat->retailer_id == auth()->user()->id) {{ @$chat->customer->id }} @else {{ @$chat->retailer->id }} @endif
                    data-id="{{ @$chat->id }}">
                    <div class="chat-profile-img-box">
                        <div class="chat-profile-img">
                            @if (@$chat->retailer->id == auth()->user()->id)
                                @if (@$chat->customer->profile_file)
                                    <img src="{{ Storage::url(@$chat->customer->profile_file) }}">
                                @else
                                    <img src="{{ asset('img/avatar-small.png') }}">
                                @endif
                            @else
                                @if (@$chat->retailer->profile_file)
                                    <img src="{{ Storage::url(@$chat->retailer->profile_file) }}">
                                @else
                                    <img src="{{ asset('img/avatar-small.png') }}">
                                @endif
                            @endif
                        </div>
                        <p>
                            @if (@$chat->retailer->id == auth()->user()->id)
                                {{ @$chat->customer->name }}
                            @else
                                {{ @$chat->retailer->name }}
                            @endif
                        </p>
                    </div>
                    <span>{{ date('h:i a', strtotime(@$chat->last_msg_datetime)) }}</span>
                </div>
            </li> --}}
        </div>
        {{-- @endforeach --}}
    </ul>
</div>
