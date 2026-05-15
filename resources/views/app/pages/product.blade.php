@extends('app.layouts.main')

@php
    $seoDesc   = Str::limit(strip_tags($product->description ?? ''), 155) ?: ($product->name . ' — Zarafya\'da el yapımı, özgün bir ürün.');
    $seoImage  = $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image) : asset('images/og-default.jpg');
    $stockSchema = $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';
@endphp

@section('seo_title', $product->name)
@section('seo_description', $seoDesc)
@section('og_type', 'product')
@section('og_image', $seoImage)

@push('jsonld')
@php
$_jsonLd = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product->name,
    'description' => Str::limit(strip_tags($product->description ?? ''), 200),
    'url'         => url()->current(),
    'brand'       => ['@type' => 'Brand', 'name' => 'Zarafya'],
    'offers'      => [
        '@type'         => 'Offer',
        'priceCurrency' => 'TRY',
        'price'         => (string) $product->price,
        'availability'  => $stockSchema,
        'url'           => url()->current(),
    ],
];
if ($product->images->isNotEmpty()) {
    $_jsonLd['image'] = $seoImage;
}
@endphp
<script type="application/ld+json">{!! json_encode($_jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

@include('app.partials.page-hero', ['title' => $product->name, 'image' => 'urun-detayi.png'])

<div class="untree_co-section before-footer-section">
    <div class="container">

        <div class="row g-5">

            {{-- Sol: Fotoğraf Galerisi --}}
            <div class="col-lg-6">
                @php $images = $product->images; @endphp

                <div class="mb-3" style="position:relative;cursor:zoom-in;" onclick="openLightbox(document.getElementById('mainPhoto').src)">
                    <img id="mainPhoto"
                         src="{{ $images->isNotEmpty() ? asset('storage/' . $images->first()->image) : asset('images/product-1.png') }}"
                         alt="{{ $product->name }}"
                         class="img-fluid w-100"
                         style="height:420px;object-fit:contain;background:#f8f8f8;border-radius:8px;">
                    <span style="position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,0.45);color:#fff;border-radius:6px;padding:5px 8px;font-size:13px;pointer-events:none;">
                        <i class="fas fa-expand-alt"></i>
                    </span>
                </div>

                @if ($images->count() > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($images as $i => $img)
                            <img src="{{ asset('storage/' . $img->image) }}"
                                 alt="{{ $product->name }} {{ $i + 1 }}"
                                 onclick="switchPhoto(this, '{{ asset('storage/' . $img->image) }}')"
                                 style="width:72px;height:72px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid {{ $i === 0 ? '#C49A6B' : '#eee' }};transition:border-color .2s;"
                                 onmouseover="this.style.borderColor='#C49A6B'"
                                 onmouseout="if(!this.classList.contains('thumb-active')) this.style.borderColor='#eee'">
                        @endforeach
                    </div>
                @endif

                {{-- Lightbox --}}
                <div id="lightbox" onclick="closeLightbox()"
                     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out;">
                    <img id="lightboxImg" src="" alt=""
                         style="max-width:92vw;max-height:92vh;object-fit:contain;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6);">
                    <button onclick="closeLightbox()" title="Kapat"
                            style="position:fixed;top:18px;right:22px;background:rgba(255,255,255,0.15);color:#fff;border:none;border-radius:50%;width:38px;height:38px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                        &times;
                    </button>
                </div>
            </div>

            {{-- Sağ: Ürün Bilgileri --}}
            <div class="col-lg-6">

                <p class="text-muted small mb-2">
                    <a href="{{ route('shop') }}" class="text-muted">Shop</a>
                    @if ($product->category)
                        &rsaquo;
                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}"
                           class="text-muted">{{ $product->category->name }}</a>
                    @endif
                </p>

                <h1 class="mb-2" style="font-size:1.8rem;font-weight:700;">{{ $product->name }}</h1>

                {{-- Fiyat (variant seçilince JS günceller) --}}
                <p id="mainPrice" class="mb-4" style="font-size:1.6rem;font-weight:700;color:#C49A6B;">
                    {{ number_format($product->price, 2, ',', '.') }} ₺
                </p>

                @if ($product->description)
                    <p class="text-muted mb-4" style="line-height:1.8;">{{ $product->description }}</p>
                @endif

                <hr>

                @if ($product->attributes->isNotEmpty())
                    {{-- ── Variant Seçici ── --}}
                    @foreach ($product->attributes as $attr)
                        <div class="mb-3">
                            <div class="text-muted small mb-1">{{ $attr->name }}</div>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach ($attr->values as $val)
                                    <button type="button"
                                            class="attr-btn"
                                            data-attr="{{ $attr->name }}"
                                            data-value="{{ $val }}"
                                            onclick="selectAttr(this)">
                                        {{ $val }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Seçim yapılmadan önce prompt --}}
                    <div id="selectPrompt" class="text-muted small mb-3" style="font-style:italic;">
                        Lütfen yukarıdaki seçenekleri belirleyin.
                    </div>

                    {{-- Variant stok bilgisi (seçim sonrası) --}}
                    <div id="variantInfo" class="mb-3" style="display:none;">
                        <div class="d-flex align-items-center gap-3 mb-1">
                            <span class="text-muted small" style="min-width:80px;">Stok</span>
                            <span id="variantStockBadge"></span>
                        </div>
                        <div id="variantPriceRow" class="d-flex align-items-center gap-3" style="display:none;">
                            <span class="text-muted small" style="min-width:80px;">Varyant Fiyatı</span>
                            <span id="variantPriceText" style="font-weight:600;color:#C49A6B;"></span>
                        </div>
                    </div>

                    {{-- Varyant tükendi: bildirim formu --}}
                    <div id="variantNotifyBox" style="display:none;" class="mb-3">
                        @include('app.partials.stock-notify-form', ['formId' => 'variantNotifyForm'])
                    </div>

                    @if ($product->store)
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="text-muted small" style="min-width:80px;">Mağaza</span>
                            <div class="d-flex align-items-center gap-2">
                                @if ($product->store->logo)
                                    <img src="{{ asset('storage/' . $product->store->logo) }}"
                                         style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                                @endif
                                <span class="small fw-semibold">{{ $product->store->name }}</span>
                            </div>
                        </div>
                        @include('app.partials.shipping-badge', ['store' => $product->store])
                    @endif

                    @if ($product->category)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="text-muted small" style="min-width:80px;">Kategori</span>
                            <span class="small">{{ $product->category->name }}</span>
                        </div>
                    @endif

                    <hr>

                    @if (session('cart_success'))
                        <div class="alert alert-success py-2 mb-3">{{ session('cart_success') }}</div>
                    @endif
                    @if (session('cart_warning'))
                        <div class="alert alert-warning py-2 mb-3">{{ session('cart_warning') }}</div>
                    @endif
                    @if (session('cart_error'))
                        <div class="alert alert-danger py-2 mb-3">{{ session('cart_error') }}</div>
                    @endif

                    <form action="{{ route('cart.add', $product->slug) }}" method="POST">
                        @csrf
                        <input type="hidden" name="variant_id" id="variantIdInput">
                        <div class="d-flex gap-2 mt-4 align-items-center">
                            <div class="input-group d-flex align-items-center quantity-container"
                                 style="min-width:200px;margin-bottom:0;gap:6px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-black decrease" type="button">&minus;</button>
                                </div>
                                <input type="text" name="quantity" value="1"
                                       class="form-control text-center quantity-amount"
                                       aria-label="Quantity">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-black increase" type="button">&plus;</button>
                                </div>
                            </div>
                            <button type="submit" id="addToCartBtn"
                                    class="btn btn-outline-secondary px-5" disabled>
                                Sepete Ekle
                            </button>
                        </div>
                    </form>

                @else
                    {{-- ── Varyantsız Ürün ── --}}
                    @php $stockQty = $product->stock ?? 0; @endphp

                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="text-muted small" style="min-width:80px;">Stok</span>
                        @if ($stockQty > 0)
                            <span class="badge bg-success">{{ $stockQty }} adet</span>
                        @else
                            <span class="badge bg-danger">Tükendi</span>
                        @endif
                    </div>

                    @if ($product->store)
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="text-muted small" style="min-width:80px;">Mağaza</span>
                            <div class="d-flex align-items-center gap-2">
                                @if ($product->store->logo)
                                    <img src="{{ asset('storage/' . $product->store->logo) }}"
                                         style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                                @endif
                                <span class="small fw-semibold">{{ $product->store->name }}</span>
                            </div>
                        </div>
                        @include('app.partials.shipping-badge', ['store' => $product->store])
                    @endif

                    @if ($product->category)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="text-muted small" style="min-width:80px;">Kategori</span>
                            <span class="small">{{ $product->category->name }}</span>
                        </div>
                    @endif

                    <hr>

                    @if (session('cart_success'))
                        <div class="alert alert-success py-2 mb-3">{{ session('cart_success') }}</div>
                    @endif
                    @if (session('cart_warning'))
                        <div class="alert alert-warning py-2 mb-3">{{ session('cart_warning') }}</div>
                    @endif
                    @if (session('cart_error'))
                        <div class="alert alert-danger py-2 mb-3">{{ session('cart_error') }}</div>
                    @endif

                    @if ($stockQty > 0)
                        <form action="{{ route('cart.add', $product->slug) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2 mt-4 align-items-center">
                                <div class="input-group d-flex align-items-center quantity-container"
                                     style="min-width:200px;margin-bottom:0;gap:6px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-black decrease" type="button">&minus;</button>
                                    </div>
                                    <input type="text" name="quantity" value="1"
                                           class="form-control text-center quantity-amount"
                                           aria-label="Quantity">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-black increase" type="button">&plus;</button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-outline-secondary px-5">
                                    Sepete Ekle
                                </button>
                            </div>
                        </form>
                    @else
                        <button class="btn btn-outline-secondary mt-4 w-100" disabled>Stokta Yok</button>
                        <div class="mt-3">
                            @include('app.partials.stock-notify-form', ['formId' => 'simpleNotifyForm'])
                        </div>
                    @endif
                @endif

                <a href="{{ route('shop') }}" class="btn btn-outline-secondary mt-3 w-100">← Alışverişe Devam</a>

            </div>
        </div>

        {{-- İlgili Ürünler --}}
        @if ($related->isNotEmpty())
            <div class="product-section" style="padding: 3rem 0 1rem;">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h3 class="fw-bold">İlgili Ürünler</h3>
                    </div>
                    @foreach ($related as $rel)
                        @php
                            $relImg = $rel->firstImage?->image
                                ? asset('storage/' . $rel->firstImage->image)
                                : asset('images/product-1.png');
                        @endphp
                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <a class="product-item" href="{{ route('shop.product', $rel->slug) }}">
                                <img src="{{ $relImg }}"
                                     class="img-fluid product-thumbnail"
                                     alt="{{ $rel->name }}"
                                     style="object-fit:cover;height:200px;width:100%;">
                                <h3 class="product-title">{{ $rel->name }}</h3>
                                <strong class="product-price">
                                    {{ number_format($rel->price, 2, ',', '.') }} ₺
                                </strong>
                                <span class="icon-cross">
                                    <img src="{{ asset('images/cross.svg') }}" class="img-fluid" alt="">
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function switchPhoto(thumb, src) {
    document.getElementById('mainPhoto').src = src;
    document.querySelectorAll('.thumb-active').forEach(el => {
        el.classList.remove('thumb-active');
        el.style.borderColor = '#eee';
    });
    thumb.classList.add('thumb-active');
    thumb.style.borderColor = '#C49A6B';
}

function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightboxImg').src = src;
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endpush

@if ($product->attributes->isNotEmpty())
@push('scripts')
<style>
.attr-btn {
    display: inline-block;
    padding: 6px 16px;
    background: #fff;
    color: #333;
    border: 1.5px solid #d0d0d0;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
    user-select: none;
}
.attr-btn:hover {
    border-color: #28a745;
}
.attr-btn--selected {
    border: 2px solid #28a745;
    color: #28a745;
    font-weight: 600;
    box-shadow: 0 0 0 3px rgba(40,167,69,.12);
}
</style>
<script>
const variants  = @json($product->variants);
const attrNames = @json($product->attributes->pluck('name'));
const basePrice = {{ $product->price }};
let   selected  = {};

function fmt(n) {
    return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
}

function selectAttr(btn) {
    const attr = btn.dataset.attr;
    const val  = btn.dataset.value;

    // Aynı özellik grubundaki diğer seçimleri kaldır
    document.querySelectorAll('.attr-btn').forEach(b => {
        if (b.dataset.attr === attr) {
            b.classList.remove('attr-btn--selected');
            b.innerHTML = b.dataset.value;
        }
    });

    btn.classList.add('attr-btn--selected');
    btn.innerHTML = '✓ ' + val;
    selected[attr] = val;

    updateVariant();
}

function updateVariant() {
    const allSelected = attrNames.every(n => selected[n] !== undefined);
    const prompt      = document.getElementById('selectPrompt');
    const info        = document.getElementById('variantInfo');
    const addBtn      = document.getElementById('addToCartBtn');

    if (!allSelected) {
        prompt.style.display = '';
        info.style.display   = 'none';
        addBtn.disabled      = true;
        return;
    }

    // Eşleşen varyantı bul
    const variant = variants.find(v =>
        attrNames.every(n => v.combination[n] === selected[n])
    );

    prompt.style.display = 'none';
    info.style.display   = '';

    if (!variant) {
        document.getElementById('variantStockBadge').innerHTML =
            '<span class="badge bg-secondary">Bu kombinasyon mevcut değil</span>';
        addBtn.disabled = true;
        document.getElementById('variantIdInput').value = '';
        return;
    }

    document.getElementById('variantIdInput').value = variant.id;

    // Stok badge + bildirim kutusu
    const stockBadge = document.getElementById('variantStockBadge');
    const notifyBox  = document.getElementById('variantNotifyBox');

    if (variant.stock > 0) {
        stockBadge.innerHTML = `<span class="badge bg-success">${variant.stock} adet</span>`;
        addBtn.disabled = false;
        if (notifyBox) notifyBox.style.display = 'none';
    } else {
        stockBadge.innerHTML = '<span class="badge bg-danger">Tükendi</span>';
        addBtn.disabled = true;
        if (notifyBox) {
            notifyBox.style.display = '';
            const labelInput = notifyBox.querySelector('.notify-variant-label');
            if (labelInput) labelInput.value = variant.label;
        }
    }

    // Komisyon dahil fiyatı güncelle
    const mainPrice = document.getElementById('mainPrice');
    if (variant.price !== null) {
        mainPrice.textContent = fmt(variant.price) + ' ₺';
    } else {
        mainPrice.textContent = fmt(basePrice) + ' ₺';
    }
    document.getElementById('variantPriceRow').style.display = 'none';
}
</script>
@endpush
@endif

@endsection
