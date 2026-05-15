<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <div style="text-align:center;padding-top:4px;padding-bottom:0;margin:0;line-height:1;">
                    <img src="{{ asset('images/zarafya_logo_white.png') }}" alt="Zarafya Logo" style="width: 50%; height: auto;">
                </div>
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
                    {{-- ✅ KULLANICI (sadece admin) --}}
                    @if (auth()->user()->isAdmin())
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
                    @endif
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

                                <li>
                                    <a href="{{ route('admin.return-requests.index') }}">
                                        <span class="sub-item">İade Talepleri</span>
                                        @if (\Illuminate\Support\Facades\Schema::hasTable('return_requests'))
                                            @php $pendingReturns = \App\Models\ReturnRequest::where('status', 'pending')->count(); @endphp
                                            @if ($pendingReturns > 0)
                                                <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">{{ $pendingReturns }}</span>
                                            @endif
                                        @endif
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    {{-- ✅ AYARLAR (sadece admin) --}}
                    @if (auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}">
                            <i class="fas fa-cog"></i>
                            <p>Ayarlar</p>
                        </a>
                    </li>
                    @endif
                    {{-- ✅ BİLANÇO (admin + satıcı) --}}
                    @if (auth()->user()->isAdmin() || auth()->user()->isSeller())
                    <li class="nav-item">
                        <a href="{{ route('admin.balance.index') }}">
                            <i class="fas fa-balance-scale"></i>
                            <p>Bilanço</p>
                        </a>
                    </li>
                    {{-- ✅ MESAJLAR (sadece admin) --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}">
                            <i class="fas fa-envelope"></i>
                            <p>Mesajlar</p>
                            @php $unread = \App\Models\ContactMessage::whereNull('read_at')->count(); @endphp
                            @if ($unread > 0)
                                <span class="badge badge-count bg-danger">{{ $unread }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                    {{-- ✅ STOK BİLDİRİMLERİ (sadece admin) --}}
                    @if (auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.stock-notifications.index') }}">
                            <i class="fas fa-bell"></i>
                            <p>Stok Bildirimleri</p>
                            @php $pendingNotify = \App\Models\StockNotification::whereNull('notified_at')->count(); @endphp
                            @if ($pendingNotify > 0)
                                <span class="badge badge-count bg-warning text-dark">{{ $pendingNotify }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                    {{-- ✅ MAĞAZALAR --}}
                    @if (auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#stores" class="collapsed" aria-expanded="false">
                            <i class="fas fa-store"></i>
                            <p>Mağazalar</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="stores">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ Route::has('admin.stores.create') ? route('admin.stores.create') : '#' }}">
                                        <span class="sub-item">Mağaza Ekle</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ Route::has('admin.stores.index') ? route('admin.stores.index') : '#' }}">
                                        <span class="sub-item">Mağaza Listesi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @else
                    {{-- Satıcı: sadece kendi mağazasını görebilir --}}
                    @if (auth()->user()->store_id)
                    <li class="nav-item">
                        <a href="{{ route('admin.stores.edit', auth()->user()->store_id) }}">
                            <i class="fas fa-store"></i>
                            <p>Mağazam</p>
                        </a>
                    </li>
                    @if (auth()->user()->store)
                    <li class="nav-item">
                        <a href="{{ route('store.show', auth()->user()->store->slug) }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <p>Mağaza Sayfam</p>
                        </a>
                    </li>
                    @endif
                    @endif
                    @endif
                    {{-- ✅ BLOG (sadece admin) --}}
                    @if (auth()->user()->isAdmin())
                    @php $pendingComments = \App\Models\BlogComment::pending()->count(); @endphp
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#blogs" class="collapsed" aria-expanded="false">
                            <i class="fas fa-pen-nib"></i>
                            <p>Blog</p>
                            @if($pendingComments > 0)
                                <span class="badge badge-count bg-danger">{{ $pendingComments }}</span>
                            @endif
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="blogs">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('admin.blogs.create') }}">
                                        <span class="sub-item">Yazı Ekle</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.blogs.index') }}">
                                        <span class="sub-item">Tüm Yazılar</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.blog-comments.index') }}">
                                        <span class="sub-item">Yorumlar</span>
                                        @if($pendingComments > 0)
                                            <span class="badge bg-danger ms-1" style="font-size:.65rem;">{{ $pendingComments }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    {{-- ✅ KUPONLAR --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#coupons" class="collapsed" aria-expanded="false">
                            <i class="fas fa-ticket-alt"></i>
                            <p>Kuponlar</p>
                            <span class="caret"></span>
                        </a>

                        <div class="collapse" id="coupons">
                            <ul class="nav nav-collapse">

                                <li>
                                    <a href="{{ Route::has('admin.coupons.create') ? route('admin.coupons.create') : '#' }}">
                                        <span class="sub-item">Kupon Ekle</span>
                                    </a>
                                </li>

                                <li>
                                    {{-- Güncelleme genelde listeden seçilerek yapılır --}}
                                    <a href="{{ Route::has('admin.coupons.index') ? route('admin.coupons.index') : '#' }}">
                                        <span class="sub-item">Kupon Güncelle</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>






                </ul>
            </div>
        </div>

    {{-- nav list buraya aynen --}}
</div>
