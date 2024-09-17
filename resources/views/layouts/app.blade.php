<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Login') }}</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/slick.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-front.css') }}?ver={{ now() }}" />
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />

    <!-- <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}"> -->
    <script>
        const APP_URL = "{{ url('') }}";
    </script>
    <style>
        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>
    <div class="overlay" id="overlay"></div>
    <!-- header Section Starts Here  -->
    <header class="cust-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="cust-nav-header-sec">
                    <div class="logo mobile-logo">
                        <a class="navbar-brand" href="{{ url('/') }}"><img
                                src="{{ asset('front/images/HUSH HUSH CLOSET.svg') }}" alt="logo" width="91"
                                height="63"></a>

                    </div>
                    <div class="collapse navbar-collapse cust-navbar-header" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#"></a>
                            </li>
                        </ul>
                    </div>
                    @if (auth()->user())
                        <div class="header-cart">
                            {{-- <ul>
                                <li>
                                    <a href="#"><i class="fa-regular fa-user"></i></a>
                                </li>
                            </ul> --}}
                            <div class="dropdown">
                                <div class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"><img
                                                src="{{ asset('front/images/logout-icon.svg') }}"
                                                alt="img">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button> -->
                </div>
            </div>
        </nav>
    </header>

    <main class="main login-pages">
        @yield('content')
    </main>
    <footer class="white-bg">
        <div class="container">
            <div class="copyright footer-section">
                <p class="small-font">© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved</p>
                <div class="copyright-links">
                    <ul>
                        <li>
                            <a href="#">About Us</a>
                        </li>
                        <li>
                            <a href="#">FAQ</a>
                        </li>
                        <li>
                            <a href="#">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#">Terms & Conditions</a>
                        </li>
                        <li>
                            <a href="#">Contact Us</a>
                        </li>
                        <li>
                            <a href="#">Help Support</a>
                        </li>
                        <li>
                            <a href="#">Cancellation and Refund policies</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!--JS-->
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/slick.js') }}"></script>
    <script src="{{ asset('front/js/slick.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}?ver={{ now() }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>

    {{-- <script>
        jQuery(document).ready(function() {
            var url = "{{ URL::previous() ? URL::previous() : '' }}"
            console.log(url, "ASdasd")
            if (url == "http://chere-internal.in/lend") {
                setCookie('lend', url);
            }
        });
    </script> --}}
    @stack('scripts')

</body>

</html>
