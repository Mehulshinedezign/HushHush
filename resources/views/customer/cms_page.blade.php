@extends('layouts.front')

@section('title', $page->title)

@section('content')
   
    <section class="cms-comman-sec">
        <div class="profile-section">
            <div class="container">
                <div class="banner-content">
                    <h3 class="banner-title">{{ @$page->title }}</h3>
                    <p class="white-text">{{ @$page->tag_line }}</p>
                </div>
                <div class="rich-txt-wrapper">
                    <div class="row">
                        <div class="col-md-12">{!! $page->content !!}</div>
                    </div>
                    <div class="row">
                        @if ($page->id == 2)
                            <a href="{{ $page->url }}">{{ $page->display_text }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
