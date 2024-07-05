@extends('layouts.front')

@section('content')
<section class="home-sec">
    <div class="container">
        <div class="home-wrapper">
            <div class="section-heading">
                <h2>Products List</h2>
            </div>
            <div class="home-filter-product">
                  
                <div class="home-product-main">
                    <div class="home-product-box">

                        @foreach ($products as $product)
                            <div class="product-card">
                                <div class="product-img-box">
                                    
                                    <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}">
                                        @if (isset($product->thumbnailImage->file_path))
                                            <img src="{{ asset('storage/'. $product->thumbnailImage->file_path) }}" alt="" loading="lazy">
                                        @else
                                            <img src="{{asset('front/images/pro-0.png')}}" alt="img">
                                        @endif
                                    </a>
                                    
                                </div>
                                <div class="product-card-detail">
                                    <p>{{ $product->name }}</p>
                                    <h4>${{$product->rent_day}}/day</h4>
                                </div>
                                <div class="product-btn-box">
                                    <a href="{{ route('editproduct', ['id' => jsencode_userdata($product->id)]) }}" class="button outline-btn full-btn">Edit</a>
                                    <a href="{{ route('deleteproduct', ['id' => jsencode_userdata($product->id)]) }}" class="button primary-btn full-btn">Delete</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- <div class="pagination-main">
                        <a href="javascript:void(0)" class="pagination-box">
                            01
                        </a>
                        <a href="javascript:void(0)" class="pagination-box">
                            02
                        </a>
                        <a href="javascript:void(0)" class="pagination-box active">
                            03
                        </a>
                        <a href="javascript:void(0)" class="pagination-box">
                            04
                        </a>
                        <a href="javascript:void(0)" class="pagination-box">
                            05
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')

@endpush
