@extends('app.layouts.main')

@section('seo_title', $store->name)
@section('seo_description', $store->description ? Str::limit(strip_tags($store->description), 155) : $store->name . ' mağazasının ürünlerini keşfedin.')
@section('og_image', $store->logo ? asset('storage/' . $store->logo) : asset('images/og-default.jpg'))

@section('content')

{{-- Mağaza Başlık Bandı --}}
<div style="background:#DCC6B7;padding:48px 0 36px;border-bottom:1px solid #c9b0a0;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

            {{-- Logo --}}
            @if ($store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}"
                     alt="{{ $store->name }}"
                     style="width:88px;height:88px;object-fit:cover;border-radius:14px;border:3px solid rgba(255,255,255,0.7);box-shadow:0 4px 16px rgba(0,0,0,0.1);flex-shrink:0;">
            @else
                <div style="width:88px;height:88px;border-radius:14px;background:rgba(255,255,255,0.5);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:32px;font-weight:800;color:#6b4e35;border:3px solid rgba(255,255,255,0.7);">
                    {{ strtoupper(substr($store->name, 0, 1)) }}
                </div>
            @endif

            {{-- Bilgiler --}}
            <div class="flex-grow-1">
                <h1 style="font-size:26px;font-weight:800;color:#3a2a1e;margin-bottom:8px;letter-spacing:-0.4px;">
                    {{ $store->name }}
                </h1>

                @if ($store->description)
                    <p style="font-size:14px;color:#6b4e35;margin-bottom:14px;max-width:620px;line-height:1.6;">
                        {{ $store->description }}
                    </p>
                @endif

                @if ($store->email)
                    <div style="font-size:13px;color:#8a6548;">
                        <i class="fas fa-envelope me-1" style="color:#C49A6B;"></i>{{ $store->email }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Ürünler --}}
<div class="untree_co-section product-section before-footer-section">
    <div class="container">

        {{-- Filtreler --}}
        <form method="GET" action="{{ route('store.show', $store->slug) }}" class="row g-2 mb-5 align-items-center">
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
                    <a href="{{ route('store.show', $store->slug) }}" class="btn btn-outline-secondary flex-fill">Temizle</a>
                @endif
            </div>
        </form>

        {{-- Ürün Grid --}}
        @if ($products->isEmpty())
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-box-open" style="font-size:36px;opacity:.3;display:block;margin-bottom:12px;"></i>
                <p>Aramanıza uygun ürün bulunamadı.</p>
            </div>
        @else
            <div class="row align-items-stretch">
                @foreach ($products as $product)
                    @php
                        $img = $product->firstImage?->image
                            ? asset('storage/' . $product->firstImage->image)
                            : asset('images/product-1.png');
                    @endphp
                    <div class="col-12 col-md-4 col-lg-3 mb-5 d-flex">
                        <a class="product-item w-100 d-flex flex-column" href="{{ route('shop.product', $product->slug) }}">
                            <img src="{{ $img }}"
                                 class="product-thumbnail"
                                 alt="{{ $product->name }}"
                                 style="object-fit:cover;height:220px;width:100%;border-radius:8px;">
                            <h3 class="product-title mt-3">{{ $product->name }}</h3>
                            <strong class="product-price">
                                @if ($product->stock === null)
                                    @php $minP = $product->variants->whereNotNull('price')->min('price'); @endphp
                                    {{ $minP !== null ? number_format($minP, 2, ',', '.') . ' ₺ ve üzeri' : '—' }}
                                @else
                                    {{ number_format($product->price, 2, ',', '.') }} ₺
                                @endif
                            </strong>
                            <span class="icon-cross">
                                <img src="{{ asset('images/cross.svg') }}" class="img-fluid" alt="">
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="d-flex justify-content-center mt-2">
                    {{ $products->links() }}
                </div>
            @endif
        @endif

    </div>
</div>

@endsection
