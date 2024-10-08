@extends('layouts.admin')

@section('content')
    <section class="section">

        {{-- <div><a href="{{ url()->previous() }}"><i class="fa-solid fa-angle-left"></i></a></div> --}}
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <x-admin_alert />
                    <div class="card">
                        <div class="card-header">
                            <h4>Categories</h4>
                        </div>
                        <div class="card-body">
                            {{-- <x-search-form :statusField="true" :dateField="false" :categoryTypeField="true" /> --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('category.name') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        {{-- <th>{{ __('Icon') }}</th> --}}
                                        {{-- <th>{{ __('category.noOfProducts') }}</th> --}}
                                        <th>{{ __('common.status') }}</th>

                                        <th>{{ __('common.action') }}</th>
                                    </tr>
                                    @foreach ($categories as $index => $category)
                                        <tr>
                                            <td>{{ $index + 1 }}</th>
                                            <td>
                                                {{ $category->name }}
                                            </td>
                                            <td>
                                                @if ($category->parent_id == null)
                                                    Main
                                                @else
                                                    Sub category
                                                @endif
                                            </td>
                                            {{-- <td>
                                                @if ($category->category_image_url)
                                                    <img src="{{ $category->category_image_url }}" width="44"
                                                        height="44" style="border-radius: 50%;" />
                                                @endif
                                            </td> --}}

                                            {{-- <td>
                                                {{ $category->products_count }}
                                            </td> --}}
                                            <td>
                                                <label>
                                                    <input type="checkbox" class="custom-switch-input"
                                                        @if ($category->status == '1') checked="checked" @endif
                                                        onchange="toggleStatus(this, 'Category', '{{ jsencode_userdata($category->id) }}');">
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </td>

                                            <td>
                                                @if (
                                                    $category->name != 'men_shoe' &&
                                                        $category->name != 'women_shoe' &&
                                                        $category->name != 'clothing_size' &&
                                                        $category->name != 'bra_size')
                                                    <a class="btn btn-success"
                                                        href="{{ route('admin.editcategory', [$category->id]) }}"
                                                        title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>


                                                <a class="btn btn-danger delete-category"
                                                    href="{{ route('admin.deletecategory', [$category->id]) }}"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endif
                                                {{-- <a class="btn btn-primary"
                                                    href="{{ route('admin.categoryproduct', [$category->id]) }}"
                                                    title="Products">
                                                    <i class="fab fa-product-hunt"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($categories->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">{{ __('category.empty') }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">{{ $categories->links() }}</div>
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
            jQuery(".delete-category").click(function(e) {
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
