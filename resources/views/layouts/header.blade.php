@extends('layouts.add_product')
{{-- <style>
    .cstm-select-bx {
        position: relative;
    }

    .select-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 8px;
        cursor: pointer;
        /* min-width: 200px; */
        background-color: #fff;
    }

    .select-btn .btn-text {
        font-size: 14px;
        font-weight: 400;
    }

    .list-items {
        position: absolute;
        margin-top: 15px;
        padding: 16px;
        background-color: #fff;
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
        display: none !important;
        top: 26px;
    }

    .select-btn.open~.list-items {
        display: flex !important;
        flex-direction: column;
    }

    /* Always show the scrollbar of the dropdown */
    .select-btn.open~.list-items::-webkit-scrollbar {
        width: 8px;
        height: 0;
    }

    .select-btn.open~.list-items::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, .2);
        border-radius: 8px;
    }

    .select-btn.open~.list-items::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, .3);
    }


    .itemneighbor {
        padding-left: 13px !important;
    }



    .item .item-text {
        font-size: 16px;
        font-weight: 400;
        color: #333;
    }

    .item .checkbox {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 16px;
        width: 16px;
        min-width: 16px;
        margin-top: 3px;
        border-radius: 4px;
        margin-right: 8px;
        border: 1.5px solid #c0c0c0;
        transition: all 0.3s ease-in-out;
    }

    .item.checked .checkbox {
        background-color: #000;
        border-color: #000;
    }

    .cstm-select-bx .check {
        width: unset;
        border: 2px solid #dee0e3;
        max-width: 20px;
        height: 20px;
        min-width: 20px;
        padding: 000000;
    }

    .cstm-select-bx .check:checked:before {
        left: 2px;
        top: 2px;
        font-size: 11px;
        width: 13px;
        height: 12px;
    }

    .cstm-select-bx .checkbox-condition-field {
        align-items: flex-start;
    }

    .cstm-select-bx label {
        color: var(--text-color) !important;
        font-size: 15px;
        margin: 0;
        width: 100%;
    }



    .cutm-dropdown-inner {
        width: 100%;
    }

    ul.dropdown-menu.dropdown-menu-left.show {
        transform: translate3d(0px, 5px, 0px) !important;
    }

    button#dropdownMenuButton1 {
        border: none;
    }


    select::-ms-expand {
        display: none;
    }
</style>
<header class="cust-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <div class="cust-nav-header-sec">

                <div class="logo mobile-logo">
                            <a href="{{url('/')}}" style="font-size: 20px;color:#000">NUDORA</a>
</div>
<div class="txt-btn-header-bx">
    <div class="d-inline-block d-lg-none txt-btn-header-inner">
        <p class="open-product-popup">Lend Your Closet </p>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <div class="toggle"> <span class="line-toggle"></span> <span class="line-toggle"></span> <span class="line-toggle"></span> </div>
    </button>
</div>

<ul class="navbar-nav">

    <li class="nav-item">
        <form class="filter-form" id="filterForm" autocomplete="off">
            <div class="search-pro-header">
                <ul>
                    <li>
                        <div class="cstm-select-bx">
                            <div class="select-btn">
                                <span class="btn-text">Where</span>

                            </div>


                            <ul class="list-items">
                                <div class="cutm-dropdown-inner">
                                    @foreach (headerneighborhoodcity() as $val => $city)
                                    <div class="checkbox-condition-field">
                                        <input type="checkbox" class="check item parent" id="itemcity{{ $city->name }}" data-id="parent{{ $city->id }}" name="neighborhoodcity[]" value="{{ $city->id }}" @if (isset($filters) && in_array($city->id, $filters['neighborhoodcity'])) checked @endif>
                                        <label for="itemcity{{ $city->name }}">{{ $city->name }}</label>
                                    </div>
                                    @foreach (headerneighborhood($city->id) as $i => $neighborhood)
                                    <div class="checkbox-condition-field itemneighbor child{{ $city->id }}">
                                        <input type="checkbox" class="check item child{{ $city->id }}" id="itemneighbor{{ $neighborhood->name }}" name="neighborhoodcity[]" value="{{ $neighborhood->id }}" @if (isset($filters) && in_array($neighborhood->id, $filters['neighborhoodcity'])) checked @endif>
                                        <label for="itemneighbor{{ $neighborhood->name }}">{{ $neighborhood->name }}</label>
                                    </div>
                                    @endforeach
                                    @endforeach


                            </ul>
                        </div>
                    <li>
                        <input type="text" name="reservation_date" class="form-control" id="reservation_date" placeholder="When" onfocus="initDateRangePicker(this, dateOptions)" value="{{ request()->get('reservation_date') }}">
                    </li>
                    <li>
                        <button type="submit" class="primary-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>Search
                        </button>
                    </li>

            </div>
        </form>
    </li>
    <li>
        <button type="button" class="secondary-btn" data-bs-toggle="modal" data-bs-target="#home-filter"><i class="fa-solid fa-sliders"></i>Filter</button>
    </li>
</ul>
<div class="collapse navbar-collapse cust-navbar-header" id="navbarNav">
    <div class="header-cart">
        <ul>
            <li>
                <p class="open-product-popup d-none d-lg-block">Lend Your Closet </p>
            </li>
            @auth

            <li><a href="{{ route('wishlist') }}"><i class="fa-regular fa-heart"></i>
                    <p class="d-inline-block d-lg-none">Wishlist</p>
                </a></li>
            @endauth
            <li><a href="javascript: void(0);" class="user-profile d-none d-lg-inline-block"><i class="fa-regular fa-user"></i>
                    <p class="d-inline-block d-lg-none">Profile</p>
                </a>
                @auth
                <div class="user-profile-dropdown">
                    <ul>

                        <li><a href="{{ route('product') }}"><svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2857 3.90909C10.2857 3.34219 10.8236 2.75 11.6597 2.75C12.4958 2.75 13.0337 3.34219 13.0337 3.90909C13.0337 4.22136 12.9223 4.49112 12.7402 4.68925C12.6273 4.81206 12.5028 4.93485 12.3609 5.07483L12.2797 5.15509C12.1111 5.32181 11.9231 5.51074 11.7513 5.71278C11.4859 6.02479 11.1984 6.43649 11.093 6.94251C10.4656 7.04826 9.85574 7.28792 9.31927 7.66529L2.22358 12.6566C1.29658 13.3086 1.05806 14.3591 1.39567 15.2392C1.72778 16.105 2.58964 16.75 3.70231 16.75H6.00901C5.99977 17.1135 5.99977 17.5271 5.99977 18C5.99977 19.8856 5.99977 20.8284 6.58556 21.4142C7.17135 22 8.11415 22 9.99977 22H13.9998C15.8854 22 16.8282 22 17.414 21.4142C17.9998 20.8284 17.9998 19.8856 17.9998 18C17.9998 17.5271 17.9998 17.1135 17.9905 16.75H20.2973C21.4219 16.75 22.2875 16.0928 22.6125 15.2165C22.9429 14.3256 22.6856 13.2692 21.736 12.629L14.3035 7.61866C13.8094 7.2856 13.2574 7.06682 12.6889 6.95888C12.7396 6.87796 12.8066 6.78721 12.8939 6.68456C13.0202 6.53616 13.1675 6.38672 13.3346 6.22141L13.4085 6.14851C13.5496 6.00942 13.7045 5.85668 13.8444 5.7044C14.2865 5.22354 14.5337 4.58928 14.5337 3.90909C14.5337 2.36727 13.1697 1.25 11.6597 1.25C10.1497 1.25 8.78566 2.36727 8.78566 3.90909C8.78566 4.3233 9.12145 4.65909 9.53566 4.65909C9.94987 4.65909 10.2857 4.3233 10.2857 3.90909ZM17.807 15.25H20.2973C20.8056 15.25 21.103 14.9729 21.2061 14.6949C21.3038 14.4314 21.2543 14.1133 20.8976 13.8728L13.4651 8.86245C12.9825 8.53714 12.3993 8.373 11.81 8.37985C11.2256 8.38665 10.6525 8.56138 10.1823 8.89216L3.08659 13.8834C2.7415 14.1262 2.69597 14.4407 2.79616 14.7019C2.90185 14.9775 3.19965 15.25 3.70231 15.25H6.19252C6.27896 14.9828 6.40418 14.7672 6.58556 14.5858C7.17135 14 8.11415 14 9.99977 14H13.9998C15.8854 14 16.8282 14 17.414 14.5858C17.5954 14.7672 17.7206 14.9828 17.807 15.25Z" fill="currentColor" />
                                </svg>My
                                Closet</a>
                        </li>

                        <li><a href="{{ route('retailercustomer') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
                                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                </svg>Inbound Requests
                            </a>
                        </li>
                        <li><a href="{{ route('orders') }}"><i class="fa-regular fa-file-lines"></i>Outbound Requests</a></li>
                        <li><a class="notification" href="#"><i class="fa-regular fa-bell"></i>Notifications</a></li>

                        <li><a href="{{ route('edit-account') }}"><i class="fa-regular fa-user"></i>Account Settings</a>
                        </li>
                        <li><a href="{{ route('logout') }}"><i class="fa-solid fa-arrow-right-from-bracket"></i>Sign Out</a></li>
                    </ul>
                </div>
                @else
                <div class="user-profile-dropdown">
                    <ul>
                        <li><a href="{{ route('login') }}"><i class="fa-regular fa-user"></i>Sign
                                in</a>
                        </li>
                    </ul>
                </div>
                @endauth
            </li>
        </ul>
    </div>
</div>


</div>
</div>
</nav>
</header> --}}

{{-- NEW HTML CODE --}}
<header class="cust-header">
    <nav class="navbar">
        <div class="container">
            <div class="cust-nav-header-sec">
                <div class="logo mobile-logo">
                    <a class="navbar-brand" href=" {{ url('/') }} "><img src="{{ asset('front/images/logo.svg') }}" alt="logo" width="91" height="63"></a>
                </div>
                <div class="collapse navbar-collapse cust-navbar-header" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                    </ul>
                </div>
                <div class="header-cart">
                    @php
                    $user = auth()->user();
                    $userBankInfo = auth()->user()->userBankInfo;
                    @endphp
                    <ul>
                        <li>
                            @if (is_null($userBankInfo))
                            <div data-bs-toggle="modal" data-bs-target="#addbank-Modal">
                                Add New Product
                            </div>
                            @else
                            <div data-bs-toggle="modal" data-bs-target="#addproduct-Modal">
                                Add New Product
                            </div>
                            @endif

                        </li>
                        <li><a href="{{ route('wishlist') }}"><i class="fa-regular fa-heart"></i>
                                <p class="d-inline-block d-lg-none">Wishlist</p>
                            </a></li>
                        <li>
                            <div class="dropdown">
                                <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">

                                    <li><a class="dropdown-item" href="{{ route('edit-account') }}"><i style="color: #606060" class="fa-solid fa-gear"></i>Account Settings</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('product') }}"><img width="15" height="14" src="{{ asset('front/images/ecommerce.png') }}" alt="img">My Products</a></li>

                                    <li><a class="dropdown-item" href="{{ route('my_query') }}"><img width="15" height="14" src="{{ asset('front/images/my-query-icon.svg') }}" alt="img">Query Receive</a></li>

                                    <li><a class="dropdown-item" href=#><img width="15" height="14" src="{{ asset('front/images/my-query-icon.svg') }}" alt="img">My Query</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><img src="{{ asset('front/images/user-icon.svg') }}" alt="img">Profile</a></li>
                                    <!-- <li><a class="dropdown-item" href="{{ route('orders') }}"><img src="{{ asset('front/images/order-history-icon.svg') }}" alt="img">Order History</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="{{ route('rental-request') }}"><img src="{{ asset('front/images/rent-req-icon.svg') }}" alt="img">Rental Request</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="#"><img src="{{ asset('front/images/notification-icon.svg') }}" alt="img">Notifications</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="#"><img src="{{ asset('front/images/saved-icon.svg') }}" alt="img">Saved Items</a></li> -->
                                    <!-- <li><a class="dropdown-item" href="{{ route('payment-history') }}"><img src="{{ asset('front/images/payment-history-icon.svg') }}" alt="img">Payment History</a></li> -->
                                    <li><a class="dropdown-item" href="{{ route('user.changePassword') }}"><i style="color: #606060" class="fa-solid fa-lock"></i></i>Change
                                            password</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"><img src="{{ asset('front/images/logout-icon.svg') }}" alt="img">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg bottom-nav">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!-- <a class="nav-link" aria-current="page" href="#">What's New</a> -->
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                What's New
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Top Profiles
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Trending
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Occasion
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Brand
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Clothing
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Shoes
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Bags
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Accessories
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="sub-categeory-links">
                                                <a href="#">Dresses</a>
                                                <a href="#">Rompers</a>
                                                <a href="#">Little Black Dress</a>
                                                <a href="#">Tops</a>
                                                <a href="#">Outerwear</a>
                                                <a href="#">Jumpsuit</a>
                                                <a href="#">Bottoms</a>
                                                <a href="#">Skirts</a>
                                                <a href="#">Boho</a>
                                                <a href="#">Knitwear</a>
                                                <a href="#">Denim</a>
                                                <a href="#">Off-Shoulder</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="search-pro-header">
                    <ul>
                        <li>
                            <input type="text" placeholder="Anywhere">
                        </li>
                        <li>
                            <input type="text" placeholder="Any day">
                        </li>
                        <li>
                            <button type="button" class="primary-btn"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
@push('scripts')
@endpush