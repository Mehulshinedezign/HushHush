@extends('layouts.customer')

@section('title', 'Payment Failed')

@section('content')
    <section class="success-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto mt-5">
                    <div class="payment-error">
                        <div class="payment-header">
                            <div class="check">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="content">
                            <h1>Opps! Payment {{ $status }}</h1>
                            <p>{{ $message }}</p>
                            <a href="{{ route('billing', [$token]) }}" class="btn blue-btn">Try Again!</a>
                            <a href="{{ route('index') }}" class="btn green-btn">Home</a>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection