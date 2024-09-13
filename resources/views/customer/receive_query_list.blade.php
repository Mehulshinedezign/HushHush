@extends('layouts.front')
@section('title', 'Receive Query')
@section('links')
    @php
        $user = auth()->user();
    @endphp
@endsection

@section('content')
    <section class="rental-request-bx min-height-100">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    {{-- @dd($user); --}}
                    <h2>Received Inquiry List</h2>
                    <div class="form-group">
                        {{-- <div class="formfield">
                            <input type="text" placeholder="Select Date" class="form-control">
                            <span class="form-icon">
                                <img src="{{ asset('front/images/calender-icon.svg') }}" alt="img" class="cal-icon">
                            </span>
                        </div> --}}
                    </div>
                </div>
                <div class="custom-tab">
                    <ul class="custom-tab-list">
                        <li class="tab-item active" data-status="PENDING" data-user="lender">
                            <a href="?status=PENDING" class="tab-link">Pending</a>
                        </li>
                        <li class="tab-item" data-status="ACCEPTED" data-user="lender"><a href="?status=ACCEPTED"
                                class="tab-link">Accepted</a></li>
                        <li class="tab-item" data-status="REJECTED" data-user="lender"><a href="?status=REJECTED"
                                class="tab-link">Rejected</a></li>
                        <li class="tab-item" data-status="COMPLETED" data-user="lender"><a href="?status=COMPLETED"
                                class="tab-link">Completed</a></li>

                    </ul>

                </div>
                <div id="query-list-container">
                    <div id="query-list-container">
                        <x-receive-query :querydatas="$querydatas" :accept="$accept" />
                    </div>

                </div>

            </div>
        </div>
    </section>



    <div class="modal fade" id="single_query_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="data-query">


                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let activeTab = localStorage.getItem('activeTab') || 'PENDING'; // Default to 'PENDING'

            $('.custom-tab-list .tab-item').removeClass('active');
            $('.custom-tab-list .tab-item[data-status="' + activeTab + '"]').addClass('active');

            $('.custom-tab-list .tab-item').on('click', function() {
                let selectedTab = $(this).data('status');
                localStorage.setItem('activeTab', selectedTab);
            });
        });

        $(document).ready(function() {
            // Get the current status from the query string
            var urlParams = new URLSearchParams(window.location.search);
            var status = urlParams.get('status') || 'PENDING'; // Default to 'PENDING' if no status is set

            // Automatically trigger a click on the correct tab based on the status
            $('.tab-item').removeClass('active');
            var activeTab = $('.tab-item[data-status="' + status + '"]');
            activeTab.addClass('active');

            // Trigger a click on the tab to load the data
            activeTab.find('a.tab-link').trigger('click');

            // Handle tab clicks to update URL without reloading the page
            $('.tab-link').click(function(e) {
                e.preventDefault(); // Prevent the default link action

                var clickedTab = $(this).parent(); // Get the clicked tab's parent (the <li> element)
                var selectedStatus = clickedTab.data('status');

                // Set the active tab class
                $('.tab-item').removeClass('active');
                clickedTab.addClass('active');

                // Update the URL without refreshing the page
                var newUrl = new URL(window.location.href);
                newUrl.searchParams.set('status', selectedStatus);
                window.history.pushState({}, '', newUrl);

                // Optionally, trigger the form submission or data loading logic here
                loadDataBasedOnTab(selectedStatus);
            });

            // Function to load data based on the selected tab
            function loadDataBasedOnTab(status) {
                // Logic to load data for the selected tab (AJAX or form submission)
                // For example:
                $.ajax({
                    url: '/received_query', // Adjust URL based on your route
                    method: 'GET',
                    data: {
                        status: status
                    },
                    success: function(response) {
                        // Update your data table with the response data
                        $('#data-table').html(response); // Assuming you have a table or data container
                    }
                });
            }

            // Trigger data load on page load
            loadDataBasedOnTab(status);
        });
    </script>
    <script>
        $(document).ready(function() {
            var myModal = new bootstrap.Modal(document.getElementById('single_query_Modal'));

            $(document).on('click', '.single_query', function(event) {
                event.preventDefault();
                console.log("Query view clicked");
                var url = $(this).attr('href');
                var queryId = $(this).data('query-id');
                // var queryShip =$(this).data('query-id')

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        query_id: queryId
                    },
                    success: function(response) {
                        $('#single_query_Modal .modal-body').html(response.data);
                        // myModal.show();
                        myModel.show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading query details:', error);
                    }
                });
            });
        });
        // modal only closed when click on the closed button
        var myModel = new bootstrap.Modal(document.getElementById('single_query_Modal'), {
            backdrop: 'static',
            keyboard: false
        });

        function confirmReject(event, queryId) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to reject this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1B1B1B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('body').addClass('loading');
                    rejectQuery(queryId);
                }
            });
        }

        function rejectQuery(queryId) {
            var url = `{{ url('/reject_query') }}/${queryId}`;
            window.location.href = url;
            $('.user_query-' + queryId).remove();
        }


        function confirmAccept(event, queryId, date_range, rentDay, rentWeek, rentMonth, deliveryOption) {
            event.preventDefault();

            // console.log('Function confirmAccept triggered'); // Ensure the function is being called

            let price = $(`.negotiation_price_${queryId}`).val();
            let shipping_charges = $(`.shipping_charges_${queryId}`).val();
            let cleaning_charges = $(`.cleaning_charges_${queryId}`).val();
            // alert(price);

            // Set shipping charges to 0 if delivery option is pickup
            if (deliveryOption === 'pick_up') {
                // console.log('Delivery option is pick_up:', deliveryOption); // Check delivery option
                shipping_charges = 0;
                // console.log('Shipping charges set to 0:', shipping_charges); // Confirm shipping charges set to 0
            }

            if (!price) {
                price = calculatePrice(date_range, rentDay, rentWeek, rentMonth);
                // alert(price);
            }


            if (price <= 0 || isNaN(price)) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please enter a valid price',
                    position: 'topRight',
                });
                return false;
            }

            // if (!cleaning_charges || cleaning_charges <= 0 || isNaN(cleaning_charges)) {
            //     iziToast.error({
            //         title: 'Error',
            //         message: 'Please enter a valid cleaning charge',
            //         position: 'topRight',
            //     });
            //     return false;
            // }

            if (isNaN(cleaning_charges)) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please enter a valid cleaning charge',
                    position: 'topRight',
                });
                return false;
            }

            // console.log('Delivery option after validation:', deliveryOption);


            if (deliveryOption != 'pick_up' && (isNaN(shipping_charges))) {
                iziToast.error({
                    title: 'Error',
                    message: 'Please enter a valid shipping charge',
                    position: 'topRight',
                });
                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `Are you sure you want to send an offer for this product`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1B1B1B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('body').addClass('loading');
                    acceptQuery(queryId, price, shipping_charges, cleaning_charges);
                }
            });
        }



        function acceptQuery(queryId, price, shipping_charges, cleaning_charges) {
            const encodedPrice = encodeURIComponent(price);
            const encodedshipping_charges = encodeURIComponent(shipping_charges);
            const encodedcleaning_charges = encodeURIComponent(cleaning_charges);

            const url =
                `{{ url('/accept_query') }}/${queryId}?negotiate_price=${encodedPrice}&shipping_charges=${encodedshipping_charges}&cleaning_charges=${encodedcleaning_charges}`;
            window.location.href = url;
        }

        function calculatePrice(dateRange, rentDay, rentWeek, rentMonth) {
            const [startDateStr, endDateStr] = dateRange.split(' - ');
            const startDate = new Date(startDateStr.trim());
            const endDate = new Date(endDateStr.trim());

            const days = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

            let price = 0;

            if (days >= 30) {
                const months = Math.floor(days / 30);
                let remainingDays = days % 30;
                if (remainingDays >= 7) {
                    const weeks = Math.floor(remainingDays / 7);
                    remainingDays = remainingDays % 7;
                    price = (months * rentMonth) + (weeks * rentWeek) + (remainingDays * rentDay);
                } else {
                    price = (months * rentMonth) + (remainingDays * rentDay);
                }
            } else if (days >= 7) {
                const weeks = Math.floor(days / 7);
                const remainingDays = days % 7;
                price = (weeks * rentWeek) + (remainingDays * rentDay);
            } else {
                price = days * rentDay;
            }

            return price;


        }
    </script>
@endpush
