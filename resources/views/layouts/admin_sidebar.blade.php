<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        {{-- <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <img alt="image" src="{{ asset('img/logo.svg') }}" class="header-logo" />
            </a>
        </div> --}}
        <div class="form-inline pl-4">
            <ul class="navbar-nav mr-3">
                <li>
                    <a href="#" id="toggle" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 218" width="20"
                            height="12">
                            <title>menu-svg</title>
                            <style>
                                .s0 {
                                    fill: #000000
                                }
                            </style>
                            <path id="Layer" class="s0"
                                d="m337 0.1c7.9 0 14.2 6.3 14.2 14.2c0 7.8-6.3 14.2-14.2 14.2h-322c-7.9 0-14.2-6.4-14.2-14.2c0-7.9 6.3-14.2 14.2-14.2zm0 94.7c7.8 0 14.2 6.4 14.2 14.2c0 7.8-6.4 14.2-14.2 14.2h-322.1c-7.8 0-14.2-6.4-14.2-14.2c0-7.8 6.4-14.2 14.2-14.2zm0 94.7c7.9 0 14.2 6.4 14.2 14.2c0 7.9-6.3 14.2-14.2 14.2h-322c-7.8 0-14.2-6.3-14.2-14.2c0-7.8 6.4-14.2 14.2-14.2z" />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="sidebar-menu">
            <li class="dropdown @if (Route::current()->getName() == 'admin.dashboard') active @endif ">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i
                        data-feather="monitor"></i><span>{{ __('adminsidebar.dashboard') }}</span></a>
            </li>
            <li class="dropdown @if (in_array(Route::current()->getName(), ['admin.categories', 'admin.addcategory'])) active @endif ">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="briefcase"></i><span>{{ __('adminsidebar.category') }}</span></a>
                <ul class="dropdown-menu">
                    <li @if (Route::current()->getName() == 'admin.categories') class="active" @endif><a class="nav-link "
                            href="{{ route('admin.categories') }}">{{ __('adminsidebar.categories') }}</a></li>
                    <li @if (Route::current()->getName() == 'admin.addcategory') class="active" @endif><a class="nav-link "
                            href="{{ route('admin.addcategory') }}">{{ __('adminsidebar.addcategory') }}</a></li>
                </ul>
            </li>
            <li class="dropdown @if (in_array(Route::current()->getName(), [
                    'admin.customers',
                    'admin.vendors',
                    'admin.view-retailer-completed-orders',
                    'view-customer-completed-orders',
                ])) active @endif">
                <a href="{{ route('admin.customers') }}" class="nav-link"><i
                        data-feather="user-check"></i><span>{{ __('Users') }}</span></a>
            </li>
            {{-- <li class="dropdown @if (in_array(Route::current()->getName(), ['admin.customers', 'admin.vendors', 'admin.view-retailer-completed-orders', 'view-customer-completed-orders'])) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="user-check"></i><span>Users</span></a>
                <ul class="dropdown-menu">
                    <li @if (in_array(Route::current()->getName(), ['admin.customers', 'view-customer-completed-orders'])) class="active" @endif ><a href="{{ route('admin.customers') }}">Customers</a></li>
                    <li @if (in_array(Route::current()->getName(), ['admin.vendors', 'admin.view-retailer-completed-orders'])) class="active" @endif ><a href="{{ route('admin.vendors') }}">Lender</a></li>
                </ul>
            </li> --}}
            <li class="dropdown @if (Route::current()->getName() == 'admin.commission') active @endif">
                <a href="{{ route('admin.commission') }}" class="nav-link"><i
                        data-feather="dollar-sign"></i><span>{{ __('adminsidebar.commission') }}</span></a>
            </li>
            <li class="dropdown @if (Route::current()->getName() == 'admin.cms') active @endif">
                <a href="{{ route('admin.cms') }}" class="nav-link"><i
                        data-feather="file"></i><span>{{ __('adminsidebar.cms') }}</span></a>
            </li>
            <li class="dropdown @if (in_array(Route::current()->getName(), [
                    'admin.retailer-payouts',
                    'admin.disputed-payouts',
                    'admin.security-payouts',
                ])) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="credit-card"></i><span>{{ __('adminsidebar.transaction') }}</span></a>
                <ul class="dropdown-menu">
                    <li @if (in_array(Route::current()->getName(), ['admin.retailer-payouts'])) class="active" @endif><a
                            href="{{ route('admin.retailer-payouts') }}">{{ config('constants.lender') }} Payouts</a>
                    </li>
                    <li @if (in_array(Route::current()->getName(), ['admin.security-payouts'])) class="active" @endif><a
                            href="{{ route('admin.security-payouts') }}">Security Payouts</a></li>
                    <li @if (in_array(Route::current()->getName(), ['admin.disputed-payouts'])) class="active" @endif><a
                            href="{{ route('admin.disputed-payouts') }}">Disputed Order Payouts</a></li>
                </ul>
            </li>
            <li class="dropdown @if (in_array(Route::current()->getName(), ['admin.view-order', 'admin.orders', 'admin.disputed-orders'])) active @endif">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="package"></i><span>{{ __('adminsidebar.orders') }}</span></a>
                <ul class="dropdown-menu">
                    <li @if (in_array(Route::current()->getName(), ['admin.orders'])) class="active" @endif><a
                            href="{{ route('admin.orders') }}">All Orders</a></li>
                    <li @if (in_array(Route::current()->getName(), ['admin.disputed-orders'])) class="active" @endif><a
                            href="{{ route('admin.disputed-orders') }}">Disputed Orders</a></li>
                </ul>
            </li>
            {{-- <li class="dropdown @if (Route::current()->getName() == 'admin.settings') active @endif  ">
                <a href="{{ route('admin.settings') }}" class="nav-link"><i data-feather="settings"></i><span>{{ __('adminsidebar.settings') }}</span></a>
            </li> --}}

            <li class="dropdown @if (Route::current()->getName() == 'admin.addbrand') active @endif">
                <a href="{{ route('admin.addbrand') }}" class="nav-link"><i data-feather="plus"></i>Create Brand</a>
            </li>

            <li class="dropdown @if (in_array(Route::current()->getName(), ['admin.brand', 'admin.addbrand'])) active @endif ">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        data-feather="briefcase"></i><span>{{ __('adminsidebar.brand') }}</span></a>
                <ul class="dropdown-menu">
                    <li @if (Route::current()->getName() == 'admin.brand') class="active" @endif><a class="nav-link "
                            href="{{ route('admin.brand') }}">{{ __('adminsidebar.brand') }}</a></li>
                    <li @if (Route::current()->getName() == 'admin.addbrand') class="active" @endif><a class="nav-link "
                            href="{{ route('admin.addbrand') }}">{{ __('adminsidebar.addbrand') }}</a></li>
                </ul>
            </li>
            
        </ul>
    </aside>
</div>
