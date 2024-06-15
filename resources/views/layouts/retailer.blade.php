<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name', 'Chere') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/slick.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-front.css') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}">

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
        const dateTimeFormat = "{{ $global_js_date_time_format }}";
        const dateSeparator = " {{ $global_date_separator }} ";
        const calendarTimeGap = parseInt("{{ $global_calendar_time_gap }}");
        const dateOptions = {
            locale: {
                format: "{{ $global_js_date_format }}",
                separator: " {{ $global_date_separator }} ",
            },
            autoUpdateInput: false,
            drops: 'down',
            opens: 'right',
        };
        var loaderIcon = '<span class="loader" id="loader"><img src="{{ asset('img/loader-icon.svg') }}"></span>';
    </script>
    @yield('links')
</head>

<body>
    <!--  cstm header start-->
    <div class="main_header">
        <div class="cheader d-flex align-items-center justify-content-between">
            <div class="logo_cstm"> <img src="{{ asset('front/images/logo.svg') }}"> </div>
            <ul class="ul_header d-flex align-items-center">
                <li class="link_head">
                    <a href="javascript: void(0);"><img src="{{ asset('front/images/chat.svg') }}"></a>
                </li>
                <li class="link_head user-profile">
                    <a href="javascript: void(0);"><img src="{{ asset('front/images/user.svg') }}"></a>
                    @auth
                        <div class="user-profile-dropdown">
                            <ul>
                                <li><a href="{{ route('retailer.profile') }}"><i class="fa-regular fa-user"></i>Profile</a>
                                </li>
                                <li><a href="{{ route('retailer.orders') }}"><i class="fa-regular fa-file-lines"></i>Order
                                        History</a></li>
                                <li><a href="{{ route('retailer.bankdetail') }}"><i
                                            class="fa-regular fa-file-lines"></i>Bank Details</a></li>
                                <li><a href="{{ route('retailer.notifications') }}"><i
                                            class="fa-regular fa-bell"></i>Notifications</a></li>
                                <li><a href="{{ route('logout') }}"><i
                                            class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <div class="user-profile-dropdown">
                            <ul>
                                <li><a href="{{ route('login') }}"><i class="fa-regular fa-user"></i>Sign in</a></li>
                            </ul>
                        </div>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
    <!-- End cstm header -->
    <!-- main-wrapper start -->
    <div class="mainwrapper d-flex">
        <!-- sidebar menus -->
        @include('layouts.retailer_sidebar')
        <!-- End sidebar menus -->
        <!-- Right content area -->
        @yield('content')
        <!-- End Right content area -->
    </div>
    <!--JS-->
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}?ver={{ now() }}"></script>
    <script>
        $(document).ready(function() {
            $(".user-profile").click(function() {
                $('.user-profile-dropdown').toggleClass("show");
            });

        });
    </script>
    @yield('modal')
    @stack('scripts')
</body>

</html>
