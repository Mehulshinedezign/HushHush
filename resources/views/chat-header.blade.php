<div class="bookfwrap mt-0 align-items-center d-flex">
    <div class="back-chatppl"><a href="javascript:void(0);" class="backcht"><i class="fa-solid fa-chevron-left"></i></a>
    </div>
    <div class="pro-tab-image">
        @if (@$chatheader->product->thumbnailImage->url)
            <img src="{{ @$chatheader->product->thumbnailImage->url }}" alt="">
        @else
            <img src="{{ asset('front/images/pro-1.png') }}" alt="">
        @endif
    </div>
    <div class="ptext-book">
        <h3>{{ @$chatheader->product->name }}</h3>
        <p>${{ @$chatheader->product->rent }} <span>/day</span></p>
    </div>
</div>
{{-- <div class="msg_toggle"><div class="toggle"> <span class="line-toggle"></span> <span class="line-toggle"></span> <span class="line-toggle"></span> </div></div> --}}
{{-- <div class="action_cht">
    <a href="javascript: void(0);"><img src="images/action.svg"></a>
</div> --}}
