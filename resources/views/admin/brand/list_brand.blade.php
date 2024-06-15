@extends('layouts.admin')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <x-admin_alert />
                    <div class="card">
                        <div class="card-header">
                            <h4>Brands</h4>
                        </div>
                        <div class="card-body">
                            <x-search-form :brandField="true" :dateField="false" :categoryTypeField="false" />
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('brand.name') }}</th>
                                        <th>{{ __('common.status') }}</th>
                                      
                                        <th>{{ __('common.action') }}</th>
                                    </tr>
                                    @foreach ($brands as $index => $brand)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{ $brand->name }}
                                            </td>
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="custom-switch-input"
                                                        @if ($brand->status == '1') checked="checked" @endif
                                                        onchange="toggleStatus(this, 'Brand', '{{ jsencode_userdata($brand->id) }}');">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>
                                      
                                            <td>
                                                {{-- <a class="btn btn-primary"
                                                    href="{{ route('admin.viewbrand', [$brand->id]) }}"
                                                    title="View">
                                                    <i class="fa-solid fa-v"></i>
                                                </a> --}}
                                            
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.editbrand', [$brand->id]) }}"
                                                    title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a class="btn btn-danger delete-brand"
                                                    href="{{ route('admin.deletebrand', [$brand->id]) }}"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($brands->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">{{ __('brand.empty') }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">{{ $brands->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery(".delete-brand").click(function(e) {
                e.preventDefault();
                jQuery('body').addClass('modal-open');
                let url = jQuery(this).attr('href');
                swal({
                        title: 'Are you sure?',
                        text: 'You want to delete the category.',
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
@endpush
