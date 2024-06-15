@extends('layouts.admin')
@section('content')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card customer-card">
                    <x-admin_alert />
                    <div class="card-header">
                        <h4>Retailers</h4>
                    </div>
                    <div class="card-body">
                        <x-search-form :statusField="true" :dateField="false" :categoryTypeField="true" />

                        <!-- <div class="filter-box row ml-0 mr-0">
                            <div >
                                <ul class="list-unstyled d-flex cus-ul align-items-center">
                                    <li class="mr-2"><label for="" class="mb-0">Search</label></li>
                                    <li>
                                        <div class="search-element payout-search">
                                            <div class="search-box">
                                                <input class="form-control" name="search" id="searchValue" type="text" placeholder="Search via email or name" aria-label="Search" value="{{ @request()->search}}">
                                                <button class="btn" type="button" id="search">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div >
                                <ul class="list-unstyled d-flex cus-ul align-items-center">
                                    <li class="mr-2"><label for="" class="mb-0">Status</label></li>
                                    <li>
                                        <select id="status" class="form-control">
                                            <option value="all" @if(request()->get('status') == 'all') selected @endif>All</option>
                                            <option value="approved" @if(request()->get('status') == 'approved') selected @endif>Approved</option>
                                            <option value="pending" @if(request()->get('status') == 'pending') selected @endif>Pending for approval</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div >
                                <ul class="list-unstyled d-flex cus-ul align-items-center">
                                    <li class="mr-2"><label for="" class="mb-0">Type</label>
                                    </li>
                                    <li>
                                        <select id="type" class="form-control">
                                            <option value="all" @if(request()->get('type') == 'all') selected @endif>All</option>
                                            <option value="retailer" @if(request()->get('type') == 'retailer') selected @endif>Retailer</option>
                                            <option value="individual" @if(request()->get('type') == 'individual') selected @endif>Individual</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div >
                                <div class="reset-filter">
                                    <a href="{{ route('admin.vendors') }}" class="btn btn-dark clear">
                                        <i class="fas fa-times"></i>
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                        </div> -->
                        <div class="table-responsive">
                            <table class="table table-striped table-md">

                                <tr>
                                    <th>#</th>
                                    <th>{{ __('retailer.name') }}</th>
                                    <th>{{ __('retailer.email') }}</th>
                                    <th>Product</th>
                                    <th>{{ __('retailer.role') }}</th>
                                    <th>{{ __('retailer.status') }}</th>
                                    <th>{{ __('common.addedDate') }}</th>
                                    <th>{{ __('common.action') }}</th>
                                </tr>
                                @foreach($vendors as $index => $vendor)
                                <tr>
                                    <td>{{ $index + $sno }}</th>
                                    <td><a href="{{ route('admin.view-retailer-completed-orders',$vendor->id)}}">{{ $vendor->name }}</a></td>
                                    <td>{{ $vendor->email }}</td>
                                    <td><a href="{{ route('admin.vendor-product-list',$vendor->id)}}">{{ count($vendor->products)}}</a></td>
                                    <td>{{ $vendor->role->name }}</td>
                                    <td>
                                        <label class="custom-switch">
                                            <input type="checkbox" class="custom-switch-input" @if ($vendor->status == '1') checked="checked" @endif onchange="toggleStatus(this, 'User', '{{ jsencode_userdata($vendor->id) }}');">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </td>
                                    <td>{{ $vendor->created_at ? date('d F Y', strtotime($vendor->created_at)): 'N/A' }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('admin.viewvendor', [$vendor->id]) }}" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-danger delete-user" href="{{ route('admin.delete.user', [$vendor->id]) }}" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <a class="btn btn-success" href="{{ route('admin.edit.user', [$vendor->id]) }}" title="Edit User">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach

                                @if ($vendors->count() == 0)
                                <tr>
                                    <td colspan="7" class="text-center text-danger">{{ __('retailer.empty') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">{{ $vendors->appends($_GET)->links() }}</div>
                </div>
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

        jQuery('#type').change(function() {
            let type = jQuery(this).val()
            currentUrl.searchParams.set("type", type);
            window.location.replace(currentUrl.href)
        });

        jQuery('#search').click(function() {
            if (jQuery("#searchValue").val().trim() != "") {
                let search = jQuery("#searchValue").val().trim();
                currentUrl.searchParams.set("search", search);
                window.location.replace(currentUrl.href)
            }
        });


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
@stop