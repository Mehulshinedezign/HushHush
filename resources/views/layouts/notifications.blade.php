<div class="notifications-fixed-section">
    <div class="massage-box">
        <div class="notifications-heading">
            <h5>Notifications</h5>
            <button type="button"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <ul class="info-list">
            @foreach ($notifications as $notification)
                <li>
                    <span class="noti-img">
                        @if (isset($notification->order->item->product->thumbnailImage->url))
                            <img src="{{ $notification->order->item->product->thumbnailImage->url }}" class="round-img">
                        @else
                            <img src="{{ asset('img/default-product.png') }}" class="round-img">
                        @endif
                    </span>
                    <div class="noti-about">
                        <h6><b>Order #{{ $notification->order_id }}</b> {{ $notification->message }}</h6>
                        <p class="listing-title ">
                            @if (!is_null($notification->created_at))
                                {{ $notification->created_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                </li>
            @endforeach
            {{-- <li>
                <span class="noti-img">
                    <img src="./images/pro-1.png" alt="profile-picture">
                </span>
                <div class="noti-about">
                    <h6>Cancellation reason<span class="cancellation"><i class="fa-solid fa-xmark"></i></span>
                    </h6>
                    <div class="pra-info">Accurately reflects your reason for canceling the dress booking, you
                        can use this as the reason when talk with the dress booking.</div>
                    <p class="listing-title ">15 min ago</p>
                </div>
            </li> --}}
        </ul>
    </div>
</div>
