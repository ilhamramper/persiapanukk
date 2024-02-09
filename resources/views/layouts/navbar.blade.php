<style>
    .nav-item,
    .dropdown-item {
        color: #F8FFFF;
    }
</style>

<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <div class="justify-content-start">
            @if (auth()->check())
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            @endif
            <a class="navbar-brand ms-2"
                href="{{ Auth::check() ? (Auth::user()->id_level == 1 ? route('order') : route('home')) : route('home') }}">Belajar
                UKK</a>
        </div>
        <div class="d-flex mx-2">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <div class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </div>
                @endif
            @else
                <div class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->username }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end text-end" aria-labelledby="navbarDropdown"
                        style="background-color: #3d444b">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Keluar') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endguest
        </div>
        <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Belajar UKK</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <style>
                        .nav-link {
                            font-weight: 500;
                        }

                        .nav-link.active {
                            font-weight: bold;
                        }
                    </style>
                    @if (Auth::check())
                        @if (Auth::user()->id_level == 1)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['order', 'create.order', 'dorder', 'make.order']) ? 'active' : '' }}"
                                    href="{{ route('order') }}">Pesan Menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('riwayat.order') ? 'active' : '' }}"
                                    href="{{ route('riwayat.order') }}">Riwayat Order</a>
                            </li>
                        @elseif(Auth::user()->id_level == 2)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['home', 'create.users', 'update.users']) ? 'active' : '' }}"
                                    href="{{ route('home') }}">Data Pelanggan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('transaksi') ? 'active' : '' }}"
                                    href="{{ route('transaksi') }}">Data Transaksi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('riwayat.transaksi') ? 'active' : '' }}"
                                    href="{{ route('riwayat.transaksi') }}">Riwayat Transaksi</a>
                            </li>
                        @elseif(Auth::user()->id_level == 3)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['home', 'create.users', 'edit.users']) ? 'active' : '' }}"
                                    href="{{ route('home') }}">Data Akun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['menu', 'create.menu', 'edit.menu']) ? 'active' : '' }}"
                                    href="{{ route('menu') }}">Data Menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['meja', 'create.meja']) ? 'active' : '' }}"
                                    href="{{ route('meja') }}">Data Meja</a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
