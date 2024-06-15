@extends('layouts.admin')

@section('content')

    <section class="section">
        <div class="section-body">
            <x-admin_alert/>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('product.title') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('product.name') }}</th>
                                        <th>Category</th>
                                        <th>{{ __('product.retailer') }}</th>
                                        <th>Added Date</th>
                                        <th>{{ __('common.action') }}</th>
                                    </tr>
                                    @foreach($products as $index => $product)
                                        <tr>
                                            <td>{{ $index + $sno }}</th>
                                            <td class="td-product-title">{{ $product->name }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->retailer->name }}</td>
                                            <td>{{ date($global_date_format, strtotime($product->created_at)) }}</td>
                                            <td>
                                                <a class="btn btn-success" href="{{ route('admin.editproduct', [$product->category_id, $product->id]) }}" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a> 
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($products->count() == 0)
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">{{ __('product.empty') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">{{ $products->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

