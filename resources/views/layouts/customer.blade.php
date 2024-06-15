<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name', 'Rentahobby') }}</title>
    <meta name="description" content="">
    <link rel=“canonical” href="#" />
    <meta name="baseurl" content="{{ url('') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}">

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script>
        const APP_URL = "{{ url('') }}";
        const fileNameLength = parseInt("{{ $global_preview_file_name_length }}");
        const uploadFileSize = parseInt("{{ $global_js_file_size }}");
        const minPickedUpImageCount = parseInt("{{ $global_min_picked_up_image_count }}");
        const maxPickedUpImageCount = parseInt("{{ $global_max_picked_up_image_count }}");
        const minReturnedImageCount = parseInt("{{ $global_min_returned_image_count }}");
        const maxReturnedImageCount = parseInt("{{ $global_max_returned_image_count }}");
        const minDisputeImageCount = parseInt("{{ $global_min_dispute_image_count }}");
        const maxDisputeImageCount = parseInt("{{ $global_max_dispute_image_count }}");
        const allowedExtension = @php echo $global_js_image_extension; @endphp;
        const allowedProofExtension = @php echo $global_js_proof_extension; @endphp;
        const allowedExtensionMessage = allowedExtension.join(',');
        const dateFormat = "{{ $global_js_date_format }}";
        const dateTimeFormat = "{{ $global_js_date_time_format }}";
        const uploadFileSizeInMb = uploadFileSize / 1000000 + 'Mb';
        const calendarTimeGap = parseInt("{{ $global_calendar_time_gap }}");
        const dateOptions = {
            locale: {
                // format: "{{ $global_js_date_format }}",
                separator: " {{ $global_date_separator }} ",
            },
            autoUpdateInput: false,
            drops: 'down',
            opens: 'right',
        };
        var loaderIcon = '<span class="loader" id="loader"><img src="{{ asset('img/loader-icon.svg') }}"></span>';
    </script>
    @yield('links')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
</head>

<body>
    <header class="header" id="header">
        <div class="container">
            <nav class="navbar navbar-expand-lg custom-navbar">
                <a class="navbar-brand" href="{{ route('index') }}">
                    <img src="{{ asset('img/logo.svg') }}" alt="logo" />
                </a>
                <!-- Toggler/collapsibe Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigationMenu">
                    <span class="navbar-toggler-icon">
                        <span class="bar1"></span>
                        <span class="bar2"></span>
                        <span class="bar3"></span>
                    </span>
                </button>

                <!-- Navbar links -->
                <div class="collapse navbar-collapse navbar-menu" id="navigationMenu">
                    <a class="navbar-brand mobile-logo d-block  d-lg-none" href="{{ route('index') }}">
                        <img src="{{ asset('img/logo.svg') }}" alt="logo" />
                    </a>
                    <button class="navbar-toggler cross-toggler" type="button" data-toggle="collapse"
                        data-target="#navigationMenu">
                        <span class="navbar-toggler-icon cross-toggle-icon">
                            <span class="bar1"></span>
                            <span class="bar2"></span>
                            <span class="bar3"></span>
                        </span>
                    </button>
                    <ul class="navbar-nav navbar-left" id="navbar-left">
                        <li class="nav-item">
                            <a class="nav-link @if (!is_null(Route::current()) && ('index' == Route::current()->getName() || 'products' == Route::current()->getName())) active @endif"
                                href="{{ route('index') }}">Home</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link @if (!is_null(Route::current()) && 'login' == Route::current()->getName()) active @endif"
                                    href="{{ route('login') }}">Sign In</a>
                            </li>
                        @endguest
                        <li class="nav-item">
                            <a class="nav-link @if (!is_null(Route::current()) && 'howitworks' == Route::current()->getName()) active @endif"
                                href="{{ route('howitworks') }}">How it works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (!is_null(Route::current()) && 'faq' == Route::current()->getName()) active @endif"
                                href="{{ route('faq') }}">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (!is_null(Route::current()) && 'contactus' == Route::current()->getName()) active @endif"
                                href="{{ route('contactus') }}">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn blue-btn blue-outline mt-3 d-block d-lg-none"
                                href="{{ route('retailer.register') }}">Become a Retailer</a>
                        </li>

                    </ul>
                    @if ('no' == $isMobile)
                        <ul class="navbar-nav navbar-right" id="navbar-right">
                            <li class="search-nav-item">
                                <div class="search-input-box">
                                    @if (!is_null(Route::current()) && Route::current()->getName() != 'products' && @Route::current()->getName() != 'index')
                                        <form action="{{ route('products') }}" method="get" id="productSearchForm">
                                            <input type="search" name="product" id="search"
                                                placeholder="Search product here..." class="form-control">
                                            <button type="submit" class="btn blue-btn"
                                                id="searchProduct">Search</button>
                                        </form>
                                    @else
                                        <form method="get" id="productSearchForm">
                                            <input type="text" name="product" id="search"
                                                value="{{ request()->product }}" placeholder="Search product here..."
                                                class="form-control">
                                            <button type="submit" class="btn blue-btn" id="searchProduct">Search
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                            @guest
                                <li class="nav-item">
                                    <a class="btn blue-btn blue-outline" href="{{ route('retailer.register') }}">Become a
                                        Retailer</a>
                                </li>
                            @endguest

                            @auth
                                <li class="header-icon dropdown">
                                    <a class="nav-link dropdown-toggle notifi_icon" href="#" id="dropdown"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if ($notifications->isNotEmpty())
                                            <span class="notification-dot"></span>
                                        @endif
                                        <svg width="27" height="27" viewBox="0 0 21 23" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.6896 15.7698L18.0658 13.952V10.5687C18.0654 9.10911 17.646 7.68063 16.8579 6.45474C16.0699 5.22885 14.9467 4.25763 13.6231 3.65767V3.21363C13.63 2.41084 13.3357 1.63492 12.7988 1.04066C12.2618 0.446392 11.5218 0.0774637 10.7263 0.00745957C10.2993 -0.0223274 9.87075 0.0373029 9.46785 0.182579C9.06494 0.327854 8.69641 0.555613 8.38557 0.851447C8.075 1.14504 7.82721 1.4992 7.65732 1.89228C7.48744 2.28537 7.39903 2.70914 7.39749 3.13772V3.66828C6.07617 4.26937 4.95499 5.24031 4.168 6.46502C3.38101 7.68973 2.96151 9.1164 2.95965 10.5744V13.9496L1.33586 15.7673C1.08278 16.0495 0.901785 16.3894 0.80847 16.7576C0.715155 17.1258 0.712325 17.5113 0.800223 17.8808C0.888122 18.2503 1.06411 18.5928 1.31301 18.8788C1.56192 19.1647 1.87626 19.3855 2.22895 19.522L2.44653 19.5751L6.62943 19.682C6.78071 20.6072 7.2536 21.4485 7.96395 22.056C8.6743 22.6636 9.57603 22.9981 10.5087 23C10.5899 23 10.6784 23 10.7644 22.9918C11.6518 22.9322 12.4931 22.5728 13.1518 21.972C13.8104 21.3712 14.2478 20.5642 14.3928 19.682H17.9554C18.4065 19.6829 18.8482 19.5521 19.2268 19.3055C19.6054 19.059 19.9046 18.7072 20.0883 18.293C20.2719 17.8787 20.332 17.4198 20.2612 16.9719C20.1904 16.524 19.9919 16.1064 19.6896 15.7698ZM12.5628 19.6779C12.4242 20.1154 12.1508 20.4972 11.7821 20.7682C11.4135 21.0391 10.9686 21.1851 10.5119 21.1851C10.0552 21.1851 9.6104 21.0391 9.24173 20.7682C8.87305 20.4972 8.59965 20.1154 8.46107 19.6779H12.5628ZM10.5119 1.79583H10.611C10.9521 1.83415 11.2668 1.99887 11.4936 2.25785C11.7204 2.51682 11.8431 2.85147 11.8378 3.19649V4.2576C11.8376 4.44303 11.8946 4.62395 12.0009 4.77551C12.1072 4.92707 12.2575 5.04183 12.4313 5.10403C13.5526 5.50607 14.5235 6.24578 15.2117 7.22246C15.8999 8.19913 16.2719 9.36531 16.2772 10.5622V13.3962H11.7817C11.5449 13.3962 11.3177 13.4908 11.1502 13.6591C10.9827 13.8275 10.8886 14.0559 10.8886 14.294C10.8886 14.5322 10.9827 14.7605 11.1502 14.9289C11.3177 15.0973 11.5449 15.1919 11.7817 15.1919H16.7765L18.3581 16.9697C18.4285 17.0483 18.4747 17.1459 18.491 17.2504C18.5073 17.3549 18.4932 17.462 18.4502 17.5586C18.4072 17.6552 18.3373 17.7372 18.2489 17.7946C18.1605 17.8521 18.0574 17.8825 17.9522 17.8822H3.06764C2.96238 17.8825 2.8593 17.8521 2.7709 17.7946C2.68249 17.7372 2.61257 17.6552 2.56959 17.5586C2.52662 17.462 2.51245 17.3549 2.5288 17.2504C2.54514 17.1459 2.59131 17.0483 2.66169 16.9697L4.51363 14.8948C4.66055 14.7296 4.74152 14.5156 4.74096 14.294V10.5753C4.74612 9.3772 5.11843 8.20984 5.80721 7.23215C6.49598 6.25445 7.46774 5.51392 8.59017 5.11138C8.76366 5.04927 8.91382 4.93476 9.02006 4.78352C9.12631 4.63229 9.18345 4.45173 9.18366 4.26658V3.13038C9.18431 2.77649 9.32448 2.4373 9.57346 2.18714C9.82245 1.93698 10.1599 1.79626 10.5119 1.79583Z"
                                                fill="#1372E6"></path>
                                        </svg>
                                    </a>
                                    <div class="notification-feed-boxes dropdown-menu nav-dropdown">
                                        <ul class="list-unstyled">
                                            <li class="notification-box noti-all">
                                                @if ($notifications->isNotEmpty())
                                                    <p>Recent <span>notifications</span></p>
                                                @else
                                                    <p>No <span>notifications</span></p>
                                                @endif
                                                <a href="{{ route('notifications') }}">View All</a>
                                            </li>
                                            @foreach ($notifications as $notification)
                                                <li class="notification-box">
                                                    <div class="notification-feed-img">
                                                        @if (isset($notification->order->item->product->thumbnailImage->url))
                                                            <img src="{{ $notification->order->item->product->thumbnailImage->url }}"
                                                                class="round-img">
                                                        @else
                                                            <img src="{{ asset('img/default-product.png') }}"
                                                                class="round-img">
                                                        @endif
                                                    </div>
                                                    <div class="notification-feed-content">
                                                        <p class="notifi-text"><b>Order #{{ $notification->order_id }}</b>
                                                            {{ $notification->message }}</p>
                                                        @if (!is_null($notification->created_at))
                                                            <span
                                                                class="update-time">{{ $notification->created_at->diffForHumans() }}</span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endauth
                            <li class="nav-item admin-login">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="dashboard-user d-flex align-items-center">
                                        @if (isset(auth()->user()->profile_pic_url))
                                            <img src="{{ auth()->user()->profile_pic_url }}"
                                                class="header-profile-img">
                                            <span class="welcome-msg d-block ml-3">{{ auth()->user()->name }} </span>
                                        @else
                                            <img src="{{ asset('img/avatar-small.png') }}"
                                                class="header-profile-img">
                                        @endif
                                    </span>
                                </a>
                                <div class="dropdown-menu nav-dropdown" aria-labelledby="dropdown">
                                    @guest
                                        @if (Route::has('login'))
                                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                        @endif

                                        @if (Route::has('register'))
                                            <a class="dropdown-item"
                                                href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                        @endif
                                    @else
                                        <a class="dropdown-item" href="{{ route('profile') }}">My Account</a>
                                        <a class="dropdown-item" href="{{ route('orders') }}">My Orders</a>
                                        <a class="dropdown-item" href="{{ route('wishlist') }}">My Wishlist</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}">Sign Out</a>
                        @endif
                    </div>
                    </li>
                    </ul>
                    @endif
            </div>

            <div class="mobile-contact d-block d-lg-none">
                <!-- <a class="btn" href="{{ route('login') }}">Login</a> -->
                <ul class="navbar-nav navbar-right" id="navbar-right">
                    <li class="search-nav-item">
                        <div class="search-input-box">
                            @if (!is_null(Route::current()) && Route::current()->getName() != 'products' && @Route::current()->getName() != 'index')
                                <form action="{{ route('products') }}" method="get">
                                    <i class="fa fa-times icon-range" aria-hidden="true"></i>
                                    <input type="search" name="product" id="search"
                                        placeholder="Search product here..." class="form-control">
                                    <button type="submit" class="btn blue-btn" id="searchProduct">Search</button>
                                </form>
                            @else
                                <form method="get" id="productSearchForm">
                                    <i class="fa fa-times icon-range" aria-hidden="true"></i>
                                    <input type="text" name="product" id="search"
                                        value="{{ request()->product }}" placeholder="Search product here..."
                                        class="form-control">
                                    <button type="submit" class="btn blue-btn" id="searchProduct">Search</button>
                                </form>
                            @endif
                        </div>
                        <a href="#" class="search-click"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </li>
                    @auth
                        <li class="header-icon dropdown">
                            <a class="nav-link dropdown-toggle notifi_icon" href="#" id="dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if ($notifications->isNotEmpty())
                                    <span class="notification-dot"></span>
                                @endif
                                <svg width="27" height="27" viewBox="0 0 21 23" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.6896 15.7698L18.0658 13.952V10.5687C18.0654 9.10911 17.646 7.68063 16.8579 6.45474C16.0699 5.22885 14.9467 4.25763 13.6231 3.65767V3.21363C13.63 2.41084 13.3357 1.63492 12.7988 1.04066C12.2618 0.446392 11.5218 0.0774637 10.7263 0.00745957C10.2993 -0.0223274 9.87075 0.0373029 9.46785 0.182579C9.06494 0.327854 8.69641 0.555613 8.38557 0.851447C8.075 1.14504 7.82721 1.4992 7.65732 1.89228C7.48744 2.28537 7.39903 2.70914 7.39749 3.13772V3.66828C6.07617 4.26937 4.95499 5.24031 4.168 6.46502C3.38101 7.68973 2.96151 9.1164 2.95965 10.5744V13.9496L1.33586 15.7673C1.08278 16.0495 0.901785 16.3894 0.80847 16.7576C0.715155 17.1258 0.712325 17.5113 0.800223 17.8808C0.888122 18.2503 1.06411 18.5928 1.31301 18.8788C1.56192 19.1647 1.87626 19.3855 2.22895 19.522L2.44653 19.5751L6.62943 19.682C6.78071 20.6072 7.2536 21.4485 7.96395 22.056C8.6743 22.6636 9.57603 22.9981 10.5087 23C10.5899 23 10.6784 23 10.7644 22.9918C11.6518 22.9322 12.4931 22.5728 13.1518 21.972C13.8104 21.3712 14.2478 20.5642 14.3928 19.682H17.9554C18.4065 19.6829 18.8482 19.5521 19.2268 19.3055C19.6054 19.059 19.9046 18.7072 20.0883 18.293C20.2719 17.8787 20.332 17.4198 20.2612 16.9719C20.1904 16.524 19.9919 16.1064 19.6896 15.7698ZM12.5628 19.6779C12.4242 20.1154 12.1508 20.4972 11.7821 20.7682C11.4135 21.0391 10.9686 21.1851 10.5119 21.1851C10.0552 21.1851 9.6104 21.0391 9.24173 20.7682C8.87305 20.4972 8.59965 20.1154 8.46107 19.6779H12.5628ZM10.5119 1.79583H10.611C10.9521 1.83415 11.2668 1.99887 11.4936 2.25785C11.7204 2.51682 11.8431 2.85147 11.8378 3.19649V4.2576C11.8376 4.44303 11.8946 4.62395 12.0009 4.77551C12.1072 4.92707 12.2575 5.04183 12.4313 5.10403C13.5526 5.50607 14.5235 6.24578 15.2117 7.22246C15.8999 8.19913 16.2719 9.36531 16.2772 10.5622V13.3962H11.7817C11.5449 13.3962 11.3177 13.4908 11.1502 13.6591C10.9827 13.8275 10.8886 14.0559 10.8886 14.294C10.8886 14.5322 10.9827 14.7605 11.1502 14.9289C11.3177 15.0973 11.5449 15.1919 11.7817 15.1919H16.7765L18.3581 16.9697C18.4285 17.0483 18.4747 17.1459 18.491 17.2504C18.5073 17.3549 18.4932 17.462 18.4502 17.5586C18.4072 17.6552 18.3373 17.7372 18.2489 17.7946C18.1605 17.8521 18.0574 17.8825 17.9522 17.8822H3.06764C2.96238 17.8825 2.8593 17.8521 2.7709 17.7946C2.68249 17.7372 2.61257 17.6552 2.56959 17.5586C2.52662 17.462 2.51245 17.3549 2.5288 17.2504C2.54514 17.1459 2.59131 17.0483 2.66169 16.9697L4.51363 14.8948C4.66055 14.7296 4.74152 14.5156 4.74096 14.294V10.5753C4.74612 9.3772 5.11843 8.20984 5.80721 7.23215C6.49598 6.25445 7.46774 5.51392 8.59017 5.11138C8.76366 5.04927 8.91382 4.93476 9.02006 4.78352C9.12631 4.63229 9.18345 4.45173 9.18366 4.26658V3.13038C9.18431 2.77649 9.32448 2.4373 9.57346 2.18714C9.82245 1.93698 10.1599 1.79626 10.5119 1.79583Z"
                                        fill="#1372E6"></path>
                                </svg>
                            </a>
                            <div class="notification-feed-boxes dropdown-menu nav-dropdown">
                                <ul class="list-unstyled">
                                    <li class="notification-box noti-all">
                                        @if ($notifications->isNotEmpty())
                                            <p>Recent <span>notifications</span></p>
                                        @else
                                            <p>No <span>notifications</span></p>
                                        @endif
                                        <a href="{{ route('notifications') }}">View All</a>
                                    </li>
                                    @foreach ($notifications as $notification)
                                        <li class="notification-box">
                                            <div class="notification-feed-img">
                                                @if (isset($notification->order->item->product->thumbnailImage->url))
                                                    <img src="{{ $notification->order->item->product->thumbnailImage->url }}"
                                                        class="round-img">
                                                @else
                                                    <img src="{{ asset('img/default-product.png') }}" class="round-img">
                                                @endif
                                            </div>
                                            <div class="notification-feed-content">
                                                <p class="notifi-text"><b>Order #{{ $notification->order_id }}</b>
                                                    {{ $notification->message }}</p>
                                                @if (!is_null($notification->created_at))
                                                    <span
                                                        class="update-time">{{ $notification->created_at->diffForHumans() }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="dashboard-user d-flex align-items-center">
                                @if (isset(auth()->user()->profile_pic_url))
                                    <img src="{{ auth()->user()->profile_pic_url }}" class="header-profile-img">
                                    <span class="welcome-msg d-block ml-3">{{ auth()->user()->name }} </span>
                                @else
                                    <img src="{{ asset('img/avatar-small.png') }}" class="header-profile-img">
                                @endif
                            </span>
                        </a>
                        <div class="dropdown-menu nav-dropdown" aria-labelledby="dropdown">
                            @guest
                                @if (Route::has('login'))
                                    <a class="dropdown-item" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                @endif

                                @if (Route::has('register'))
                                    <a class="dropdown-item" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                @endif
                            @else
                                <a class="dropdown-item" href="{{ route('profile') }}">My Account</a>
                                <a class="dropdown-item" href="{{ route('orders') }}">My Orders</a>
                                <a class="dropdown-item" href="{{ route('wishlist') }}">My Wishlist</a>
                                <a class="dropdown-item" href="{{ route('logout') }}">Sign Out</a>
                                @endif
                            </div>
                        </li>
                    </ul>

                </div>

                </nav>
                </div>
            </header>
            <main class="top-space">
                @yield('content')
            </main>
            <footer class="footer" id="footer">
                <div class="footer-bottom-section">
                    <div class="container">
                        <div class="footer-content-outer">
                            <div class="footer-logo-section">
                                <figure><img src="{{ asset('img/logo.svg') }}" alt="Logo" /></figure>
                                @if (strlen($global_footer_page_content) > 50)
                                    <p class="read-more-content"> {{ substr($global_footer_page_content, 0, 50) }} <span
                                            class="read-more blue-text">Read More...</span></p>
                                    <p class="read-less-content d-none">{{ $global_footer_page_content }} <span
                                            class="read-less blue-text">Read Less...</span></p>
                                @else
                                    <p class="read-less-content">{{ $global_footer_page_content }}</p>
                                @endif
                            </div>
                            <div class="footer-navigation-menus" id="footer-accordion">
                                <div class="footer-navigation-list">
                                    <div class="footer-navigation-title"><span>Quick Links</span> <span
                                            class="accordion-arrow"></span></div>
                                    <ul class="submenu">
                                        <li class="footer-navigation-link"><a href="{{ route('products') }}">Products</a>
                                        </li>
                                        <li class="footer-navigation-link"><a href="{{ route('aboutus') }}">About Us</a>
                                        </li>
                                        @guest
                                            <li class="footer-navigation-link"><a href="{{ route('retailer.register') }}">Become
                                                    a Retailer</a> </li>
                                        @endguest
                                        <li class="footer-navigation-link"><a href="{{ route('contactus') }}">Contact Us</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="footer-navigation-list">
                                    <div class="footer-navigation-title"><span>Help</span> <span
                                            class="accordion-arrow"></span></div>
                                    <ul class="submenu">
                                        <li class="footer-navigation-link"><a href="{{ route('faq') }}">FAQ</a> </li>
                                        <li class="footer-navigation-link"><a href="{{ route('howitworks') }}">How it
                                                works</a> </li>
                                        <li class="footer-navigation-link"><a href="{{ route('termsconditions') }}">Terms &
                                                Conditions</a> </li>
                                        <li class="footer-navigation-link"><a href="{{ route('policies') }}">Privacy
                                                Policies</a> </li>
                                    </ul>
                                </div>
                                <div class="footer-navigation-list">
                                    <div class="footer-navigation-title"><span>Social media</span> <span
                                            class="accordion-arrow"></span></div>
                                    <ul class="submenu">
                                        <li class="footer-navigation-link">
                                            <p>Follow us on social media to find out the latest updates on our progress.</p>
                                        </li>
                                        <li>
                                            <ul class="social-icons-list">
                                                <li class="social-icon"><a href="https://www.facebook.com/rentahobby"
                                                        target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                                <li class="social-icon"><a href="https://twitter.com/RentaHobby"
                                                        target="_blank"><i class="fab fa-twitter"></i></a></li>
                                                <li class="social-icon"><a href="https://instagram.com/rentahobby"
                                                        target="_blank"><i class="fab fa-instagram"></i></a></li>
                                                <li class="social-icon"><a href="https://linkedin.com" target="_blank"><i
                                                            class="fab fa-linkedin-in"></i></a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-copyright" id="footer-copyright">
                        <p class="footer-copyright-text">© {{ date('Y') }} RentAHobby. All rights reserved</p>
                    </div>
                </div>
            </footer>
            <script src="{{ asset('js/daterangepicker.js') }}"></script>
            <script src="{{ asset('js/iziToast.min.js') }}"></script>
            <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
            <script src="{{ asset('js/additional-methods.min.js') }}"></script>
            <script src="{{ asset('js/custom/common.js') }}"></script>
            @yield('scripts')
            <script>
                jQuery(document).ready(function() {
                    jQuery(".search-click").click(function() {
                        jQuery(".search-input-box").addClass("search-open");
                    });
                    jQuery(".icon-range").click(function() {
                        jQuery(".search-input-box").removeClass("search-open");
                    });

                    jQuery('#searchProduct').click(function(e) {
                        e.preventDefault();
                        let searchText = (jQuery('input[name="product"]').val()).trim();
                        jQuery('input[name="product"]').val(searchText);
                        jQuery('#productSearchForm').submit();
                    });
                });
            </script>
        </body>

        </html>
