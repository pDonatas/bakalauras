<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between right-30">

        <a href="{{ route('index') }}" class="logo d-flex align-items-center me-auto me-lg-0">
            <i class="bi bi-camera"></i>
            <h1>TheBarbers</h1>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="{{ route('index') }}" class="active">{{ __('Dashboard') }}</a></li>
                <li><a href="{{ route('shops.compare.index') }}">{{ __('Compare shops') }}</a></li>
                <li class="last-child dropdown">
                    <a href="#"><span><i class="bi bi-person"></i></span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul class="main-menu">
                        @auth
                            <li><a href="{{ route('index') }}">{{ __('Dashboard') }}</a></li>
                            @if (auth()->user()->isGranted(\App\Models\User::ROLE_BARBER))
                                <li><a href="{{ route('admin.index') }}">{{ __('Admin panel') }}</a></li>
                            @endif
                            <li><a href="{{ route('user.profile') }}">{{ __('Profile') }}</a></li>
                            <li><a href="{{ route('user.orders') }}">{{ __('Orders') }}</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">{{ __('Log in') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </nav>

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
</header>
