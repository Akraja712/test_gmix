<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-purple elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('public/images/poslg.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
	<!-- Log on to codeastro.com for more projects -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://www.gravatar.com/avatar/" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
        <a href="#" class="d-block">Admin</a>
    </div>
        </div>
        <!-- Sidebar Menu -->
        <!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview">
            <a href="{{ route('home') }}" class="nav-link {{ activeSegment('') }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
            <li class="nav-item has-treeview">
                <a href="{{ route('users.index') }}" class="nav-link {{ activeSegment('users') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="{{ route('addresses.index') }}" class="nav-link {{ activeSegment('addresses') }}">
                <i class="nav-icon fas fa-map-marker-alt"></i> <!-- Icon for Address -->
                    <p>Address</p>
                </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                <i class="nav-icon fas fa-map-marker-alt"></i> <!-- Icon for Address -->
                    <p>Products</p>
                </a>
            </li>

            <li class="nav-item has-treeview">
                <a href="{{ route('reward_products.index') }}" class="nav-link {{ activeSegment('reward_products') }}">
                <i class="nav-icon fas fa-gift"></i> <!-- Icon for Address -->
                    <p>Reward Products</p>
                </a>
            </li>

            <li class="nav-item has-treeview">
                <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                <i class="nav-icon fas fa-shopping-basket"></i> <!-- Icon for Orders -->
                    <p>Orders</p>
                </a>
            </li>

            <li class="nav-item has-treeview">
                    <a href="{{ route('news.edit') }}" class="nav-link {{ activeSegment('news') }}">
                        <i class="nav-icon fas fa-gear"></i>
                        <p>Settings</p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('appsettings.edit', 1) }}" class="nav-link {{ activeSegment('appsettings') }}">
                        <i class="nav-icon fas fa-gear"></i>
                        <p>App Settings</p>
                    </a>
                </li>
            <!-- Add other admin-specific menu items here -->

        <li class="nav-item">
            <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                <i class="nav-icon fas fa-power-off"></i>
                <p>Logout</p>
                <form action="{{route('logout')}}" method="POST" id="logout-form">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</nav>

        <!-- /.sidebar-menu -->
    </div><!-- Log on to codeastro.com for more projects -->
    <!-- /.sidebar -->
</aside>
<?php
function activeSegment($segmentName) {
    $currentUri = $_SERVER['REQUEST_URI'];
    if (strpos($currentUri, $segmentName) !== false) {
        return 'active';
    }
    return '';
}
?>

