@extends('app.layouts.main')

@section('content')

<div class="error-page-wrap">

    {{-- Dekoratif arka plan çizgisi --}}
    <div class="error-bg-text" aria-hidden="true">404</div>

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-lg-7 col-md-9 text-center">

                {{-- Numara --}}
                <div class="error-number">404</div>

                {{-- Ayraç çizgisi --}}
                <div class="error-divider"></div>

                {{-- Mesaj --}}
                <h1 class="error-title">Sayfa Bulunamadı</h1>
                <p class="error-desc">
                    Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir.<br>
                    Endişelenmeyin, sizi doğru yöne yönlendirelim.
                </p>

                {{-- Butonlar --}}
                <div class="error-actions">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-error-primary">
                        Anasayfaya Dön
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-error-outline">
                        Ürünleri Gör
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

<style>
.error-page-wrap {
    position: relative;
    overflow: hidden;
    background: #eff2f1;
    padding: 60px 0 80px;
}

.error-bg-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: clamp(160px, 28vw, 320px);
    font-weight: 900;
    color: rgba(59, 93, 80, 0.055);
    letter-spacing: -10px;
    user-select: none;
    white-space: nowrap;
    pointer-events: none;
    line-height: 1;
}

.error-number {
    font-size: clamp(72px, 14vw, 130px);
    font-weight: 900;
    color: #3b5d50;
    line-height: 1;
    letter-spacing: -4px;
    margin-bottom: 20px;
}

.error-divider {
    width: 60px;
    height: 4px;
    background: #f9bf29;
    margin: 0 auto 28px;
    border-radius: 2px;
}

.error-title {
    font-size: clamp(22px, 4vw, 32px);
    font-weight: 700;
    color: #2f2f2f;
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.error-desc {
    font-size: 16px;
    color: #6a6a6a;
    line-height: 1.8;
    margin-bottom: 40px;
}

.error-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-error-primary {
    background: #3b5d50;
    border-color: #3b5d50;
    color: #fff;
    padding: 13px 36px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.3px;
    transition: background .2s, border-color .2s;
}
.btn-error-primary:hover {
    background: #314d43;
    border-color: #314d43;
    color: #fff;
}

.btn-error-outline {
    background: transparent;
    border: 2px solid #3b5d50;
    color: #3b5d50;
    padding: 12px 36px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.3px;
    transition: background .2s, color .2s;
    text-decoration: none;
    display: inline-block;
}
.btn-error-outline:hover {
    background: #3b5d50;
    color: #fff;
}
</style>

@endsection
