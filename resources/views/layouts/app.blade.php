<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
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
    <link rel="stylesheet" href="{{ asset('css/custom-front.css') }}?ver={{ now() }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}">

    <style>
        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>
    <!-- header Section Starts Here  -->
    <header class="cust-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="cust-nav-header-sec">
                    <div class="logo mobile-logo">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{-- <img src="{{ asset('front/images/logo.svg') }}" alt="logo"> --}}
                            <h4>NUDORA</h4>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse cust-navbar-header" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="header-cart">
                        <ul>
                            <li>
                                {{-- <a href="#"><i class="fa-regular fa-user"></i></a> --}}
                            </li>
                        </ul>
                    </div>
                    {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> --}}
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
                <p class="small-font">© {{ date('Y') }} Chere. {{ __('All rights reserved') }}</p>
                <div class="copyright-links">
                    <ul>

                        @foreach ($cms as $cms)
                            <li>
                                <a href="{{ route('view', ['slug' => $cms->slug]) }}">{{ $cms->title }}</a>
                            </li>
                        @endforeach
                        {{-- <li>
                            <a href="{{ route('howitworks')}}">{{__('How it Works')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('aboutus') }}">{{__('About Us')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('faq') }}">{{__('FAQ')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('policies') }}">{{__('Privacy Policy')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('termsconditions') }}">{{__('Terms & Conditions')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('contactus') }}">{{__('Contact Us')}}</a>
                        </li> --}}
                        {{-- <li>
                            <a href="#"><img src="{{ asset('front/images/android-store.png') }}" alt="android-icon"></a>
                            <a href="#"><img src="{{ asset('front/images/play-store.png') }}" alt="play-icon"></a>
                        </li> --}}
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
