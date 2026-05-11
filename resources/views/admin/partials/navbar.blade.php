<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <p>Zarafya</p>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar" type="button">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler" type="button">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more" type="button">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">

            {{-- Sol: Arama --}}
            <form action="{{ route('admin.search') }}" method="GET"
                  class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" name="q" placeholder="Ara..." class="form-control"
                           value="{{ request('q') }}" autocomplete="off">
                </div>
            </form>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                {{-- Mobil Arama --}}
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <li>
                            <form action="{{ route('admin.search') }}" method="GET"
                                  class="navbar-left navbar-form nav-search">
                                <div class="input-group">
                                    <input type="text" name="q" placeholder="Ara..." class="form-control"
                                           value="{{ request('q') }}" autocomplete="off">
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>

                {{-- Mesajlar --}}
                @if (auth()->user()->isAdmin())
                @php $unreadNav = \App\Models\ContactMessage::whereNull('read_at')->count(); @endphp
                <li class="nav-item topbar-icon">
                    <a class="nav-link" href="{{ route('admin.contacts.index') }}" title="Mesajlar">
                        <i class="fa fa-envelope"></i>
                        @if ($unreadNav > 0)
                            <span class="notification">{{ $unreadNav }}</span>
                        @endif
                    </a>
                </li>
                @endif

                {{-- Stok Bildirimleri (sadece admin) --}}
                @if (auth()->user()->isAdmin())
                @php $pendingNav = \App\Models\StockNotification::whereNull('notified_at')->count(); @endphp
                @if ($pendingNav > 0)
                <li class="nav-item topbar-icon">
                    <a class="nav-link" href="{{ route('admin.stock-notifications.index') }}" title="Stok Bildirimleri">
                        <i class="fa fa-bell"></i>
                        <span class="notification">{{ $pendingNav }}</span>
                    </a>
                </li>
                @endif
                @endif

                {{-- Siteye Git --}}
                <li class="nav-item topbar-icon">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank" title="Siteye Git">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </li>

                {{-- Kullanıcı --}}
                @php
                    $user      = auth()->user();
                    $userName  = $user?->name ?? 'Kullanıcı';
                    $avatarUrl = asset('admin/assets/img/profile.jpg');
                @endphp
                <li class="nav-item topbar-user dropdown hidden-caret submenu">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ $avatarUrl }}" alt="Profil" class="avatar-img rounded-circle">
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Merhaba,</span>
                            <span class="fw-bold">{{ Str::words($userName, 1, '') }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <li>
                            <div class="user-box px-3 py-2">
                                <div class="u-text">
                                    <h4 class="mb-0 small fw-bold">{{ $userName }}</h4>
                                    <p class="text-muted mb-0" style="font-size:11px;">{{ $user?->email }}</p>
                                    <span class="badge mt-1" style="background:#3b5d50;font-size:10px;">
                                        {{ $user?->role === 'admin' ? 'Yönetici' : 'Satıcı' }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider m-0"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Çıkış Yap
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</div>
