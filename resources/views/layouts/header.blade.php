@extends('layouts.add_product')


{{-- NEW HTML CODE --}}
<header class="cust-header header">
    <nav class="navbar sticky-nav">
        <div class="container">
            <div class="cust-nav-header-sec">
                <div class="logo mobile-logo">
                    <a class="navbar-brand" href=" {{ url('/') }} "><img
                            src="{{ asset('front/images/HUSH HUSH CLOSET.svg') }}" alt="logo" width="220"
                            height="63"></a>
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
                                {{-- @if (($user->identity_verified) == 'pending')
                                    <div data-bs-toggle="modal" data-bs-target="#pending">
                                        Rent your Closet
                                    </div>
                                @elseif (($user->identity_verified) != 'verified' )
                                    <div data-bs-toggle="modal" data-bs-target="#identity">
                                        Rent your Closet
                                    </div>
                             
                                   

                                @elseif(is_null($userBankInfo))
                                    <div data-bs-toggle="modal" data-bs-target="#addbank-Modal">
                                        Rent your Closet
                                    </div>
                                @elseif (is_null($user->userDetail->complete_address))
                                    <div data-bs-toggle="modal" data-bs-target="#accountSetting">
                                        Rent your Closet    
                                    </div>
                                @else
                                    <div data-bs-toggle="modal" data-bs-target="#addproduct-Modal">
                                        Rent your Closet
                                    </div>
                                @endif --}}
                                <div data-bs-toggle="modal" data-bs-target="#addproduct-Modal">
                                    Rent your Closet
                                </div>
                            </li>
                            <li>
                                <a class="header-chat-icon" href="{{ route('common.chat') }}">
                                    {{-- <i class="fa-solid fa-comment"></i> --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                        viewBox="0 0 24 24" width="15" height="15">
                                        <path
                                            d="m13-.004H5C2.243-.004,0,2.239,0,4.996v12.854c0,.793.435,1.519,1.134,1.894.318.171.667.255,1.015.255.416,0,.831-.121,1.191-.36l3.963-2.643h5.697c2.757,0,5-2.243,5-5v-7C18,2.239,15.757-.004,13-.004Zm11,9v12.854c0,.793-.435,1.519-1.134,1.894-.318.171-.667.255-1.015.256-.416,0-.831-.121-1.19-.36l-3.964-2.644h-5.697c-1.45,0-2.747-.631-3.661-1.62l.569-.38h5.092c3.859,0,7-3.141,7-7v-7c0-.308-.027-.608-.065-.906,2.311.44,4.065,2.469,4.065,4.906Z" />
                                    </svg>
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

                                            </a></li> --}}
                                        {{-- <li><a class="dropdown-item" href="{{ route('order.spent.transaction') }}"><i
                                                class="fa-solid fa-comment"></i>Spent Transaction
                                        </a></li> --}}
                                        <li><a class="dropdown-item" href="{{ route('order.earning.transaction') }}"><svg
                                                    width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_1034_13511)">
                                                        <path
                                                            d="M15.2442 6.13583L15.9492 5.43083C14.5325 3.58917 12.3342 2.49917 10.0008 2.49917C5.865 2.5 2.5 5.86417 2.5 10C2.5 14.1358 5.865 17.5 10 17.5C13.7083 17.5 16.9 14.7375 17.4233 11.0733C17.5217 10.3908 18.1575 9.9175 18.8375 10.0125C19.5208 10.1108 19.9967 10.7442 19.8983 11.4267C19.1992 16.3142 14.9442 20 9.99917 20C4.48583 20 0 15.5142 0 10C0 4.48583 4.48583 0 10 0C13.0017 0 15.835 1.35167 17.7275 3.6525L18.6458 2.73417C19.1458 2.23417 20 2.58833 20 3.295V6.48667C20 7.04083 19.5508 7.49 18.9958 7.49H15.8042C15.0975 7.49 14.7442 6.63583 15.2442 6.13583ZM10 16.6667C10.6908 16.6667 11.25 16.1067 11.25 15.4167V14.8592C12.4567 14.4742 13.3333 13.3425 13.3333 12.0108C13.3333 10.7808 12.5958 9.69167 11.455 9.23583L9.47333 8.4425C9.28667 8.36833 9.16667 8.19 9.16667 7.98917C9.16667 7.72 9.38583 7.5 9.65583 7.5H10.64C10.7908 7.5 10.9258 7.57833 11.0017 7.70917C11.3475 8.30667 12.1125 8.51167 12.71 8.16583C13.3075 7.82 13.5117 7.055 13.1667 6.4575C12.7508 5.7375 12.0467 5.23667 11.2508 5.06583V4.58333C11.2508 3.89333 10.6917 3.33333 10.0008 3.33333C9.31 3.33333 8.75083 3.89333 8.75083 4.58333V5.14083C7.54417 5.525 6.6675 6.6575 6.6675 7.98917C6.6675 9.22 7.405 10.3083 8.54583 10.7642L10.5267 11.5567C10.7133 11.6317 10.8333 11.81 10.8333 12.0108C10.8333 12.28 10.6142 12.5 10.3442 12.5H9.36C9.20917 12.5 9.07417 12.4217 8.99833 12.2908C8.6525 11.6933 7.8875 11.4892 7.29083 11.8342C6.69333 12.18 6.48917 12.945 6.835 13.5425C7.25083 14.2625 7.95417 14.7633 8.75 14.935V15.4167C8.75 16.1067 9.30917 16.6667 10 16.6667Z"
                                                            fill="#212529e6" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_1034_13511">
                                                            <rect width="20" height="20" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                Earnings
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
                            <div><a href="{{ route('login') }}">login</a> / <a href="{{ route('register')}}">sign up</a></div>
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
                    <li>
                        <a href="{{ route('index', ['sort' => 'new']) }}">Latest</a>
                    </li>
                    <li>
                        <a href="{{ route('index', ['sort' => 'old']) }}">Oldest</a>
                    </li>
                    <li>
                        <a href="{{ route('index', ['sort' => 'costier']) }}">Costlier</a>
                    </li>
                    {{-- <li>
                        <a href="{{ url('/') }}">Categories</a>
                     </li> --}}
                    <li class="nav-item">
                        <!-- <a class="nav-link" aria-current="page" href="#">What's New</a> -->
                        <div class="dropdown cstm-dropdown">
                            <div class="dropdown-toggle p-0" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <div class="sub-categeory-menu">
                                    <div class="sub-categeory-links">
                                        @foreach (getParentCategory() as $index => $parentCategory)
                                            <a class="dropdown-toggle"
                                                href="{{ route('index', ['category' => $parentCategory->id]) }}"
                                                type="button">
                                                {{ $parentCategory->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                    {{-- <div class="col-md-6">
                                            <div class="sub-categeory-img">
                                                <img src="{{ asset('front/images/sub-menu-img.png') }}"
                                                    alt="img">
                                            </div>
                                        </div> --}}
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- @foreach (getParentCategory() as $index => $parentCategory)
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
                @endforeach --}}
                </ul>
            </div>
            <form id="searchForm" action="{{ route('index') }}" method="GET" class="mb-0">
                {{-- @if (request()->route()->getName() == 'index') --}}
                <input type="hidden" id="currentRoute" name="currentRoute"
                    value="{{ request()->route()->getName() }}">
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
        function clearForm() {
            document.getElementById('searchForm').reset();
        }

        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const filterForm = document.getElementById('filters');
            const searchForm = document.getElementById('searchForm');
            const filterInputs = filterForm.querySelectorAll('input[name]');

            const searchInput = document.querySelector('input[name="search"]').value.trim();
            const dateInput = document.querySelector('input[name="filter_date"]').value.trim();

            // Validation: Check if both fields are filled
            if (searchInput === '' && dateInput === '') {
                return; // Prevent form submission if both are empty
            }

            filterInputs.forEach(function(input) {
                const clone = input.cloneNode(true);
                clone.style.display = 'none';
                searchForm.appendChild(clone);
            });
            searchForm.submit();
        });
    </script>
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const currentRouteName = document.getElementById('currentRoute') ? document.getElementById(
                'currentRoute').value : document.body.getAttribute('data-route-name');

            const searchInput = document.querySelector('input[name="search"]').value.trim();
            const dateInput = document.querySelector('input[name="filter_date"]').value.trim();

            // Validation: Check if both fields are filled
            if (searchInput === '' && dateInput === '') {
                return; // Prevent form submission if both are empty
            }
            // If the route is 'index', submit the form with all fields
            if (currentRouteName === 'index') {
                this.submit();
            } else {
                // Handle submission for other routes: submit only specific fields
                const formData = new FormData(this);
                const filteredData = new URLSearchParams();

                // Add only the fields you want to include
                filteredData.append('search', formData.get('search'));
                // Add other fields you want to include

                // Create a new form with filtered data
                const filteredForm = document.createElement('form');
                filteredForm.method = 'GET';
                filteredForm.action = this.action;

                // Add the filtered data to the new form
                for (const [key, value] of filteredData) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    filteredForm.appendChild(input);
                }

                // Append the new form to the document and submit it
                document.body.appendChild(filteredForm);
                filteredForm.submit();
            }
        });

        function clearForm() {
            document.getElementById('searchForm').reset();
        }
        jQuery(this).daterangepicker({
            minDate: moment(),
        });
    </script>
@endpush
