{{-- <div class="pro-slider-section">
    <div class="container">
        <div class="custom-slider">
            @foreach (getParentCategory() as $k => $category)
            <div class="product-collection-slide @if (isset($filters) && in_array($category->id, $filters['categories'])) active @endif">
                <a href="{{ route('index', ["category[$k]" => $category->id]) }}">
@if ($category->category_image_url)
<span><img src="{{ $category->category_image_url }}" alt="pro-img" title="{{$category->name}}"></span>
@else
<span><img src="{{ asset('img/Accessories.svg') }}" alt="pro-img" title="{{$category->name}}"></span>
@endif
<p>{{$category->name}}</p>
</a>
</div>
@endforeach
</div>
</div>
</div> --}}

<section class="home-sec">
    <div class="container">
        <div class="home-wrapper">
            <div class="section-heading">
                <p>Rent the Perfect </p>
                <h2>Dress for Every Occasion</h2>
            </div>
            <div class="home-filter-product">
                <form action="{{ route('index') }}" method="GET">
                    <input type="hidden" name="country" id="country">
                    <input type="hidden" name="state" id="state">
                    <input type="hidden" name="city" id="city">
                    <input type="hidden" name="rating" id="rating">
                    @csrf
                    <div class="home-filter-box">
                        <div class="filter-head">
                            <h3>Filter</h3>
                        </div>
                        <div class="home-filter-inner">
                            <h4>Product category</h4>
                            <div class="filter-categories category-hight-fix">

                                @foreach (getParentCategory() as $key => $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $category->id }}"
                                            id="category-check-{{ $key }}" name="Category[]"
                                            @if (in_array($category->id, request()->input('Category', []))) checked @endif>
                                        <label class="form-check-label"
                                            for="category-check1">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="home-filter-inner">
                            <h4>Brand</h4>
                            <div class="filter-categories category-hight-fix">
                                @foreach (getBrands() as $key => $brand)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $brand->id }}"
                                            id="brand-check-{{ $key }}" name="Brand[]"
                                            @if (in_array($brand->id, request()->input('Brand', []))) checked @endif>
                                        <label class="form-check-label"
                                            for="brand-check-{{ $key }}">{{ $brand->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- <div class="home-filter-inner">
                             <h4>Price Range</h4>
                            <div class="filter-categories">
                                <div class="">
                                    <div class="price-range-box">
                                        <div class="form-group">
                                            <div class="formfield">
                                                <input type="text" name="min" id="" placeholder="min"
                                                    class="form-control">
                                                <span class="left-form-icon">
                                                    <img src="{{ asset('front/images/dollar-icon.svg') }}" alt="img">
                        </span>
                    </div>
            </div>
            <div class="form-group">
                <div class="formfield">
                    <input type="text" name="max" id="" placeholder="max"
                        class="form-control">
                    <span class="left-form-icon">
                        <img src="{{ asset('front/images/dollar-icon.svg') }}" alt="img">
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div> --}}
                        <div class="home-filter-inner">
                            <h4>Price</h4>
                            <div class="custom-wrapper filter-range-wrapper">
                                <div class="price-input-container">
                                    <div class="price-input">
                                        <div class="price-field left">
                                            <input type="number" name="min_value" id="selectedMinValue"
                                                class="min-input" value="{{ request()->input('min_value', 0) }}"
                                                readonly>
                                        </div>
                                        <div class="price-field right">
                                            <input type="number" name="max_value" id="selectedMaxValue"
                                                class="max-input" value="{{ request()->input('max_value', 10000) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="slider-container">
                                        <div class="price-slider"
                                            style="@if (request()->has('style')) {{ request()->style }} @endif">
                                        </div>
                                    </div>
                                </div>

                                <div class="range-input">
                                    <input type="range" class="min-range" min="0" max="10000"
                                        value="{{ request()->input('min_value', 0) }}" step="1" readonly>
                                    <input type="range" class="max-range" min="0" max="10000"
                                        value="{{ request()->input('max_value', 10000) }}" step="1" readonly>
                                </div>
                            </div>

                        </div>

                        {{-- <h4>Status</h4>
                            <div class="filter-categories">
                                <div class="stock-status">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="category-status1">
                                        <label class="form-check-label" for="category-status1">Stock</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="category-status2">
                                        <label class="form-check-label" for="category-status2">Out of Stock</label>
                                    </div>
                                </div>
                            </div> --}}
                        <div class="home-filter-inner">
                            <h4>Size</h4>
                            <div class="filter-categories category-hight-fix">
                                @foreach (getAllsizes() as $key => $size)
                                    <div class="form-check">
                                        <label for="size-filter-{{ $key }}" class="size-filter">
                                            <input class="form-check-input" type="checkbox" value="{{ $size->name }}"
                                                id="size-check-{{ $key }}" name="Size[]"
                                                @if (in_array($size->name, request()->input('Size', []))) checked @endif>
                                            <label class="form-check-label"
                                                for="size-check-{{ $key }}">{{ $size->name }}</label>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <!-- </div> -->
                        <div class="home-filter-inner">
                            <h4>Rating</h4>
                            <div class="filter-categories">
                                <div class="filter-rating-box">
                                    <div class="filter-rating-inner" data-value="1">
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div class="filter-rating-inner" data-value="2">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div class="filter-rating-inner" data-value="3">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div class="filter-rating-inner" data-value="4">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div class="filter-rating-inner" data-value="5">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <h4>Date range</h4>
                            <div class="form-group date-range-field">
                                <div class="formfield">
                                    <input type="text" name="filter_date" id="daterange"
                                        placeholder="Enter date range" class="form-control daterange-cus">
                                    <label for="daterange" class="form-icon">
                                        <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img">
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="home-filter-inner">
                            <h4>Locations</h4>
                            <div class="filter-categories">
                                <div class="filter-location-box">
                                    <div class="form-group">
                                        <label for=""><img src="{{ asset('front/images/aim-icon.svg') }}"
                                                alt="img"> Use current
                                            Location</label>
                                        <div class="formfield">
                                            {{-- <input type="text" placeholder="Your Location" class="form-control"
                                                id="filter_address" name="complete_address" value="{{request()->country request()->state request()->city}}"> --}}
                                            <input type="text" placeholder="Your Location" class="form-control"
                                                id="filter_address" name="complete_address"
                                                value="{{ request()->country ? request()->country . ' ' : '' }}{{ request()->state ? request()->state . ' ' : '' }}{{ request()->city ? request()->city : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <h4>Area Range</h4>
                            <div class="filter-categories">
                                <div class="filter-range-box">
                                    <input type="text" class="js-range-slider" name="my_range" value=""
                                        data-skin="round" data-type="double" data-min="0" data-max="1000"
                                        data-grid="false" />
                                </div>
                            </div> --}}
                        </div>
                        <div class="home-filter-fotter">
                            <div class="filter-fotter-btns">
                                <a href="{{ route('index') }}" id="actionButton"
                                    class="button outline-btn">Cancel</a>
                                {{-- <a href="javascript:void(0)" class="button primary-btn">Apply Filter</a> --}}
                                <button type="submit" class="button primary-btn">Apply Filter</button>
                            </div>
                        </div>
                        <div class="small-slidebar-icon">
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon1.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon2.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon3.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon4.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon5.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon6.svg') }}" alt="img">
                            </span>
                            <span>
                                <img src="{{ asset('front/images/sidebar-icon7.svg') }}" alt="img">
                            </span>
                        </div>
                        <div class="sidebar-expand-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18"
                                viewBox="0 0 26 18" fill="none">
                                <path
                                    d="M24.8854 9.15808L17.2806 16.763C17.0074 17.082 16.5273 17.1192 16.2083 16.8459C15.8893 16.5727 15.8521 16.0926 16.1254 15.7736C16.1509 15.7439 16.1786 15.7161 16.2083 15.6907L22.5127 9.37865H0.901088C0.481121 9.37865 0.140625 9.03815 0.140625 8.61812C0.140625 8.19809 0.481121 7.85766 0.901088 7.85766H22.5127L16.2083 1.55325C15.8893 1.28007 15.8521 0.799974 16.1254 0.48098C16.3986 0.161985 16.8787 0.1248 17.1977 0.398046C17.2274 0.423534 17.2552 0.451242 17.2806 0.48098L24.8855 8.08587C25.1803 8.38239 25.1803 8.86143 24.8854 9.15808Z"
                                    fill="#1B1B1B" />
                            </svg>
                        </div>
                    </div>
                </form>

                {{-- Product listing here --}}

                {{-- @dd($products); --}}
                <div class="home-product-main">
                    <div class="home-product-box">
                        @if ($products && $products->count() > 0)
                            @foreach ($products as $product)
                                <div class="product-card">
                                    <div class="product-img-box">
                                        <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}"
                                            class="productLink">
                                            @if ($product->thumbnailImage && $product->thumbnailImage->file_path)
                                                <img src="{{ $product->thumbnailImage->file_path }}"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ asset('front/images/pro-0.png') }}"
                                                    alt="{{ $product->name }}">
                                            @endif
                                        </a>
                                        <div class="product-card-like"
                                            onclick="addToWishlist(this, {{ $product->id }})">
                                            @if ($product->favorites && $product->favorites->count() > 0)
                                                <i class="fa-solid fa-heart active"></i>
                                            @else
                                                <i class="fa-regular fa-heart"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-card-detail">
                                        <p>{{ $product->name }}</p>
                                        <h4>${{ $product->rent_day }}/day</h4>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="list-empty-box">
                                <img src="{{ asset('front/images/no-products.svg') }}">
                                <h3 class="text-center">Your Query is empty</h3>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </div>
    </div>
    </div>
</section>


<!-- <section class="three-feature-sec">
    <div class="container">
        <div class="three-feature-wrapper">
            <div class="three-feature-box">
                <img src="{{ asset('front/images/easy-return-icon.svg') }}" alt="easy-return" width="52"
                    height="52">
                <h4>Easy Returns</h4>
            </div>
            <div class="three-feature-box">
                <img src="{{ asset('front/images/quality-check-icon.svg') }}" alt="quality-check" width="52"
                    height="52">
                <h4>Quality Check & Hygiene</h4>
            </div>
            <div class="three-feature-box">
                <img src="{{ asset('front/images/secure-payment-icon.svg') }}" alt="secure-payment" width="52"
                    height="52">
                <h4>Secure Payment</h4>
            </div>
        </div>
    </div>
</section> -->



@push('scripts')
    <script>
        const customDateFormat = 'MM/DD/YYYY';

        jQuery(function() {
            const date = '{{ request()->get('
                    filter_date ') }}';
            let start, end;

            if (date) {
                const dateParts = date.split(' - ').map(part => part.trim());
                start = moment(dateParts[0], customDateFormat);
                end = moment(dateParts[1], customDateFormat);
            } else {
                start = moment();
                end = moment();
            }

            jQuery('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                autoUpdateInput: false,
                locale: {
                    format: customDateFormat,
                    separator: ' - ',
                },
            });

            if (date) {
                jQuery('#daterange').val(date);
            }
            jQuery('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format(customDateFormat) + ' - ' + picker.endDate.format(
                    customDateFormat));
            });

            jQuery('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        $('.navbar-toggler').on('click', function() {
            $(".navbar-toggler").toggleClass('open');
        });
        $('.send-mail-open').on('click', function() {
            $(".invite-member-popup").toggleClass('open');
        });

        $('.sidebar-expand-btn').on('click', function() {
            $(".home-filter-box").addClass('expand');
        });
        $('.filter-fotter-btns').on('click', function() {
            $(".home-filter-box").removeClass('expand');
        });



        $(document).ready(function() {
            $('.product-slider').slick({
                dots: false,
                infinite: false,
                draggable: true,
                arrow: true,
                slidesToShow: 4,
                prevArrow: $('.prev-product-btn'),
                nextArrow: $('.next-product-btn'),
                responsive: [{
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        }
                    }

                ]
            });
        });

        $(document).ready(function() {
            $('.host-cal-slider').slick({
                dots: false,
                infinite: true,
                draggable: true,
                arrow: true,
                slidesToShow: 1,
                prevArrow: $('.prev-banner-btn'),
                nextArrow: $('.next-banner-btn'),
                responsive: [{
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }

                ]
            });
        });

        var $range = $(".js-range-slider"),
            $from = $(".from"),
            $to = $(".to"),
            range,
            min = $range.data('min'),
            max = $range.data('max'),
            from,
            to;

        var updateValues = function() {
            $from.prop("value", from);
            $to.prop("value", to);
        };

        $range.ionRangeSlider({
            onChange: function(data) {
                from = data.from;
                to = data.to;
                updateValues();
            }
        });

        range = $range.data("ionRangeSlider");
        var updateRange = function() {
            range.update({
                from: from,
                to: to
            });
        };

        $from.on("input", function() {
            from = +$(this).prop("value");
            if (from < min) {
                from = min;
            }
            if (from > to) {
                from = to;
            }
            updateValues();
            updateRange();
        });

        $to.on("input", function() {
            to = +$(this).prop("value");
            if (to > max) {
                to = max;
            }
            if (to < from) {
                to = from;
            }
            updateValues();
            updateRange();
        });
    </script>
    <script>
        const rangevalue = document.querySelector(".slider-container .price-slider");

        document.addEventListener('DOMContentLoaded', function() {
            const priceInputValue = document.querySelectorAll(".price-input input");
            const rangeInputValue = document.querySelectorAll(".range-input input");
            const rangeValue = document.querySelector(".price-slider");
            const priceGap = 100; // Define a minimum gap between min and max values

            function updateRangePosition(minp, maxp) {
                const value1 = rangeInputValue[0].max;
                rangeValue.style.left = `${(minp / value1) * 100}%`;
                rangeValue.style.right = `${100 - (maxp / value1) * 100}%`;
            }

            priceInputValue.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    let minp = parseInt(priceInputValue[0].value);
                    let maxp = parseInt(priceInputValue[1].value);

                    if (minp < 0) {
                        alert("Minimum price cannot be less than 0");
                        priceInputValue[0].value = 0;
                        minp = 0;
                    }
                    if (maxp > 10000) {
                        alert("Maximum price cannot be greater than 10000");
                        priceInputValue[1].value = 10000;
                        maxp = 10000;
                    }
                    if (minp > maxp - priceGap) {
                        priceInputValue[0].value = maxp - priceGap;
                        minp = maxp - priceGap;
                    }

                    if (e.target.classList.contains("min-input")) {
                        rangeInputValue[0].value = minp;
                    } else {
                        rangeInputValue[1].value = maxp;
                    }

                    updateRangePosition(minp, maxp);
                });
            });

            rangeInputValue.forEach((range, index) => {
                range.addEventListener("input", (e) => {
                    let minVal = parseInt(rangeInputValue[0].value);
                    let maxVal = parseInt(rangeInputValue[1].value);

                    if (maxVal - minVal < priceGap) {
                        if (e.target.classList.contains("min-range")) {
                            rangeInputValue[0].value = maxVal - priceGap;
                        } else {
                            rangeInputValue[1].value = minVal + priceGap;
                        }
                    } else {
                        priceInputValue[0].value = minVal;
                        priceInputValue[1].value = maxVal;

                        document.getElementById('selectedMinValue').value = minVal;
                        document.getElementById('selectedMaxValue').value = maxVal;
                        document.getElementById('styleForSlider').value =
                            `left:${(minVal / rangeInputValue[0].max) * 100}%; right:${100 - (maxVal / rangeInputValue[1].max) * 100}%;`;

                        updateRangePosition(minVal, maxVal);
                    }
                });
            });
        });
    </script>
    <script>
        function initAutocomplete() {

            var input = document.getElementById('filter_address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();


                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];

                    if (addressType === 'country') {
                        $('#country').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'administrative_area_level_1') {
                        $('#state').val(place.address_components[i].long_name);
                    }
                    if (addressType === 'locality') {
                        $('#city').val(place.address_components[i].long_name);
                    }
                }
            });
        }

        initAutocomplete();

        // disable the enter keyword
        $(document).ready(function() {
            $(document).on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            if (window.location.search) {
                $('#actionButton').text('Clear');
            }

            $('.filter-rating-inner').click(function() {
                var value = $(this).data('value');
                $('.filter-rating-inner').removeClass('active');
                $(this).addClass('active').addClass('active');

                $('#rating').val(value);
            });

            function getQueryParam(param) {
                var urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }
            var rating = getQueryParam('rating');
            if (rating) {
                $('.filter-rating-inner').each(function() {
                    if ($(this).data('value') == rating) {
                        $('#rating').val(rating);
                        $(this).addClass('active');
                    }
                });
            }


        });
    </script>
@endpush
