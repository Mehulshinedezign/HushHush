@auth
@php
    $user = auth()->user();
    $userBankInfo = $user->userBankInfo;
    $userCompleteAddress = $user->userDetail->complete_address ?? null;
@endphp

@if (is_null($userBankInfo) || is_null($userCompleteAddress))
    <div class="alert alert-danger alert-dismissible add-adress-alert" role="alert">
        <button type="button" class="close" data-dismiss="alert">
        </button>
        <strong>
            <p>Please enter your @if (is_null($userBankInfo))
                    <a href="{{ route('stripe.onboarding.redirect') }}">Bank Details</a>
                    {{ is_null($userCompleteAddress) ? ' | ' : '' }}
                @endif
                @if (is_null($userCompleteAddress))
                    <a href="{{ route('change-Profile', [$user, $user->id]) }}">Add Address</a>
                @endif
            </p>
        </strong>
    </div>
@endif
@endauth
