<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto text-center">

                <div class="mb-4" style="font-size: 5rem; color: #C49A6B; line-height: 1;">
                    <i class="fas fa-check-circle"></i>
                </div>

                <h2 class="mb-3" style="color: #2f2f2f; font-weight: 700;">Siparişiniz Alındı</h2>
                <p class="lead mb-5" style="color: rgba(90, 90, 90, 0.8);">
                    Siparişiniz başarıyla tamamlandı. Kargoya verildiğinde e-posta ile bilgilendirileceksiniz.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3" style="position: relative; z-index: 2;">
                    @auth
                        <a href="{{ route('account.orders') }}" class="btn btn-secondary">
                            Siparişlerime Git
                        </a>
                    @endauth
                    <a href="{{ route('shop') }}" class="btn btn-outline-black">
                        Alışverişe Devam Et
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>