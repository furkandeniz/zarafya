<div class="untree_co-section product-section before-footer-section">
    <div class="container">

        {{-- Filtreler --}}
        <form method="GET" action="{{ route('shop') }}" class="row g-2 mb-5 align-items-center">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Ürün ara..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-4">
                <select name="category" class="form-select">
                    <option value="">Tüm Kategoriler</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filtrele</button>
                @if (request('search') || request('category'))
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary flex-fill">Temizle</a>
                @endif
            </div>
        </form>

        {{-- Ürün grid --}}
        @if ($products->isEmpty())
            <div class="col-12 text-center py-5 text-muted">
                <p>Aramanıza uygun ürün bulunamadı.</p>
            </div>
        @else
            <div class="row">
                @foreach ($products as $product)
                    @php
                        $img = $product->firstImage?->image
                            ? asset('storage/' . $product->firstImage->image)
                            : asset('images/product-1.png');
                    @endphp
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
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
                                <img src="{{ asset('images/cross.svg') }}" class="img-fluid" alt="Add to cart">
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div class="d-flex justify-content-center mt-2">
                    {{ $products->links() }}
                </div>
            @endif
        @endif

    </div>
</div>
