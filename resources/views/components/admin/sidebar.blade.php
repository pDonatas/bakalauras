<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">
    <div class="brand-container">
        <a href="{{ config('app.url') }}" class="brand-link">
            <img src="/assets/admin/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-80 shadow">
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
        </a>
        <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="{{ config('app.url') }}" role="button"><i class="fas fa-angle-double-left"></i></a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <!-- Sidebar Menu -->
            <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            {{ __('Back to Home') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-circle"></i>
                        <p>
                            {{ __('Dashboard') }}
                        </p>
                    </a>
                </li>
                @if (auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ __('Users') }}
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('admin.shops.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            {{ __('Shops') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-tag"></i>
                        <p>
                            {{ __('Orders') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.calendar.index') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-calendar-check"></i>
                        <p>
                            {{ __('Calendar') }}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
