{{-- <div class="sidebar @if (session('menu_option', 'open') == 'open') menu-open @endif" id="menu">
    <div class="logo">
        <div class="toggle" id="toggle">
            <div class="toggle-innr">
            <span class="bar1"></span>
            <span class="bar2"></span>
            <span class="bar3"></span>
            </div>
        </div>
        <div class="logoimg-box">
            <a href="{{ route('retailer.dashboard') }}" class="lrg-logo">
                <img src="{{ asset('img/logo.png') }}" alt="logo img" class="img-fluid ml-5" width="60%">
            </a>
            <a href="{{ route('retailer.dashboard') }}" class="sml-logo">
                <img src="{{ asset('img/logo.png') }}" alt="logo img" class="img-fluid">
            </a>
        </div>
    </div>
    
    <div class="sidebar-content">
        <ul class="list-unstyled">
            <li class="@if (Route::current()->getName() == 'retailer.dashboard') active @endif">
                <a href="{{ route('retailer.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="dropdown @if (in_array(Route::current()->getName(), ['retailer.products', 'retailer.addproduct'])) active show @endif">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-dice-d6"></i>
                    <span>Products</span>
                    <i class="fal fa-plus"></i>
                </a>                        
                <div class="dropdown-menu sidebar-dropdown @if (in_array(Route::current()->getName(), ['retailer.products', 'retailer.addproduct'])) show @endif" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('retailer.products') }}">
                        <i class="fas fa-angle-right"></i> 
                        <span> All Products</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('retailer.addproduct') }}">
                        <i class="fas fa-angle-right"></i> 
                        <span>Add Product</span>
                    </a>
                </div>
            </li>
           <li class="@if (Route::current()->getName() == 'retailer.orders') active @endif">
                <a href="{{ route('retailer.orders') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="@if (Route::current()->getName() == 'retailer.notifications') active @endif">
                <a href="{{ route('retailer.notifications') }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>                
            <li>
                <a href="{{ route('retailer.payouts') }}">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Payouts</span>
                </a>
            </li>
        </ul>
    </div>
</div> --}}

{{-- hide the sidebar_menu --}}

<div class="sidebar_menu">
    <div class="toggle-menu"><i class="fa-solid fa-chevron-right"></i></div>
    <div class="Menucstm">
        <h2><a href="{{ route('retailer.dashboard') }}">Dashboard</a></h2>
        <ul class="dashboard-menu-list">
            <li><a href="{{ route('retailer.products') }}"><i class="fa-regular fa-file-lines"></i> Product management</a>
            </li>
            <li><a href="{{ route('retailer.orders') }}"><i class="fa-regular fa-file-lines"></i> Order management</a>
            </li>
            <li><a href="{{ route('retailer.notifications') }}"><i class="fa-regular fa-bell"></i> Notifications</a>
            </li>
            <li><a href="{{ route('retailer.profile') }}"><i class="fa-solid fa-gear"></i> Account settings</a></li>
            <li><a href="{{ route('retailer.payouts') }}"><i class="fa-regular fa-file-lines"></i> Payment history
                    management</a></li>
        </ul>
    </div>
    <div class="log_out">
        <a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</div>
