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
      
        document.addEventListener('DOMContentLoaded', function() {
        // Get all tab items
        const tabs = document.querySelectorAll('.tab-item');

        // Check if there's a stored tab status in localStorage or get from URL query string
        const urlParams = new URLSearchParams(window.location.search);
        const urlTab = urlParams.get('status');
        const savedTab = localStorage.getItem('activeTab');
        const activeTab = urlTab || savedTab || 'PENDING'; // Default to 'PENDING' if none is found

        // Set the active tab visually
        const activeTabElement = document.querySelector(`.tab-item[data-status="${activeTab}"]`);
        if (activeTabElement) {
            activeTabElement.classList.add('active'); // Add active class to the selected tab
        }

        // Add click event listener to all tabs
        tabs.forEach(tab => {
            tab.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                
                // Get the data-status of the clicked tab
                const status = tab.getAttribute('data-status');

                // Store the clicked tab's status in localStorage
                localStorage.setItem('activeTab', status);

                // Update the URL without reloading the page
                window.history.pushState(null, '', `?status=${status}`);

                // Refresh the page to simulate the behavior you want
                location.reload();
            });
        });
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
