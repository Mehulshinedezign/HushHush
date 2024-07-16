@extends('layouts.front')

@section('content')
    <section class="home-sec fill-hight">
        <div class="container">
            <div class="home-wrapper">
                <div class="section-heading">
                    <h2>Products List</h2>
                </div>
                <div class="home-filter-product">
                    @if ($products->isNotEmpty())
                        <div class="home-product-main">
                            <div class="home-product-box">

                                @foreach ($products as $product)
                                    <div class="product-card">
                                        <div class="product-img-box">

                                            <a href="{{ route('viewproduct', jsencode_userdata($product->id)) }}" class="productLink">
                                                @if (isset($product->thumbnailImage->file_path))
                                                    <img src="{{$product->thumbnailImage->file_path }}"
                                                        alt="" loading="lazy">
                                                @else
                                                    <img src="{{ asset('front/images/pro-0.png') }}" alt="img">
                                                @endif
                                            </a>

                                        </div>
                                        <div class="product-card-detail">
                                            <p>{{ $product->name }}</p>
                                            <h4>${{ $product->rent_day }}/day</h4>
                                        </div>
                                        <div class="product-btn-box">
                                            <a href="{{ route('editproduct', ['id' => jsencode_userdata($product->id)]) }}"
                                                class="button outline-btn full-btn">Edit</a>
                                            {{-- <a href="{{ route('deleteproduct', ['id' => jsencode_userdata($product->id)]) }}" class="button primary-btn full-btn">Delete</a> --}}
                                            <a href="{{ route('deleteproduct', ['id' => jsencode_userdata($product->id)]) }}"
                                                class="button primary-btn full-btn"
                                                onclick="confirmDelete(event)">Delete</a>
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
                    @else
                        <div class="list-empty-box">
                            <img src="{{ asset('front/images/no-products.svg') }}">
                            <h3 class="text-center">Your product is empty</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        function confirmDelete(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "Are you sure you want to delete this product?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1B1B1B',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('body').addClass('loading');
            window.location.href = event.target.href;
        }
    });
}
    </script>
@endpush
