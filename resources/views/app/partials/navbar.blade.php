<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar-expand-md navbar-dark" aria-label="Zarafya navigation bar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
                <img
                    src="{{ asset('images/zarafya_logo_white.png') }}" style="height: 46px; width: auto;"
                    alt="Zarafya"
                    class="header-logo"
                >
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
                aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">Anasayfa</a>
                </li>
                <li class="{{ request()->routeIs('shop', 'shop.product') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('shop') }}">Ürünler</a>
                </li>
                <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('about') }}">Hakkımızda</a>
                </li>
                <li class="{{ request()->routeIs('services') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('services') }}">Hizmetlerimiz</a>
                </li>
                <li class="{{ request()->routeIs('blog') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                </li>
                <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('contact') }}">İletişim</a>
                </li>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-1"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('images/user.svg') }}" alt="User">
                            <span class="small text-white">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            @if (Auth::user()->role === 'enduser')
                                <li><a class="dropdown-item" href="{{ route('app.account.dashboard') }}">Hesabım</a></li>
                                <li><a class="dropdown-item" href="{{ route('app.account.orders') }}">Siparişlerim</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            @if (Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('app.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Çıkış Yap</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a class="nav-link" href="{{ route('app.login') }}">
                            <img src="{{ asset('images/user.svg') }}" alt="User">
                        </a>
                    @endauth
                </li>
                <li>
                    <a class="nav-link" href="{{ route('cart') }}" style="position:relative;">
                        <img src="{{ asset('images/cart.svg') }}" alt="Cart">
                        @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                        @if ($cartCount > 0)
                            <span style="position:absolute;top:-4px;right:-8px;background:#e74c3c;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Header/Navigation -->
