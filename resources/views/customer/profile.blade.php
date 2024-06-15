@extends('layouts.front')
@section('title', 'My Profile')
@section('content')
    <section>
        <div class="my-profile-section">
            <div class="container">
                <div class="my-profile-title">
                    <h4>My Profile</h4>
                </div>
                <div class="my-profile-form">
                    <div class="profile-edit-btn">
                        <a class="primary-btn" href="{{ route('edit-account') }}">Edit</a>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="personal-info-row">
                                <div>
                                    <span>Name</span>
                                    <p>{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="personal-info-row">
                                <div>
                                    <span>Username</span>
                                    <p>{{ auth()->user()->username }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="personal-info-row">
                                <div>
                                    <span>Email</span>
                                    <p>{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="personal-info-row">
                                <div>
                                    <span>Phone number</span>
                                    <p>{{ auth()->user()->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="zip-code-row">
                        <div>
                            <span>Zip code</span>
                            <p>{{ auth()->user()->zipcode }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts') 
@endpush