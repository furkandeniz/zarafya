<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Zarafya navigation bar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
                <img
                    src="{{ asset('images/zarafya_logo_white.png') }}" style="height:auto; width: auto; max-height: 150px"
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
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li><a class="nav-link" href="{{ url('/shop') }}">Shop</a></li>
                <li><a class="nav-link" href="{{ url('/about') }}">About us</a></li>
                <li><a class="nav-link" href="{{ url('/services') }}">Services</a></li>
                <li><a class="nav-link" href="{{ url('/blog') }}">Blog</a></li>
                <li><a class="nav-link" href="{{ url('/contact') }}">Contact us</a></li>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                <li><a class="nav-link" href="#"><img src="{{ asset('images/user.svg') }}" alt="User"></a></li>
                <li><a class="nav-link" href="{{ url('/cart') }}"><img src="{{ asset('images/cart.svg') }}" alt="Cart"></a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Header/Navigation -->
