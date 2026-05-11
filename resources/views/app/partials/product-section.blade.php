<!-- Seçili Ürünler -->
<div class="product-section">
    <div class="container">
        <div class="row">

            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Özenle Seçilmiş Koleksiyon</h2>
                <p class="mb-4">
                    Zarafya'nın seçkin ürünlerini keşfedin.
                    Her parça kalite ve estetik anlayışıyla hazırlanmıştır.
                </p>
                <p><a href="{{ route('shop') }}" class="btn">Tümünü Gör</a></p>
            </div>

            @foreach ($featured->take(3) as $product)
                @php
                    $img = $product->firstImage?->image
                        ? asset('storage/' . $product->firstImage->image)
                        : asset('images/product-1.png');
                @endphp
                <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                    <a class="product-item" href="{{ route('shop.product', $product->slug) }}">
                        <img src="{{ $img }}"
                             class="img-fluid product-thumbnail"
                             alt="{{ $product->name }}"
                             style="object-fit:cover;height:220px;width:100%;">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <strong class="product-price">
                            {{ number_format($product->price, 2, ',', '.') }} ₺
                        </strong>
                        <span class="icon-cross">
                            <img src="{{ asset('images/cross.svg') }}" class="img-fluid" alt="">
                        </span>
                    </a>
                </div>
            @endforeach

        </div>
    </div>
</div>
<!-- End Seçili Ürünler -->
