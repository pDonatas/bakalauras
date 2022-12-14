<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between right-30">

        <a href="{{ route('index') }}" class="logo d-flex align-items-center me-auto me-lg-0">
            <i class="bi bi-camera"></i>
            <h1>TheBarbers</h1>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="{{ route('index') }}" class="active">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li class="dropdown"><a href="#"><span>Gallery</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        <li><a href="gallery.html">Nature</a></li>
                        <li><a href="gallery.html">People</a></li>
                        <li><a href="gallery.html">Architecture</a></li>
                        <li><a href="gallery.html">Animals</a></li>
                        <li><a href="gallery.html">Sports</a></li>
                        <li><a href="gallery.html">Travel</a></li>
                        <li class="dropdown"><a href="#"><span>Sub Menu</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li><a href="#">Sub Menu 1</a></li>
                                <li><a href="#">Sub Menu 2</a></li>
                                <li><a href="#">Sub Menu 3</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="services.html">Services</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li class="last-child dropdown">
                    <a href="#"><span><i class="bi bi-person"></i></span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        @auth
                            <li><a href="{{ route('index') }}">Dashboard</a></li>
                            @if (auth()->user()->isGranted(\App\Models\User::ROLE_BARBER))
                                <li><a href="{{ route('admin.index') }}">Admin</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </nav>

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
</header>
