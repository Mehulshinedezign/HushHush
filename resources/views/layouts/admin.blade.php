<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
@include("favicon")
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chere') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" async defer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toaster.min.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @yield('links')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <!-- <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}"> -->

    @stack('styles')
    <style>
        .tox-notifications-container {
            display: none;
        }
    </style>
    <script>
        const APP_URL = "{{ url('') }}";
        const fileNameLength = parseInt("{{ $global_preview_file_name_length }}");
        const uploadFileSize = parseInt("{{ $global_js_file_size }}");
        const minProductImageCount = parseInt("{{ $global_min_product_image_count }}");
        const maxProductImageCount = parseInt("{{ $global_max_product_image_count }}");
        const minPickedUpImageCount = parseInt("{{ $global_min_picked_up_image_count }}");
        const maxPickedUpImageCount = parseInt("{{ $global_max_picked_up_image_count }}");
        const minReturnedImageCount = parseInt("{{ $global_min_returned_image_count }}");
        const maxReturnedImageCount = parseInt("{{ $global_max_returned_image_count }}");
        const minDisputeImageCount = parseInt("{{ $global_min_dispute_image_count }}");
        const maxDisputeImageCount = parseInt("{{ $global_max_dispute_image_count }}");
        const allowedExtension = @php echo $global_js_image_extension; @endphp;
        const allowedProofExtension = @php echo $global_js_proof_extension; @endphp;
        const allowedExtensionMessage = allowedExtension.join(',');
        const uploadFileSizeInMb = uploadFileSize / 1000000 + 'Mb';
        const dateFormat = "{{ $global_js_date_format }}";
        const dateSeparator = " {{ $global_date_separator }} ";
        const dateOptions = {
            locale: {
                format: "{{ $global_js_date_format }}",
                separator: " {{ $global_date_separator }} ",
            },
            autoUpdateInput: false,
            drops: 'down',
            opens: 'right',
        };
    </script>
</head>

<body class="@if (session('menu_option', 'open') == 'closed') sidebar-mini @endif">
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline d-block d-xl-none">
                    <ul class="navbar-nav mr-3">
                        <li>
                            <a href="#" id="toggle" data-toggle="sidebar"
                                class="nav-link nav-link-lg collapse-btn">
                                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 218"
                                    width="20" height="12">
                                    <title>menu-svg</title>
                                    <style>
                                        .s0 {
                                            fill: #000000
                                        }
                                    </style>
                                    <path id="Layer" class="s0"
                                        d="m337 0.1c7.9 0 14.2 6.3 14.2 14.2c0 7.8-6.3 14.2-14.2 14.2h-322c-7.9 0-14.2-6.4-14.2-14.2c0-7.9 6.3-14.2 14.2-14.2zm0 94.7c7.8 0 14.2 6.4 14.2 14.2c0 7.8-6.4 14.2-14.2 14.2h-322.1c-7.8 0-14.2-6.4-14.2-14.2c0-7.8 6.4-14.2 14.2-14.2zm0 94.7c7.9 0 14.2 6.4 14.2 14.2c0 7.9-6.3 14.2-14.2 14.2h-322c-7.8 0-14.2-6.3-14.2-14.2c0-7.8 6.4-14.2 14.2-14.2z" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-brand mx-auto">
                    <a href="{{ route('admin.dashboard') }}">
                        {{-- <img alt="image" src="{{ asset('img/logo.svg') }}" class="header-logo" /> --}}
                        <h4>NUDORA</h4>
                    </a>
                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img src="{{ auth()->user()->frontend_profile_url }}" alt="user-img"
                                class="user-img-radious-style">
                            <span class="d-sm-none d-lg-inline-block"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">{{ auth()->user()->name }}</div>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> {{ __('common.profile') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('common.signout') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            @include('layouts.admin_sidebar')
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/toaster.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <script>
        const menuUrl = "{{ route('admin.menusetup') }}"
        jQuery(document).ready(function() {
            @if (session('menu_option', 'open') == 'closed')
                jQuery.each(jQuery('.main-sidebar .sidebar-menu li.active'), function(i, val) {
                    var $activeAnchors = $(val).find('a:eq(0)');
                    $activeAnchors.addClass('toggled');
                    $activeAnchors.next().show();
                    $activeAnchors.trigger('click');
                });
            @endif

            jQuery("#toggle").click(function() {
                var data = {
                    menu: 'open'
                };
                if (jQuery('body').hasClass('sidebar-mini')) {
                    data.menu = 'closed';
                    jQuery.each(jQuery('.main-sidebar .sidebar-menu li.active'), function(i, val) {
                        var $activeAnchors = $(val).find('a:eq(0)');
                        $activeAnchors.addClass('toggled');
                        $activeAnchors.next().show();
                        $activeAnchors.trigger('click');
                    });
                } else {
                    jQuery.each(jQuery('.main-sidebar .sidebar-menu li.active'), function(i, val) {
                        var $activeAnchors = $(val).find('a:eq(0)');
                        $activeAnchors.addClass('toggled');
                        $activeAnchors.next().show();
                    });
                }
                const menuSetup = ajaxCall(menuUrl, 'post', data);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
