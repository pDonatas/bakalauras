<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <div class="container-fluid">
        <!-- End navbar links -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->photo }}" class="user-image img-circle shadow" alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{ auth()->user()->avatar }}" class="img-circle shadow" alt="User Image">

                        <p>
                            {{ Auth::user()->name }}
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-default btn-flat">{{ __('Profile') }}</a>
                            </div>
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->
