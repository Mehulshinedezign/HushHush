@extends('layouts.admin')
@section('content')
    <section class="section">
        <div class="section-body">
            <x-admin_alert />
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card customer-card">

                        <div class="card-header">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">

                            <x-search-form :statusField="true" :dateField="false" :Export="true" />

                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('user.name') }}</th>
                                        {{-- <th>{{ __('user.type') }}</th> --}}
                                        <th>{{ __('user.product') }}</th>
                                        <th>{{ __('user.products rented') }}</th>
                                        <th>{{ __('user.email') }}</th>
                                        <th>Gov Id</th>
                                        <th>{{ __('user.active') }}</th>
                                        <th>{{ __('Date Joined') }}</th>
                                        <th>{{ __('common.action') }}</th>
                                    </tr>
                                    @foreach ($customers as $index => $customer)
                                        <tr>
                                            <td>{{ $index + $sno }}</th>
                                            <td><a
                                                    @if ($customer->role_id == 3) href="{{ route('admin.view-customer-completed-orders', $customer->id) }}" @else href="{{ route('admin.view-retailer-completed-orders', $customer->id) }}" @endif>{{ $customer->name }}</a>
                                            </td>
                                            {{-- <td>
                                        {{ ($customer->role_id == 3) ? config('constants.renter') : config('constants.lender') }}
                                    </td> --}}
                                            {{-- @if ($customer->role_id == 2) --}}
                                            <td><a
                                                    href="{{ route('admin.vendor-product-list', $customer->id) }}">{{ count($customer->products) }}</a>
                                            </td>
                                            {{-- @else
                                                <td></td>
                                            @endif --}}
                                            <td>{{ count($customer->orderitem) }}</td>
                                            <td>{{ $customer->email }}</td>
                                            
                                            <td>
                                                @if (@$customer->document)
                                                    <img id="customer-img" src="{{ asset('storage/' . $customer->document->url) }}"
                                                        alt="Profile Picture">
                                                @else
                                                    <img id="customer-img" src="{{ asset('front/images/pro3.png') }}"
                                                        alt="Default Image">
                                                @endif
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="custom-switch-input"
                                                        @if ($customer->status == '1') checked="checked" @endif
                                                        onchange="toggleStatus(this, 'User', '{{ jsencode_userdata($customer->id) }}');">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                            <td>{{ date('m/d/Y', strtotime($customer->created_at)) }}</td>
                                            <td>
                                                {{-- <a class="btn btn-primary" href="{{ route('admin.viewcustomer', [$customer->id]) }}" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a> --}}
                                                 {{-- <a class="btn btn-success"
                                                    href="{{ route('admin.edit.user', [$customer->id]) }}"
                                                    title="Edit User">
                                                    <i class="fa fa-pencil"></i>
                                                </a>  --}}
                                                <a class="btn btn-danger delete-user"
                                                    href="{{ route('admin.delete.user', [$customer->id]) }}"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($customers->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">{{ __('user.empty') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{ $customers->appends($_GET)->links() }}
                        </div>
                    </div>
                    {{-- <div class="card-footer text-right">{{ $customers->appends($_GET)->links() }}</div> --}}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        var currentUrl = new URL(window.location.href);
        jQuery(document).ready(function() {
            jQuery('#status').change(function() {
                let status = jQuery(this).val()
                currentUrl.searchParams.set("status", status);
                window.location.replace(currentUrl.href)
            });

            jQuery('#search').click(function() {
                if (jQuery("#searchValue").val().trim() != "") {
                    let search = jQuery("#searchValue").val().trim();
                    console.log(search);
                    currentUrl.searchParams.set("search", search);
                    window.location.replace(currentUrl.href)
                }
            })


            jQuery('.toggle-status').click(function() {
                let user = jQuery(this).attr('data-user');
                let url = APP_URL + '/admin/user/' + user + '/status';
                if (jQuery.isNumeric(user) && user > 0) {
                    const result = ajaxCall(url, 'get', {});
                    result.then(response => {
                        return iziToast.success({
                            title: response.title,
                            message: response.message,
                            position: 'topRight'
                        });
                    }).catch(error => {
                        if (jQuery(this).is(":checked")) {
                            jQuery(this).prop("checked", false)
                        } else {
                            jQuery(this).prop('checked', true);
                        }
                        return iziToast.error({
                            title: 'Error',
                            message: error.statusText,
                            position: 'topRight'
                        });
                    })
                }
            })
        })
    </script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery(".delete-user").click(function(e) {
                e.preventDefault();
                jQuery('body').addClass('modal-open');
                let url = jQuery(this).attr('href');
                swal({
                        title: 'Are you sure?',
                        text: 'You want to delete the customer.',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                        buttons: ["No", "Yes"],
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location.replace(url)
                        } else {
                            jQuery('body').removeClass('modal-open');
                        }
                    });
            });
        });
    </script>
@stop
