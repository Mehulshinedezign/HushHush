@extends('layouts.admin')
@section('content')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card customer-card">
                        <x-admin_alert />
                        <div class="card-header">
                            <h4>Products</h4>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped table-md">

                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Size</th>
                                        <th>Rent</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($products as $index => $product)
                                        <tr>
                                            <td>{{ $index + 1 }}</th>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->size }}</td>
                                            <td>{{ $product->rent }}</td>
                                            <td>{{ $product->price }}</td>

                                            <td>
                                                <label class="custom-switch">
                                                    <input type="checkbox" class="custom-switch-input"
                                                        @if ($product->status == '1') checked="checked" @endif
                                                        onchange="toggleStatus(this, 'Product', '{{ jsencode_userdata($product->id) }}');">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>

                                            <td>
                                                <a class="btn btn-primary"
                                                    href="{{ route('admin.view_product', $product->id) }}" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a class="btn btn-danger delete-product"
                                                    href="{{ route('admin.deleteproduct', $product->id) }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                {{-- <a class="btn btn-success" href="{{ route('admin.vendor-product-edit',$product->id) }}" title="Edit User">
                                            <i class="fa fa-pencil"></i>
                                        </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- @if ($products->count() == 0)
    <tr>
                                                                                                    <td colspan="7" class="text-center text-danger">{{ __('retailer.empty') }}</td>
                                                                                                </tr>
    @endif -->
                                </table>
                            </div>
                        </div>

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
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery(".delete-product").click(function(e) {
                e.preventDefault();
                jQuery('body').addClass('modal-open');
                let url = jQuery(this).attr('href');
                swal({
                        title: 'Are you sure?',
                        text: 'You want to delete the product.',
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
