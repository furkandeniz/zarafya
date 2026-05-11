@extends('app.layouts.main')

@section('content')

@include('app.partials.page-hero', ['title' => 'Ödeme', 'subtitle' => 'Nasıl devam etmek istersiniz?', 'image' => 'odeme.png'])

<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <h3 class="text-center fw-bold mb-2">Nasıl devam etmek istersiniz?</h3>
                <p class="text-center text-muted mb-5">Sipariş sürecinizi hızlandırmak için giriş yapabilir ya da misafir olarak devam edebilirsiniz.</p>

                {{-- Giriş Yap --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div style="width:48px;height:48px;background:#f0f5f3;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-user" style="color:#3b5d50;font-size:18px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Giriş Yap ve Devam Et</h5>
                                <p class="text-muted small mb-3">Hesabınızdaki bilgiler otomatik doldurulur, siparişlerinizi takip edebilirsiniz.</p>
                                <a href="{{ route('app.login') }}"
                                   class="btn btn-black btn-block w-100">
                                    Giriş Yap
                                </a>
                                <p class="text-center text-muted small mt-2 mb-0">
                                    Hesabınız yok mu?
                                    <a href="{{ route('app.register') }}" class="text-decoration-underline">Kayıt Ol</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Misafir --}}
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div style="width:48px;height:48px;background:#f5f5f5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-shopping-bag" style="color:#888;font-size:18px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Misafir Olarak Devam Et</h5>
                                <p class="text-muted small mb-3">Hesap oluşturmadan alışverişinizi tamamlayabilirsiniz.</p>
                                <form action="{{ route('checkout.guest') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-black btn-block w-100">
                                        Misafir Olarak Devam Et
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('cart') }}" class="text-muted small">
                        <i class="fas fa-arrow-left me-1"></i> Sepete Geri Dön
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
