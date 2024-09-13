@extends('layouts.front')
@section('title', 'My query')
@section('links')
    @php
        $user = auth()->user();
        $currentTab = request()->get('status', 'PENDING'); // Default to 'PENDING' tab if no status is set
    @endphp
@endsection

@section('content')
    <section class="rental-request-bx min-height-100">
        <div class="container">
            <div class="rental-request-wrapper">
                <div class="rental-header">
                    <h2>My Inquiry List</h2>
                    <div class="form-group">
                    </div>
                </div>

                <div class="custom-tab">
                    <ul class="custom-tab-list">
                        <li class="tab-item" data-status="PENDING">
                            <a href="?status=PENDING" class="tab-link">Pending</a>
                        </li>
                        <li class="tab-item" data-status="ACCEPTED">
                            <a href="?status=ACCEPTED" class="tab-link">Accepted</a>
                        </li>
                        <li class="tab-item" data-status="REJECTED">
                            <a href="?status=REJECTED" class="tab-link">Rejected</a>
                        </li>
                        <li class="tab-item" data-status="COMPLETED">
                            <a href="?status=COMPLETED" class="tab-link">Completed</a>
                        </li>
                    </ul>
                </div>


                <div id="query-list-container">
                    <x-product-query :querydatas="$querydatas" />
                </div>
            </div>
        </div>
    </section>
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
                    url: '/my_inquiry', // Adjust URL based on your route
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

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{-- @includeFirst(['validation']) --}}
    <script>
        $(document).ready(function() {
            var singleQueryModal = new bootstrap.Modal(document.getElementById('single_query_Modal'));

            $(document).on('click', '.single_query_Modal', function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var queryId = $(this).data('query-id');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        query_id: queryId
                    },
                    success: function(response) {
                        $('#single_query_Modal .modal-body').html(response.data);
                        // singleQueryModal.show();
                        myModal.show();

                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading query details:', error);
                    }
                });
            });

            $('.my_query_details').hide();

            $(document).on('click', '.query_btn_close', function() {
                // singleQueryModal.hide();
                myModal.hide();

            });
            $('#single_query_Modal').on('hidden.bs.modal', function() {
                $('#single_query_Modal .modal-body').html('');
            });

            // modal only closed when click on the closed button
            var myModal = new bootstrap.Modal(document.getElementById('single_query_Modal'), {
                backdrop: 'static',
                keyboard: false
            });


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
    </script>
@endpush
