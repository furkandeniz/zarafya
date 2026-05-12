<!-- Footer -->
<footer class="footer-section">
    <div class="container relative">

        <div class="sofa-img">
            <img src="{{ asset('images/footer-2.png') }}" alt="Zarafya" class="img-fluid">
        </div>

        <div class="row g-5 mb-5">
            <div class="col-lg-4">
                <div class="mb-4 footer-logo-wrap">
                    <a href="{{ url('/') }}" class="footer-logo">Zarafya<span>.</span></a>
                </div>
                <p class="mb-4">
                    Her parçada incelik, her detayda özen. Yaşam alanlarınızı
                    Zarafya'nın seçkin koleksiyonuyla şekillendirin.
                </p>
                <ul class="list-unstyled custom-social">
                    <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
                    <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
                    <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
                    <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
                </ul>
            </div>

            <div class="col-lg-8">
                <div class="row links-wrap">
                    <div class="col-12 col-sm-12 col-md-12">
                        <ul class="list-unstyled">
                            <li><a href="{{ route('shop') }}">Ürünler</a></li>
                            <li><a href="{{ route('about') }}">Hakkımızda</a></li>
                            <li><a href="{{ route('services') }}">Hizmetlerimiz</a></li>
                            <li><a href="{{ route('contact') }}">İletişim</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top copyright">
            <div class="row pt-4">
                <div class="col-lg-6">
                    <p class="mb-2 text-center text-lg-start">
                        &copy; <script>document.write(new Date().getFullYear());</script> Zarafya. Tüm hakları saklıdır.
                    </p>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <ul class="list-unstyled d-inline-flex ms-auto">
                        <li class="me-4"><a href="#">Kullanım Koşulları</a></li>
                        <li><a href="#">Gizlilik Politikası</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</footer>
<!-- End Footer -->
