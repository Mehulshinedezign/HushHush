@extends('layouts.add_product')


{{-- NEW HTML CODE --}}
<header class="cust-header">
    <nav class="navbar sticky-nav">
        <div class="container">
            <div class="cust-nav-header-sec">
                <div class="logo mobile-logo">
                    <a class="navbar-brand" href=" {{ url('/') }} "><img src="{{ asset('front/images/logo.svg') }}"
                            alt="logo" width="91" height="63"></a>
                </div>
                <div class="collapse navbar-collapse cust-navbar-header" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                    </ul>
                </div>
                @auth
                    <div class="header-cart">
                        @php
                            $user = auth()->user();
                            $userBankInfo = auth()->user()->userBankInfo;
                        @endphp
                        <ul>
                            <li>
                                @if (is_null($userBankInfo))
                                    <div data-bs-toggle="modal" data-bs-target="#addbank-Modal">
                                        Rent your Closet
                                    </div>
                                @elseif (is_null($user->userDetail->complete_address))
                                    <div data-bs-toggle="modal" data-bs-target="#addaddress-Modal">
                                        Rent your Closet
                                    </div>
                                @else
                                    <div data-bs-toggle="modal" data-bs-target="#addproduct-Modal">
                                        Rent your Closet
                                    </div>
                                @endif

                            </li>
                            <li>
                                <a class="header-chat-icon" href="{{ route('common.chat') }}">
                                    {{-- <i class="fa-solid fa-comment"></i> --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="15" height="15"><path d="m13-.004H5C2.243-.004,0,2.239,0,4.996v12.854c0,.793.435,1.519,1.134,1.894.318.171.667.255,1.015.255.416,0,.831-.121,1.191-.36l3.963-2.643h5.697c2.757,0,5-2.243,5-5v-7C18,2.239,15.757-.004,13-.004Zm11,9v12.854c0,.793-.435,1.519-1.134,1.894-.318.171-.667.255-1.015.256-.416,0-.831-.121-1.19-.36l-3.964-2.644h-5.697c-1.45,0-2.747-.631-3.661-1.62l.569-.38h5.092c3.859,0,7-3.141,7-7v-7c0-.308-.027-.608-.065-.906,2.311.44,4.065,2.469,4.065,4.906Z"/></svg>
                                <span class="userIconbtn"></span>
                            </a>
                            </li>
                            <li><a href="{{ route('wishlist') }}">
                                    <i class="fa-regular fa-heart"></i>
                                    <p class="d-inline-block d-lg-none"></p>
                                </a></li>
                            <li>
                                <div class="dropdown">
                                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-regular fa-user"></i>
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">

                                        <li><a class="dropdown-item" href="{{ route('edit-account') }}"><i
                                                    style="color: #212529" class="fa-solid fa-gear"></i>Account Settings</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('product') }}">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                My Products</a></li>

                                        <li><a class="dropdown-item" href="{{ route('my_query') }}">
                                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                My Inquiry</a></li>
                                        <li><a class="dropdown-item" href="{{ route('receive_query') }}"><img width="15"
                                                    height="14" src="{{ asset('front/images/my-query-icon.svg') }}"
                                                    alt="img">Received Inquiry</a></li>
                                        <li><a class="dropdown-item" href="{{ route('profile') }}">
                                                <i class="fa-solid fa-user"></i>
                                                Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('retailercustomer') }}">
                                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                Received order</a></li>
                                        <li><a class="dropdown-item" href="{{ route('orders') }}"><i
                                                    class="fa-solid fa-file"></i>Order History</a></li>
                                        <!-- <li><a class="dropdown-item" href="{{ route('rental-request') }}"><img src="{{ asset('front/images/rent-req-icon.svg') }}" alt="img">Rental Request</a></li> -->
                                        <!-- <li><a class="dropdown-item" href="#"><img src="{{ asset('front/images/notification-icon.svg') }}" alt="img">Notifications</a></li> -->
                                        <!-- <li><a class="dropdown-item" href="#"><img src="{{ asset('front/images/saved-icon.svg') }}" alt="img">Saved Items</a></li> -->
                                        <!-- <li><a class="dropdown-item" href="{{ route('payment-history') }}"><img src="{{ asset('front/images/payment-history-icon.svg') }}" alt="img">Payment History</a></li> -->
                                        <li><a class="dropdown-item" href="{{ route('user.changePassword') }}"><i
                                                    style="color: #212529" class="fa-solid fa-lock"></i>Change
                                                password</a></li>
                                        {{-- <li><a class="dropdown-item" href="{{ route('common.chat') }}"><i
                                                    class="fa-solid fa-comment"></i>Chat
                                                <span class="userIconbtn"></span>
                                            </a></li> --}}
                                        {{-- <li><a class="dropdown-item" href="{{ route('order.spent.transaction') }}"><i
                                                class="fa-solid fa-comment"></i>Spent Transaction
                                        </a></li> --}}
                                        <li><a class="dropdown-item" href="{{ route('order.earning.transaction') }}"><svg
                                                    xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                                    viewBox="0 0 24 24" width="20" height="20">
                                                    <path
                                                        d="m18.293,7.363l.846-.846c-1.7-2.21-4.338-3.518-7.138-3.518C7.038,3,3,7.037,3,12s4.038,9,9,9c4.45,0,8.28-3.315,8.908-7.712.118-.819.881-1.387,1.697-1.273.82.118,1.391.878,1.273,1.697-.839,5.865-5.945,10.288-11.879,10.288C5.383,24,0,18.617,0,12S5.383,0,12,0c3.602,0,7.002,1.622,9.273,4.383l1.102-1.102c.6-.6,1.625-.175,1.625.673v3.83c0,.665-.539,1.204-1.205,1.204h-3.83c-.848,0-1.273-1.025-.673-1.625Zm-6.293,12.637c.829,0,1.5-.672,1.5-1.5v-.669c1.448-.462,2.5-1.82,2.5-3.418,0-1.476-.885-2.783-2.254-3.33l-2.378-.952c-.224-.089-.368-.303-.368-.544,0-.323.263-.587.587-.587h1.181c.181,0,.343.094.434.251.415.717,1.333.963,2.05.548s.962-1.333.548-2.05c-.499-.864-1.344-1.465-2.299-1.67v-.579c0-.828-.671-1.5-1.5-1.5s-1.5.672-1.5,1.5v.669c-1.448.461-2.5,1.82-2.5,3.418,0,1.477.885,2.783,2.254,3.33l2.377.951c.224.09.368.304.368.545,0,.323-.263.587-.587.587h-1.181c-.181,0-.343-.094-.434-.251-.415-.717-1.333-.962-2.049-.548-.717.415-.962,1.333-.547,2.05.499.864,1.343,1.465,2.298,1.671v.578c0,.828.671,1.5,1.5,1.5Z" />
                                                </svg>Earnings
                                            </a></li>
                                        {{-- <li><a class="dropdown-item" href="{{ route('') }}"><i
                                                style="color: #606060" class="fa-solid fa-lock"></i></i>my
                                            order</a></li> --}}
                                        <li><a class="dropdown-item" href="{{ route('logout') }}">
                                                <i class="fa-solid fa-sign-out" aria-hidden="true"></i>
                                                Logout</a></li>
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
            @endauth
            @guest
                <div class="header-cart">
                    <ul>
                        <li>
                            <div><a href="{{ route('login') }}">Rent your Closet</a></div>
                        </li>
                        <li>
                            <div><a href="{{ route('login') }}">login</a></div>
                        </li>
                    </ul>

                </div>
            @endguest
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg bottom-nav">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class=""><i style="color: #fff" class="fa-sharp fa-solid fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li>
                       <a href="{{ url('/') }}">Home</a> 
                    </li>
                    @foreach (getParentCategory() as $index => $parentCategory)
                        @if ($index >= 5)
                        @break
                    @endif
                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="dropdown-toggle"
                                href="{{ route('index', ['category' => $parentCategory->id]) }}" type="button">
                                {{ $parentCategory->name }}
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <form id="searchForm" action="{{ route('index') }}" method="GET">
            {{-- @if (request()->route()->getName() == 'index') --}}
            <div class="search-pro-header">
                <ul>
                    <li>
                        <input type="text" name="search" placeholder="Search by Product Name"
                            value="{{ request('search') }}">
                    </li>
                    <li>
                        <div class="formfield icon-new-bx">
                            <input type="text" name="filter_date" id="daterange-header"
                                placeholder="Enter Date Range" class="form-control daterange-cus custom-left-open"
                                readonly>
                            <label for="daterange-header" class="form-icon">
                                <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img">
                            </label>

                        </div>
                    </li>
                    <li>
                        <button type="submit" class="primary-btn"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </li>
                    <li>
                        @if (request()->route()->getName() == 'index')
                            <a href="{{ route('index') }}" class="close-icon-link"><i
                                    class="fa-solid fa-xmark"></i></a>
                        @else
                            <a href="javascript:void(0);" class="close-icon-link" onclick="clearForm();"><i
                                    class="fa-solid fa-xmark"></i></a>
                        @endif
                    </li>
                </ul>
            </div>
            {{-- @endif --}}
        </form>
    </div>
</nav>
</header>
{{-- @include('common.alert') --}}
@push('scripts')
<script>
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const filterForm = document.getElementById('filters');
        const searchForm = document.getElementById('searchForm');
        const filterInputs = filterForm.querySelectorAll('input[name]');
        filterInputs.forEach(function(input) {
            const clone = input.cloneNode(true);
            clone.style.display = 'none';
            searchForm.appendChild(clone);
        });
        searchForm.submit();
    });
</script>
<script>
    function clearForm() {
        document.getElementById('searchForm').reset();
    }
</script>
@endpush
