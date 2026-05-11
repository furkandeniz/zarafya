<div class="untree_co-section before-footer-section">
    <div class="container">

        @if (session('cart_success'))
            <div class="alert alert-success mb-4">{{ session('cart_success') }}</div>
        @endif

        @if (empty($cart))

            <div class="text-center py-5">
                <img src="{{ asset('images/cart.svg') }}" alt="Boş Sepet" style="width:64px;opacity:.3;margin-bottom:1rem;">
                <h3 class="text-muted">Sepetiniz boş</h3>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Alışverişe Başla</a>
            </div>

        @else

            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Görsel</th>
                                    <th class="product-name">Ürün</th>
                                    <th class="product-price">Fiyat</th>
                                    <th class="product-quantity">Adet</th>
                                    <th class="product-total">Toplam</th>
                                    <th class="product-remove">Kaldır</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="{{ route('shop.product', $item['slug']) }}">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                                     class="img-fluid" style="width:80px;height:80px;object-fit:cover;">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">
                                                <a href="{{ route('shop.product', $item['slug']) }}"
                                                   class="text-black">{{ $item['name'] }}</a>
                                            </h2>
                                            @if (!empty($item['variant_label']))
                                                <small class="text-muted">{{ $item['variant_label'] }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item['price'], 2, ',', '.') }} ₺</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item['cart_key'] ?? $item['id']) }}"
                                                  method="POST" class="d-flex align-items-center gap-1">
                                                @csrf @method('PATCH')
                                                <div class="input-group mb-3 d-flex align-items-center quantity-container"
                                                     style="max-width:120px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-outline-black decrease" type="button">&minus;</button>
                                                    </div>
                                                    <input type="text" name="quantity"
                                                           value="{{ $item['quantity'] }}"
                                                           class="form-control text-center quantity-amount"
                                                           aria-label="Quantity">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-black increase" type="button">&plus;</button>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-black ms-1"
                                                        title="Güncelle">↻</button>
                                            </form>
                                        </td>
                                        <td class="fw-bold">
                                            {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }} ₺
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item['cart_key'] ?? $item['id']) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-black btn-sm">✕</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">

                {{-- Sol: Butonlar + Kupon --}}
                <div class="col-md-6">

                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-black btn-sm btn-block"
                                        onclick="return confirm('Sepet temizlensin mi?')">
                                    Sepeti Temizle
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('shop') }}" class="btn btn-outline-black btn-sm btn-block">
                                Alışverişe Devam
                            </a>
                        </div>
                    </div>

                    {{-- Kupon Alanı --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-black h4" for="coupon">Kupon Kodu</label>
                            <p class="text-muted small">Kupon kodunuz yalnızca ilgili mağazanın ürünlerine uygulanır.</p>
                        </div>

                        @if ($coupon)
                            {{-- Aktif kupon --}}
                            <div class="col-md-12">
                                <div class="alert alert-success d-flex align-items-center justify-content-between mb-0">
                                    <div>
                                        <strong>{{ $coupon['code'] }}</strong> uygulandı
                                        <span class="text-muted small ms-2">({{ $coupon['store_name'] }})</span>
                                        <br>
                                        <small>
                                            {{ $coupon['discount_type'] === 'percent'
                                                ? '%' . $coupon['discount_value'] . ' indirim'
                                                : number_format($coupon['discount_value'], 2, ',', '.') . ' ₺ indirim' }}
                                        </small>
                                    </div>
                                    <form action="{{ route('coupon.remove') }}" method="POST" class="ms-3">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Kaldır</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            {{-- Kupon giriş formu --}}
                            <form action="{{ route('coupon.apply') }}" method="POST" class="col-md-12">
                                @csrf
                                <div class="d-flex gap-2">
                                    <input type="text" name="coupon"
                                           class="form-control py-3 @error('coupon') is-invalid @enderror"
                                           id="coupon" placeholder="Kupon Kodu"
                                           value="{{ old('coupon') }}"
                                           style="text-transform:uppercase;">
                                    <button class="btn btn-black px-4" type="submit">Uygula</button>
                                </div>
                                @error('coupon')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </form>
                        @endif
                    </div>

                </div>

                {{-- Sağ: Sipariş Özeti --}}
                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">

                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-4">
                                    <h3 class="text-black h4 text-uppercase">Sipariş Özeti</h3>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6"><span class="text-black">Ara Toplam</span></div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black">{{ number_format($subtotal, 2, ',', '.') }} ₺</strong>
                                </div>
                            </div>

                            @if ($coupon && $discountAmount > 0)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <span class="text-success">
                                            İndirim
                                            <small class="d-block text-muted" style="font-size:11px;">{{ $coupon['code'] }}</small>
                                        </span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong class="text-success">
                                            &minus;{{ number_format($discountAmount, 2, ',', '.') }} ₺
                                        </strong>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-5 border-top pt-3">
                                <div class="col-md-6"><span class="text-black fw-bold">Toplam</span></div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black" style="font-size:1.1rem;">
                                        {{ number_format($total, 2, ',', '.') }} ₺
                                    </strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('checkout.gate') }}"
                                       class="btn btn-black btn-lg py-3 btn-block">
                                        Ödemeye Geç
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        @endif

    </div>
</div>
