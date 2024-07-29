<div class="chat-msg-box">
    <div class="chat-product-box">
        <div class="chat-product-profile">
            <img src="images/notify-img.png" alt="chat-img" class="chat-pro-img">
            <div class="chat-product-info">
                <h4>Pennington Dress</h4>
                <div class="pro-desc-prize">
                    <h3>$156</h3>
                    <div class="badge day-badge">
                        Per day
                    </div>

                </div>
            </div>
        </div>
        <div class="dropdown">
            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </div>
    </div>
    <div class="chat-screen">
        {{-- <p>Today, August 7</p> --}}
        <div class="chat-screen-area">
            <div class="msg_wrap" id="chatWindow"></div>

            {{-- <div class="chat-screen-left-wrapper">
                <div class="chat-screen-img">
                    <img src="asset(images/notify-img.png)" alt="">
                </div>
                <div class="chat-screenmsg-wrapper">
                    <div class="chat-screen-name-time">
                        <p>Desirae Vaccaro</p>
                        <span>02:30 PM</span>
                    </div>
                    <div class="chat-txt-box">
                        <p>What colors & styles are trending this season?</p>
                    </div>
                    <div class="chat-img-box">
                        <img src="images/style-single.png" alt="">
                    </div>
                </div>
            </div>
            <div class="chat-screen-right-wrapper">

                <div class="chat-screenmsg-wrapper">
                    <div class="chat-screen-name-time">
                        <span>02:30 PM</span>
                        <p>You</p>

                    </div>
                    <div class="chat-txt-box">
                        <p>Pink Color</p>
                    </div>
                </div>
                <div class="chat-screen-img">
                    <img src="images/notify-img.png" alt="">
                </div>
            </div> --}}
        </div>
    </div>
    <form id="chatForm" class="chatwindowform">
        <div class="chat-text-area-field">
            <textarea class="commentarea" id="message" data-senderId="{{ auth()->user()->id }}"
                data-receverId="{{ $query->user_id }}" placeholder="Type a Message"></textarea>
            <div class="upload-img-chat">
                <label for="upload-img">
                    <img src="images/img-upload-svg.svg" alt="">
                    {{-- <input type="file" id="attachment" /> --}}
                </label>
            </div>
    </form>

    <button type="submit" id="sendMessage" class="btn primary-btn send-msg-btn send_btn">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12.2981 5.01829L6.76088 8.86273L0.969235 6.93193C0.564968 6.79689 0.292611 6.4176 0.294937 5.99148C0.297293 5.56536 0.572773 5.1884 0.9786 5.05808L15.7859 0.289603C16.1378 0.176454 16.5242 0.269311 16.7856 0.530773C17.0471 0.792235 17.1399 1.17854 17.0268 1.53053L12.2583 16.3378C12.128 16.7436 11.751 17.0191 11.3249 17.0214C10.8988 17.0238 10.5195 16.7514 10.3844 16.3471L8.44427 10.5274L12.2981 5.01829Z"
                    fill="white" />
            </svg>
        </span>
    </button>
</div>
</div>
