<div class="chat-outer">
    @include('components.chat_list', ['chatlist' => $chatlist])
    @include('components.chat_msg', ['query' => $query])
</div>
