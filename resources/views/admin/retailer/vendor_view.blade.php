@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="profile-view-outer">
                        <div class="profile-header">
                            <div class="profile-big-image">
                                @if(isset($user->profile_pic_url))
                                    <img src="{{ $user->profile_pic_url }}">
                                @else
                                    <img src="{{ asset('img/avatar-small.png') }}">
                                @endif
                            </div>
                            <div class="profile-detail">
                                <h4>{{ $user->name }}</h4>
                                <p >{{ $user->role->name }}</p>
                            </div>
                        </div>
                        <div class="profile-view-body">
                            <ul class="listing-row">
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.email') }}:</span>
                                    <span class="">{{ $user->email }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.phone') }}:</span>
                                    <span class="">{{ $user->phone_number ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.address') }}:</span>
                                    <span class="">{{ $user->userdetail->address1 ?? 'N/A' }} @if(isset($user->userdetail->address2)) , {{ $user->userdetail->address2 }} @endif</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.country') }}:</span>
                                    <span class="">{{ @$user->userdetail->country->name ?? 'N/A' }} </span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.state') }}:</span>
                                    <span class="">{{ @$user->userdetail->state->name ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.city') }}:</span>
                                    <span class="">{{ @$user->userdetail->city->name ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">{{ __('user.zip') }}:</span>
                                    <span class="">{{ $user->zipcode ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">About:</span>
                                    <span class="">{!! $user->about ?? 'N/A' !!}</span>
                                </li>
                                {{-- @if ($user->documents->isNotEmpty())
                                    <li class="list-col">
                                        <span class="list-item">{{ __('user.idProof') }}:</span>
                                        <span class="">
                                        <a class="" href="{{ route('admin.vendor.downloadproof', [$user->id]) }}" target="_blank">
                                            Download Proof
                                            <span class="download-file-icon"><span class="icon-box small-icon"><i class="fas fa-arrow-down"></i></span></span>
                                        </a>
                                        </span>
                                    </li>
                                @endif --}}
                            </ul>

                             @if($user->is_approved == 1)
                                <span class="btn btn-info">Approved</span>
                            @elseif($user->documents->isNotEmpty())
                                <a href="{{ route('admin.approvevendor', [$user->id]) }}" class="btn btn-primary">Approve</a>
                            @else
                                <span class="btn btn-warning">Documents not uploaded</span>
                            @endif 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection