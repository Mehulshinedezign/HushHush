@extends('layouts.front')
@section('title', 'Home')
@section('content')
    <section>
        @include('category')
    </section>
    {{-- <section>
        <div class="home-collection">
            <div class="container">
                <div class="row">
                    @if (count($products) > 0)
                        <x-single-product :products="$products" />
                    @else
                        <h2 class="text-center">We're sorry, no products fit your search criteria.</h2>
                    @endif
                </div>
                <div class="custom-pagination">{{ $products->links('pagination::product-list') }}</div>
            </div>
        </div>
    </section> --}}
    {{-- @include('instruction') --}}
@endsection
