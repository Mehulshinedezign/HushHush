<section class="home-sec">
    <div class="container">
        <div class="home-wrapper">
            <div class="section-heading">
                <p>Rent the Perfect </p>
                <h2>Dress for Every Occasion</h2>
            </div>
            <div class="home-filter-product">
                <form action="{{ route('index') }}" method="GET" id="filters">
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
                                        <input class="form-check-input parent-category" type="checkbox"
                                            value="{{ $category->id }}" id="category-check-{{ $key }}"
                                            name="category[]" @if (in_array($category->id, (array) request()->input('category', []))) checked @endif>
                                        <label class="form-check-label" for="category-check-{{ $key }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>

                                    @php
                                        $childCategories = getChild($category->id);
                                    @endphp

                                    @if ($childCategories->isNotEmpty())
                                        <div class="child-categories" id="child-categories-{{ $key }}"
                                            style="display:none; margin-left: 20px;">
                                            @foreach ($childCategories as $child)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $child->id }}"
                                                        id="subcategory-check-child-{{ $child->id }}"
                                                        name="Subcategory[]"
                                                        @if (in_array($child->id, (array) request()->input('Subcategory', []))) checked @endif>
                                                    <label class="form-check-label"
                                                        for="subcategory-check-child-{{ $child->id }}">
                                                        {{ $child->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
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


                        <div class="home-filter-inner">
                            <h4>Price</h4>
                            <div class="custom-wrapper filter-range-wrapper">
                                <div class="price-input-container">
                                    <div class="price-input">
                                        <div class="price-field left">
                                            <span class="dollar-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                                            <input type="number" name="min_value" id="selectedMinValue"
                                                class="min-input" value="{{ request()->input('min_value', 0) }}">
                                        </div>
                                        <div class="price-field right">
                                            <span class="dollar-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                                            <input type="number" name="max_value" id="selectedMaxValue"
                                                class="max-input" value="{{ request()->input('max_value', 10000) }}">
                                        </div>
                                    </div>

                                    <!-- Error message div -->
                                    <div id="error-message" style="color: red; margin-top: 5px; display: none;"></div>
                                    <div class="slider-container">
                                        <div class="price-slider"
                                            style="@if (request()->has('style')) {{ request()->style }} @endif">
                                        </div>
                                    </div>
                                </div>

                                <div class="range-input">
                                    <input type="range" class="min-range" min="0" max="10000"
                                        value="{{ request()->input('min_value', 0) }}" step="1">
                                    <input type="range" class="max-range" min="0" max="10000"
                                        value="{{ request()->input('max_value', 10000) }}" step="1">
                                </div>
                            </div>

                        </div>


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



                        </div>
                        <div class="home-filter-inner">
                            <h4>Date range</h4>
                            <div class="form-group date-range-field">
                                <div class="formfield">
                                    <input type="text" name="filter_date" id="daterange-category"
                                        placeholder="Enter date range" class="form-control daterange-cus" readonly>
                                    <label for="daterange-category" class="form-icon">
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

                        </div>
                        <div class="home-filter-fotter">
                            <div class="filter-fotter-btns">
                                <a href="{{ route('index') }}" id="actionButton"
                                    class="button outline-btn">Cancel</a>
                                {{-- <a href="javascript:void(0)" class="button primary-btn">Apply Filter</a> --}}
                                <button type="submit" class="button primary-btn">Apply Filter</button>
                                {{-- @auth
                                @endauth
                                @guest
                                <a href="{{route('login')}}">
                                <span class="button primary-btn">Apply Filter</button>
                                </a>
                                @endguest --}}
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
                                {{-- <img src="{{ asset('front/images/sidebar-icon3.svg') }}" alt="img"> --}}
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_28_13240)">
                                        <path
                                            d="M21.458 4.29395C19.0971 1.93301 15.958 0.632812 12.6191 0.632812C9.28027 0.632812 6.14121 1.93301 3.78027 4.29395C1.41934 6.65488 0.119141 9.79395 0.119141 13.1328C0.119141 16.4717 1.41938 19.6107 3.78027 21.9717C6.14116 24.3327 9.28027 25.6328 12.6191 25.6328C15.958 25.6328 19.0971 24.3326 21.458 21.9717C23.8189 19.6107 25.1191 16.4717 25.1191 13.1328C25.1191 9.79395 23.8189 6.65493 21.458 4.29395ZM12.6191 12.3516C14.5577 12.3516 16.1348 13.9287 16.1348 15.8672C16.1348 17.4725 15.0529 18.8291 13.5802 19.2482C13.4744 19.2783 13.4004 19.3735 13.4004 19.4835V20.142C13.4004 20.5627 13.0771 20.9247 12.6569 20.9444C12.2082 20.9655 11.8379 20.6081 11.8379 20.1641V19.4833C11.8379 19.3736 11.7644 19.2784 11.6588 19.2484C10.1924 18.8314 9.11333 17.4851 9.10357 15.889C9.10098 15.463 9.43594 15.0985 9.86182 15.0863C10.3038 15.0736 10.666 15.4281 10.666 15.8672C10.666 16.9903 11.619 17.8951 12.7587 17.8154C13.7208 17.7482 14.5002 16.9688 14.5674 16.0067C14.647 14.8669 13.7422 13.9141 12.6191 13.9141C10.6806 13.9141 9.10352 12.337 9.10352 10.3984C9.10352 8.79312 10.1854 7.43657 11.6581 7.01743C11.7639 6.9873 11.8379 6.89214 11.8379 6.78213V6.12363C11.8379 5.70293 12.1612 5.34097 12.5814 5.32119C13.0301 5.3001 13.4004 5.65757 13.4004 6.10156V6.78228C13.4004 6.89199 13.4739 6.98721 13.5794 7.01724C15.0458 7.43423 16.125 8.78057 16.1347 10.3766C16.1373 10.8026 15.8023 11.1671 15.3765 11.1793C14.9345 11.192 14.5723 10.8375 14.5723 10.3984C14.5723 9.27529 13.6193 8.37051 12.4795 8.4502C11.5174 8.51743 10.7381 9.29683 10.6709 10.2589C10.5913 11.3987 11.496 12.3516 12.6191 12.3516Z"
                                            fill="#3A3A3A" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_28_13240">
                                            <rect width="25" height="25" fill="white"
                                                transform="translate(0.119141 0.632812)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                            <span>

                                <img src="{{ asset('front/images/sidebar-icon4.svg') }}" alt="img">
                            </span>
                            <span>
                                {{-- <img src="{{ asset('front/images/sidebar-icon5.svg') }}" alt="img"> --}}
                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_28_13249)">
                                        <path
                                            d="M20.3364 7.9407L16.2091 7.34095L14.3632 3.60096C13.6506 2.15673 11.589 2.15619 10.876 3.60091L9.03017 7.34095L4.90278 7.9407C3.30904 8.17224 2.67159 10.1326 3.8252 11.2572L6.81181 14.1685L6.10679 18.2791C5.83452 19.8663 7.5018 21.0785 8.92802 20.3289L12.6196 18.388L16.3113 20.3289C17.7392 21.0794 19.4041 19.8627 19.1324 18.2791L18.4274 14.1685L21.414 11.2572C22.5672 10.133 21.9308 8.17239 20.3364 7.9407Z"
                                            fill="#3A3A3A" />
                                        <path
                                            d="M6.69749 2.93492L5.70086 1.56324C5.4303 1.19097 4.90916 1.1084 4.5369 1.37896C4.16458 1.64947 4.08206 2.17061 4.35257 2.54292L5.3492 3.9146C5.6199 4.28706 6.14109 4.36934 6.51316 4.09888C6.88547 3.82842 6.96799 3.30723 6.69749 2.93492Z"
                                            fill="#3A3A3A" />
                                        <path
                                            d="M3.39046 15.2274C3.24822 14.7898 2.7782 14.5501 2.34041 14.6924L0.695149 15.2271C0.257454 15.3692 0.0179526 15.8394 0.16014 16.2771C0.30262 16.7156 0.773615 16.9542 1.21019 16.8121L2.8555 16.2775C3.29319 16.1354 3.53269 15.6652 3.39046 15.2274Z"
                                            fill="#3A3A3A" />
                                        <path
                                            d="M20.7007 1.37897C20.3284 1.10842 19.8073 1.19094 19.5367 1.56325L18.5401 2.93493C18.2695 3.30724 18.3521 3.82843 18.7244 4.09889C19.0969 4.3695 19.6179 4.28678 19.8884 3.91461L20.885 2.54294C21.1556 2.17062 21.073 1.64948 20.7007 1.37897Z"
                                            fill="#3A3A3A" />
                                        <path
                                            d="M24.543 15.2271L22.8977 14.6925C22.46 14.55 21.9899 14.7898 21.8477 15.2275C21.7054 15.6652 21.9449 16.1353 22.3827 16.2775L24.028 16.8122C24.4648 16.9542 24.9356 16.7154 25.078 16.2771C25.2202 15.8394 24.9807 15.3693 24.543 15.2271Z"
                                            fill="#3A3A3A" />
                                        <path
                                            d="M12.6185 21.6875C12.1583 21.6875 11.7852 22.0606 11.7852 22.5208V24.2121C11.7852 24.6723 12.1583 25.0454 12.6185 25.0454C13.0787 25.0454 13.4518 24.6723 13.4518 24.2121V22.5208C13.4518 22.0606 13.0787 21.6875 12.6185 21.6875Z"
                                            fill="#3A3A3A" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_28_13249">
                                            <rect width="25" height="25" fill="white"
                                                transform="translate(0.119141 0.632812)" />
                                        </clipPath>
                                    </defs>
                                </svg>
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
                    <div class="home-product-box" id="home-product-box">
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

                            <!-- Pagination Links -->
                        @else
                            <div class="list-empty-box">
                                <img src="{{ asset('front/images/Empty 1.svg') }}">
                                <h3 class="text-center">No Products Available</h3>
                            </div>
                        @endif
                    </div>



                    {{-- <div id="home-product-box" class="home-product-box">
                        @include('partials.product-cards', ['products' => $products])
                    </div> --}}

                    {{-- <div class="auto-load text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3"
                                fill="none"></circle>
                            <circle cx="50" cy="50" r="40" stroke="gray" stroke-width="3"
                                fill="none" stroke-dasharray="100" stroke-dashoffset="100"
                                transform="rotate(-90 50 50)">
                                <animate attributeName="stroke-dashoffset" values="100;0" dur="1s"
                                    repeatCount="indefinite"></animate>
                            </circle>
                        </svg>
                    </div> --}}




                </div>

            </div>

        </div>
    </div>
    </div>
    </div>
</section>




@push('scripts')
    <script>
        function getQueryParameters() {
            let params = new URLSearchParams(window.location.search);
            let queryStr = '';

            // Loop through all parameters and append them if they are not empty
            params.forEach((value, key) => {
                if (value) {
                    queryStr += `&${key}=${encodeURIComponent(value)}`;
                }
            });

            // Remove the leading '&' and return the parameters
            return queryStr ? queryStr.slice(1) : '';
        }

        function createPaginationUrl(page) {
            let baseUrl = window.location.origin + window.location.pathname;
            let queryParameters = getQueryParameters();

            // Append the page parameter
            let paginationUrl = baseUrl + '?page=' + page;

            // If there are other query parameters, append them
            if (queryParameters) {
                paginationUrl += '&' + queryParameters;
            }

            return paginationUrl;
        }

        $(document).ready(function() {
            var page = 1;
            var appendElements = true;

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
                    if (appendElements) {
                        page++;
                        loadMoreData(page);
                    }
                }
            });

            function loadMoreData(page) {
                let url = createPaginationUrl(page);
                console.log(url, 'gdhg');

                $.ajax({
                        url: url,
                        type: 'get',
                        beforeSend: function() {}
                    })
                    .done(function(response) {
                        $('.auto-load svg').hide();
                        if (response.data.html === '' || response.data.products_count === 0) {
                            appendElements = false;
                            return;
                        }
                        $('#home-product-box').append(response.data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occurred');
                    });
            }
        });
    </script>
    <script>
        const minInput = document.getElementById('selectedMinValue');
        const maxInput = document.getElementById('selectedMaxValue');
        const errorMessage = document.getElementById('error-message');

        function validateValues() {
            const minValue = parseFloat(minInput.value);
            const maxValue = parseFloat(maxInput.value);

            if (minValue >= maxValue) {
                errorMessage.textContent = 'The minimum value must be less than the maximum value.';
                errorMessage.style.display = 'block';
                return false;
            } else {
                errorMessage.style.display = 'none';
                return true;
            }
        }

        // Attach real-time validation to input events
        minInput.addEventListener('input', validateValues);
        maxInput.addEventListener('input', validateValues);

        // Attach validation check before form submission
        document.getElementById('filters').addEventListener('submit', function(event) {
            if (!validateValues()) {
                event.preventDefault();
            }
        });
    </script>
    <script>
        jQuery(document).ready(function() {
            // Handle parent category click
            $('.parent-category').on('change', function() {
                var parentId = $(this).val();
                var childDivId = '#child-categories-' + $(this).attr('id').split('-')[2];

                if ($(this).is(':checked')) {
                    // Show the child categories div if the parent category is checked
                    $(childDivId).slideDown();
                } else {
                    // Hide the child categories div if the parent category is unchecked
                    $(childDivId).slideUp();

                    // Uncheck all child checkboxes
                    $(childDivId).find('input[type="checkbox"]').prop('checked', false);
                }
            });

            // Initially show the child categories for any checked parent categories
            $('.parent-category:checked').each(function() {
                var childDivId = '#child-categories-' + $(this).attr('id').split('-')[2];
                $(childDivId).show();
            });
        });
    </script>
    <script>
        const customDateFormat = 'MM/DD/YYYY';


        jQuery(function() {
            const date = '{{ request()->get('filter_date') }}';
            let start, end;

            if (date) {
                const dateParts = date.split(' - ').map(part => part.trim());
                start = moment(dateParts[0], customDateFormat);
                end = moment(dateParts[1], customDateFormat);
            } else {
                start = moment();
                end = moment();
            }

            jQuery('.daterange-cus').each(function() {
                var option = 'right';
                if (jQuery(this).hasClass('custom-left-open')) {
                    option = 'left';
                }

                jQuery(this).daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    opens: option,
                    drops: 'auto',
                    minDate: moment(),
                    locale: {
                        format: customDateFormat,
                        separator: ' - ',
                    },
                });

                if (date) {
                    jQuery(this).val(date);
                }

                jQuery(this).on('apply.daterangepicker', function(ev, picker) {
                    jQuery(this).val(picker.startDate.format(customDateFormat) + ' - ' + picker
                        .endDate.format(customDateFormat));
                    if (jQuery(this).closest('form').attr('id') === 'searchForm') {
                        jQuery(this).closest('form').submit();
                    }
                });

                jQuery(this).on('cancel.daterangepicker', function(ev, picker) {
                    jQuery(this).val('');
                });
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
        $('.filter-head').on('click', function() {
            $(".home-filter-box").toggleClass('expand');
        });

        // $('.filter-head').on('click', function() {
        //     $(".home-filter-box").removeClass('expand');
        // });



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
            const priceGap = 10; // Define a minimum gap between min and max values

            function updateRangePosition(minp, maxp) {
                const value1 = rangeInputValue[0].max;
                rangeValue.style.left = `${(minp / value1) * 100}%`;
                rangeValue.style.right = `${100 - (maxp / value1) * 100}%`;
            }

            function sanitizeValue(value, min, max) {
                // Ensure value is a number and within the allowed range
                if (value === '' || isNaN(value)) {
                    return min;
                }
                value = parseInt(value);
                if (value < min) {
                    return min;
                }
                if (value > max) {
                    return max;
                }
                return value;
            }

            function setInitialValues() {
                priceInputValue.forEach((input) => {
                    if (input.value === '') {
                        input.value = '0';
                    }
                });
                rangeInputValue.forEach((range) => {
                    if (range.value === '') {
                        range.value = '0';
                    }
                });
            }

            setInitialValues();

            // Event listener for price input fields
            priceInputValue.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    let minp = sanitizeValue(priceInputValue[0].value, 0, 10000);
                    let maxp = sanitizeValue(priceInputValue[1].value, 0, 10000);

                    if (minp < 0) {
                        minp = 0;
                    }
                    if (maxp > 10000) {
                        maxp = 10000;
                    }
                    if (minp > maxp - priceGap) {
                        minp = maxp - priceGap;
                    }

                    if (e.target.classList.contains("min-input")) {
                        rangeInputValue[0].value = minp;
                    } else {
                        rangeInputValue[1].value = maxp;
                    }

                    updateRangePosition(minp, maxp);
                });

                // Add blur event listener to handle empty input case
                input.addEventListener("blur", (e) => {
                    if (e.target.value === '') {
                        e.target.value = '0';
                        let minp = sanitizeValue(priceInputValue[0].value, 0, 10000);
                        let maxp = sanitizeValue(priceInputValue[1].value, 0, 10000);
                        updateRangePosition(minp, maxp);
                    }
                });
            });

            rangeInputValue.forEach((range, index) => {
                range.addEventListener("input", (e) => {
                    let minVal = sanitizeValue(rangeInputValue[0].value, 0, 10000);
                    let maxVal = sanitizeValue(rangeInputValue[1].value, 0, 10000);

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
                var form = $(this).closest('form');
                if (form.attr('id') !== 'searchForm' && e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            if (window.location.search) {
                $('#actionButton').text('Clear');
            }

            $('.filter-rating-inner').click(function() {
                var value = $(this).data('value');

                // Check if the clicked rating is already active
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active'); // Remove active class
                    $('#rating').val(''); // Clear the value in the hidden input
                } else {
                    $('.filter-rating-inner').removeClass('active'); // Remove active class from all
                    $(this).addClass('active'); // Add active class to clicked element
                    $('#rating').val(value); // Set the value in the hidden input
                }
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
