<div class="account-sidebar">
    <div class="account-user-info">
        <div class="account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <div class="account-user-name">{{ auth()->user()->name }}</div>
            <div class="account-user-email">{{ auth()->user()->email }}</div>
        </div>
    </div>

    <nav class="account-nav">
        <a href="{{ route('app.account.dashboard') }}"
           class="account-nav-item {{ request()->routeIs('app.account.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Hesabım
        </a>
        <a href="{{ route('app.account.orders') }}"
           class="account-nav-item {{ request()->routeIs('app.account.orders', 'app.account.order.detail') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Siparişlerim
        </a>
        <a href="{{ route('app.account.profile') }}"
           class="account-nav-item {{ request()->routeIs('app.account.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Bilgilerimi Güncelle
        </a>
        <a href="{{ route('app.account.password') }}"
           class="account-nav-item {{ request()->routeIs('app.account.password') ? 'active' : '' }}">
            <i class="fas fa-lock"></i> Şifre Güncelle
        </a>
        <form method="POST" action="{{ route('app.logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="account-nav-item account-nav-logout w-100 text-start">
                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
            </button>
        </form>
    </nav>
</div>
