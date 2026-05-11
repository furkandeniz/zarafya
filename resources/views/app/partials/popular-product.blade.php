<!-- Öne Çıkan Ürünler -->
<div class="popular-product">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title">Öne Çıkan Ürünler</h2>
            </div>
        </div>
        <div class="row">
            @forelse ($featured as $product)
                @php
                    $img = $product->firstImage?->image
                        ? asset('storage/' . $product->firstImage->image)
                        : asset('images/product-1.png');
                @endphp
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ $img }}" alt="{{ $product->name }}" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>{{ $product->name }}</h3>
                            <p>{{ Str::limit($product->description, 60) }}</p>
                            <p><a href="{{ route('shop.product', $product->slug) }}">İncele</a></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-muted">Henüz ürün bulunmuyor.</div>
            @endforelse
        </div>
    </div>
</div>
<!-- End Öne Çıkan Ürünler -->
