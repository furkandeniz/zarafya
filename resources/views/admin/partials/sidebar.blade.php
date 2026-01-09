<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <p style="color: #ffffff; font-size: 30px; font-weight: bold; text-align: center; margin-top: 10px">Zarafya</p>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">

                    <li class="nav-item active">
                        <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="dashboard">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}">
                                        <span class="sub-item">Dashboard</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
                        <h4 class="text-section">Kısa Yollar</h4>
                    </li>
                    {{-- ✅ KULLANICI --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#users" class="collapsed" aria-expanded="false">
                            <i class="fas fa-users"></i>
                            <p>Kullanıcı</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="users">
                            <ul class="nav nav-collapse">

                                <li>
                                    <a href="{{ Route::has('admin.users.create') ? route('admin.users.create') : '#' }}">
                                        <span class="sub-item">Kullanıcı Ekleme</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}">
                                        <span class="sub-item">Kullanıcı Listeleme</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- ✅ KATEGORİLER --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#categories" class="collapsed" aria-expanded="false">
                            <i class="fas fa-tags"></i>
                            <p>Kategoriler</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="categories">
                            <ul class="nav nav-collapse">

                                <li>
                                    <a href="{{ Route::has('admin.categories.create') ? route('admin.categories.create') : '#' }}">
                                        <span class="sub-item">Kategori Ekleme</span>
                                    </a>
                                </li>

                                <li>
                                    {{-- Güncelleme genelde listeden seçilerek yapılır --}}
                                    <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}">
                                        <span class="sub-item">Kategori Güncelleme</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- ✅ ÜRÜNLER --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#products" class="collapsed" aria-expanded="false">
                            <i class="fas fa-box"></i>
                            <p>Ürünler</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="products">
                            <ul class="nav nav-collapse">

                                <li>
                                    <a href="{{ Route::has('admin.products.create') ? route('admin.products.create') : '#' }}">
                                        <span class="sub-item">Ürün Ekleme</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}">
                                        <span class="sub-item">Ürün Listeleme</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- ✅ SİPARİŞLER --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#orders" class="collapsed" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i>
                            <p>Siparişler</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="orders">
                            <ul class="nav nav-collapse">

                                <li>
                                    <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}">
                                        <span class="sub-item">Sipariş Listeleme</span>
                                    </a>
                                </li>

                                <li>
                                    {{-- Sipariş durumu genelde listeden seçilerek güncellenir --}}
                                    <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}">
                                        <span class="sub-item">Sipariş Durumu Güncelleme</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- ✅ BİLANÇO --}}
                    <li class="nav-item">
                        <a href="{{ Route::has('admin.balance.index') ? route('admin.balance.index') : '#' }}">
                            <i class="fas fa-balance-scale"></i>
                            <p>Bilanço</p>
                        </a>
                    </li>
                    {{-- ✅ MESAJLAR --}}
                    <li class="nav-item">
                        <a href="{{ Route::has('admin.messages.index') ? route('admin.messages.index') : '#' }}">
                            <i class="fas fa-envelope"></i>
                            <p>Mesajlar</p>
                        </a>
                    </li>





                </ul>
            </div>
        </div>

    {{-- nav list buraya aynen --}}
</div>
