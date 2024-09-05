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
        <a href="#" class="d-block"><?php echo e(explode(' ', auth()->user()->name)[0]); ?></a>
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
            <!-- Add other admin-specific menu items here -->
            <li class="nav-item has-treeview">
                <a href="{{ route('orders.index') }}" class="nav-link {{ activeSegment('orders') }}">
                <i class="nav-icon fas fa-shopping-basket"></i> <!-- Icon for Orders -->
                    <p>Orders</p>
                </a>
            </li>
            
            <li class="nav-item has-treeview">
                <a href="{{ route('staffs.index') }}" class="nav-link {{ activeSegment('staffs') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Staffs</p>
                </a>
            </li>

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
    if (request()->is($segmentName . '*')) {
        return 'active';
    }
    return '';
}
?>
