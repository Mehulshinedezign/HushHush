@extends('layouts.front')

@section('title', 'Contact Us')

@section('content')
    <section class="small-banner contact-banner">
        <div class="container">
            <div class="banner-content">
                <h3 class="banner-title">{{@$page->title}}</h3>
                <p class="white-text ">{{@$page->tag_line}}</p>
            </div>
        </div>
    </section>
    <section class="contact-section">
        <div class="container">
            <div class="custom-columns">
                @if($page->content)
                <div class="row"> 
                    <div class="col-xl-4 col-md-6">
                        <div class="icon-card style-2">
                            <div class="icon">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <div class="content">
                                <h2 class="title">Contact number</h2>
                                <div class="info">
                                    <a href="tel:{{ json_decode(@$page->content, true)['phone'] }}" class="desc">{{ json_decode(@$page->content, true)['phone'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="icon-card style-2">
                            <div class="icon">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                            <div class="content">
                                <h2 class="title">Mail address</h2>
                                <div class="info">
                                    <a href="mailto:{{ json_decode(@$page->content, true)['email'] }}" class="desc">{{ json_decode(@$page->content, true)['email'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="icon-card style-2">
                            <div class="icon">
                                <i class="fa-solid fa-message"></i>
                            </div>
                            <div class="content">
                                <h2 class="title">Office address</h2>
                                <div class="info">
                                    <span class="address-desc">{{ json_decode(@$page->content, true)['address'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@stop

