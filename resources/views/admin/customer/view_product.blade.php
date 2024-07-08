@extends('layouts.admin')

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="profile-view-outer">
                        <div class="profile-header">
                            <div class="profile-big-image">

                            @if (isset($product->thumbnailImage->file_path))
                                <img src="{{ asset('storage/'. $product->thumbnailImage->file_path) }}" alt="" loading="lazy">
                            @else
                                <img src="{{asset('front/images/pro-0.png')}}" alt="img">
                            @endif
                            </div>
                            <div class="profile-detail">
                                <h4>{{ $product->name }}</h4>
                                <p>{{ $product->price }}</p>
                            </div>
                        </div>
                        <div class="profile-view-body">
                            <ul class="listing-row">
                                <li class="list-col">
                                    <span class="list-item">Category :</span>
                                    <span class="">{{ @$product->category->name }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">Size:</span>
                                    <span class="">{{ $product->size ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">Rent:</span>
                                    <span class="">{{ $product->rent_day ?? 'N/A' }} </span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">Condition:</span>
                                    <span class="">{{ @$product->condition ?? 'N/A' }} </span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item"> Brand:</span>
                                    <span class="">{{ @$product->brand ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">Color:</span>
                                    <span class="">{{ @$product->color ?? 'N/A' }}</span>
                                </li>

                                {{-- <li class="list-col">
                                    <span class="list-item">{{ __('user.zip') }}:</span>
                                    <span class="">{{ $user->zipcode ?? 'N/A' }}</span>
                                </li>
                                <li class="list-col">
                                    <span class="list-item">About:</span>
                                    <span class="">{!! $user->about ?? 'N/A' !!}</span>
                                </li> --}}
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

                            {{-- @if ($user->is_approved == 1)
                                <span class="btn btn-info">Approved</span>
                            @elseif($user->documents->isNotEmpty())
                                <a href="{{ route('admin.approvevendor', [$user->id]) }}" class="btn btn-primary">Approve</a>
                            @else
                                <span class="btn btn-warning">Documents not uploaded</span>
                            @endif  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
