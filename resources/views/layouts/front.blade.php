<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Home') }}</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/slick.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}?ver={{ now() }}">
    <link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom-front.css') }}?ver={{ now() }}" />
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}?ver={{ now() }}">
    <link rel="stylesheet" href="{{ asset('front/css/custom.css') }}">
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('img/fav.png') }}"> --}}
    <script>
        const APP_URL = "{{ url('') }}";
        const login_url = '{{ route('login') }}';
        const userId = '{{ jsencode_userdata(auth()->id()) }}';
        const url = "{{ route('addfavorite') }}";
        const errorTitle = "{{ __('favorite.error') }}";
        const allowedExtension = @php echo $global_js_image_extension; @endphp;
        const allowedProofExtension = @php echo $global_js_proof_extension; @endphp;
        const uploadFileSize = parseInt("{{ $global_js_file_size }}");
        const allowedExtensionMessage = allowedExtension.join(',');
        const uploadFileSizeInMb = uploadFileSize / 1000000 + 'Mb';
        const fileNameLength = parseInt("{{ $global_preview_file_name_length }}");
        const minPickedUpImageCount = parseInt("{{ $global_min_picked_up_image_count }}");
        const maxPickedUpImageCount = parseInt("{{ $global_max_picked_up_image_count }}");
        const minReturnedImageCount = parseInt("{{ $global_min_returned_image_count }}");
        const maxReturnedImageCount = parseInt("{{ $global_max_returned_image_count }}");
        const minDisputeImageCount = parseInt("{{ $global_min_dispute_image_count }}");
        const maxDisputeImageCount = parseInt("{{ $global_max_dispute_image_count }}");
        const minProductImageCount = parseInt("{{ $global_min_product_image_count }}");
        const dateFormat = "{{ $global_js_date_format }}";

        const dateOptions = {
            locale: {
                format: "{{ $global_js_date_format }}",
                separator: " {{ $global_date_separator }} ",
            },
            autoUpdateInput: false,
            drops: 'down',
            opens: 'right',
        };
        dateOptions["minDate"] = new Date();
        let selectedLocation = '{{ isset($selectedLocation) ? $selectedLocation : '' }}';
        var loaderIcon = '<span class="loader" id="loader"><img src="{{ asset('img/loader-icon.svg') }}"></span>';
        var rentPrice = '';
    </script>
    @yield('links')
</head>

<body>
    @include('layouts.header')

    <main class="main @if (isset($layout_class)) single_product @endif">
        @yield('content')
    </main>

    {{-- Notifications --}}
    {{-- @include('layouts.notifications') --}}

    <footer>
        <div class="container">
            <div class="copyright footer-section">
                <p class="small-font">© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved</p>
                <div class="copyright-links">
                    <ul>
                        @foreach ($cms as $cms)
                            <li>
                                <a href="{{ route('view', ['slug' => $cms->slug]) }}">{{ $cms->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div id="loader">
        <div class="cube-folding">
            <span class="leaf1"></span>
            <span class="leaf2"></span>
            <span class="leaf3"></span>
            <span class="leaf4"></span>
        </div>
    </div>
    @include('layouts.filter_popup')

    <!--JS-->
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/slick.js') }}"></script>
    <script src="{{ asset('front/js/slick.min.js') }}"></script>
    <script src="{{ asset('js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOyw9TNt8YzANQjJMjjijfr8MC2DV_f1s&libraries=places">
    </script>
    <script src="{{ asset('js/custom/customer-search-location.js') }}"></script>
    <script src="{{ asset('js/custom/product-list.js') }}"></script>
    <script src="{{ asset('js/custom/add-wishlist.js') }}"></script>
    <script src="{{ asset('js/custom/common.js') }}?ver={{ now() }}"></script>
    <script>
        $('.custom-slider').slick({
            centerPadding: '60px',
            slidesToShow: 10,
            responsive: [{
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 7
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 4
                    }
                }
            ]
        });
        // single product slider
        $('.profile-custom-slider').slick({
            centerPadding: '0px',
            slidesToShow: 4,
            autoplaySpeed: 5000,

            responsive: [{
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        //For sticky header
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 250) {
                $("header").addClass("fixed");
            } else {
                $("header").removeClass("fixed");
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $("a.notification").click(function() {
                $('body').addClass("show-noti");
            });
            $(".notifications-heading button").click(function() {
                $('body').removeClass("show-noti");
            });

            // $(".user-profile").click(function() {
            //     $('.user-profile-dropdown').toggleClass("show");
            // });
            var $el = $(".user-profile");
            var $ee = $(".user-profile-dropdown");
            $el.click(function(e) {
                e.stopPropagation();
                $(".user-profile-dropdown").toggleClass('show');
            });
            $(document).on('click', function(e) {
                if (($(e.target) != $el) && ($ee.hasClass('show'))) {
                    $ee.removeClass('show');
                    // console.log("yes");
                }
            });
            var $elw = $(".cstm-select-bx");
            var $eew = $(".select-btn");
            $elw.click(function(e) {
                e.stopPropagation();
                $(".select-btn").addClass('open');
            });
            $(document).on('click', function(e) {
                if (($(e.target) != $elw) && ($eew.hasClass('open'))) {
                    $eew.removeClass('open');
                    // console.log("yes");
                }
            });
            $("#loader").addClass("load-complete");
            $(".toggle").click(function() {
                $(this).toggleClass("activate");
            });
        });
    </script>
    {{-- header country checkbox --}}
    <script>
        jQuery(document).on("click", ".parent", function() {
            var parentid = jQuery(this).val();
            var checkedvalue = jQuery(this).is(':checked');

            if (checkedvalue == true) {
                var cityid = $(this).parent().siblings('.child' + parentid).children('.child' + parentid).prop(
                    'checked', true);
            } else {
                var cityid = $(this).parent().siblings('.child' + parentid).children('.child' + parentid).prop(
                    'checked', false);
            }
 
        });
        // jQuery('body').bind('cut paste', function(event) {
        //     return false;
        // });
    </script>
    {{-- open modal on specific url --}}
    {{-- @if (request()->url() == 'http://chere-internal.in/lend')
        <script src="{{ asset('js/custom/product-add-edit.js') }}?ver={{ now() }}"></script>
    @endif --}}
    @if (isset($openModal) || isset($_COOKIE['lend']))
        <script>
            jQuery(document).ready(function() {
                var url = "{{ request()->url() }}";
                // setCookie('lendurl', '', url);


                const commision_type = "{{ adminsetting()->type }}"
                const commision = "{{ adminsetting()->value }}"
                const maxProductImageCount = parseInt("{{ $global_max_product_image_count }}");
                const is_bankdetail = "{{ auth()->user()->vendorBankDetails }}";
                const checkdocuments = "{{ count(auth()->user()->documents) }}";
                const checkdocumentstatus = "{{ auth()->user()->is_approved }}";
                const product_check = "{{ count(auth()->user()->products) }}";
                const image_store_url = "{{ route('image.store') }}";
                var htmlForm = `@include('retailer.include.product-from', ['product' => new App\Models\Product()])`;
                jQuery('#ajax-form-html').html(htmlForm);
                jQuery('#NproductModal').modal('show');

                // submit form
                jQuery('#addProduct').submit(function(e) {
                    e.preventDefault();
                    handleValidation('addProduct', rules, messages);
                    if ($('#addProduct').valid()) {
                        productformData = new FormData($('form#addProduct').get(0));
                        var url = jQuery('#addProduct').attr('action');
                        response = ajaxCall(url, 'post', productformData)
                        response.then(response => {
                            if (response.success) {
                                setCookie('img_token', '', '-1');
                                //console.log(response.checkdocuments, "check response here");
                                // if (response.checkdocuments == 0) {
                                //     jQuery('#mutlistepForm1').modal("show");
                                //     jQuery("#NproductModal").modal("hide");

                                // } else

                                getId = response.product;
                                localStorage.setItem("getid", getId);
                                if (!response.is_bankdetail && response.product) {
                                    jQuery('#mutlistepForm2').modal("show");
                                    jQuery("#NproductModal").modal("hide");
                                } else {
                                    // window.location.href = response.url

                                    window.location.replace(APP_URL + '/' + 'products')
                                }
                            }

                        }).catch(error => {
                            return iziToast.error({
                                title: 'Error',
                                message: error.msg,
                                position: 'topRight'
                            });
                        });


                    }

                });
            });
        </script>
        @php setcookie("lend", "", time() - 3600);@endphp
    @endif

    {{-- end header --}}
    @stack('scripts')
</body>

</html>
