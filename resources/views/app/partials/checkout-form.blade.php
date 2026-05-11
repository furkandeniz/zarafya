<div class="untree_co-section before-footer-section">
    <div class="container">

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf

            <div class="row g-5">

                {{-- ── Sol: Teslimat Bilgileri ── --}}
                <div class="col-lg-7">
                    <h2 class="h4 fw-bold mb-4" style="color:#3b5d50;">
                        <i class="fas fa-map-marker-alt me-2"></i>Teslimat Bilgileri
                    </h2>

                    <div class="p-4 border bg-white" style="border-radius:12px;">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">Ad <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name', $user?->name ? explode(' ', $user->name)[0] : '') }}"
                                       placeholder="Adınız" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">Soyad <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name', $user?->name ? (str_contains($user->name, ' ') ? substr($user->name, strpos($user->name, ' ') + 1) : '') : '') }}"
                                       placeholder="Soyadınız" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">E-posta <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $user?->email) }}"
                                       placeholder="ornek@mail.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">Telefon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone') }}"
                                       placeholder="05XX XXX XX XX" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-black fw-semibold">Adres <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address') }}"
                                       placeholder="Mahalle, Cadde / Sokak, Kapı No" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">İl <span class="text-danger">*</span></label>
                                <select name="city" id="citySelect" class="form-select" required onchange="loadDistricts(this.value)">
                                    <option value="">İl seçiniz...</option>
                                    @foreach (array_keys($cities) as $city)
                                        <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">İlçe <span class="text-danger">*</span></label>
                                <select name="district" id="districtSelect" class="form-select" required>
                                    <option value="">Önce il seçiniz...</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-black fw-semibold">Posta Kodu</label>
                                <input type="text" name="postal_code" class="form-control"
                                       value="{{ old('postal_code') }}"
                                       placeholder="34000">
                            </div>

                            <div class="col-12">
                                <label class="form-label text-black fw-semibold">Sipariş Notu</label>
                                <textarea name="order_note" class="form-control" rows="3"
                                          placeholder="Teslimatla ilgili eklemek istediğiniz notlar...">{{ old('order_note') }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- ── Ödeme Yöntemi ── --}}
                    <h2 class="h4 fw-bold mt-5 mb-4" style="color:#3b5d50;">
                        <i class="fas fa-credit-card me-2"></i>Ödeme Yöntemi
                    </h2>

                    <div class="p-4 border bg-white" style="border-radius:12px;">

                        {{-- Havale / EFT --}}
                        <div class="form-check mb-3 p-3 border rounded" style="cursor:pointer;"
                             onclick="selectPayment('havale')">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="pay_havale" value="havale" checked onchange="togglePaymentDetail()">
                            <label class="form-check-label w-100 ms-2" for="pay_havale" style="cursor:pointer;">
                                <span class="fw-semibold"><i class="fas fa-university me-1"></i> Havale / EFT</span>
                                <span class="text-muted small d-block mt-1">
                                    Sipariş onaylandıktan sonra banka hesabımıza transfer yapabilirsiniz.
                                    Hesap bilgileri onay e-postasında paylaşılacaktır.
                                </span>
                            </label>
                        </div>

                        {{-- Banka / Kredi Kartı --}}
                        <div class="form-check p-3 border rounded" style="cursor:pointer;"
                             onclick="selectPayment('kart')">
                            <input class="form-check-input" type="radio" name="payment_method"
                                   id="pay_kart" value="kart" onchange="togglePaymentDetail()">
                            <label class="form-check-label w-100 ms-2" for="pay_kart" style="cursor:pointer;">
                                <span class="fw-semibold"><i class="fas fa-credit-card me-1"></i> Banka / Kredi Kartı</span>
                                <span class="text-muted small d-block mt-1">
                                    Kart bilgilerinizi güvenli şekilde girin, ödemeniz anında tamamlansın.
                                </span>
                            </label>
                        </div>

                        {{-- Kart Bilgileri (kart seçilince açılır) --}}
                        <div id="cardDetail" class="mt-3" style="display:none;">
                            <div class="p-3 bg-light rounded" style="border:1px solid #dde;">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Kart Üzerindeki Ad Soyad</label>
                                    <input type="text" name="card_name" id="cardName" class="form-control"
                                           placeholder="AD SOYAD" autocomplete="cc-name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small mb-1">Kart Numarası</label>
                                    <input type="text" name="card_number" id="cardNumber" class="form-control"
                                           placeholder="0000 0000 0000 0000" maxlength="19"
                                           autocomplete="cc-number" inputmode="numeric">
                                    <div id="cardLogos" class="d-flex gap-2 align-items-center mt-2">
                                        <svg id="logo-visa" width="46" height="30" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg" style="opacity:.25;transition:opacity .2s,transform .2s;border-radius:4px;flex-shrink:0;">
                                            <rect width="750" height="471" rx="40" fill="#1A1F71"/>
                                            <text x="375" y="320" font-family="Arial" font-size="240" font-weight="bold" fill="white" text-anchor="middle">VISA</text>
                                        </svg>
                                        <svg id="logo-mastercard" width="46" height="30" viewBox="0 0 152 100" xmlns="http://www.w3.org/2000/svg" style="opacity:.25;transition:opacity .2s,transform .2s;border-radius:4px;flex-shrink:0;">
                                            <rect width="152" height="100" rx="8" fill="#252525"/>
                                            <circle cx="58" cy="50" r="30" fill="#EB001B"/>
                                            <circle cx="94" cy="50" r="30" fill="#F79E1B"/>
                                            <path d="M76 26.8a30 30 0 0 1 0 46.4A30 30 0 0 1 76 26.8z" fill="#FF5F00"/>
                                        </svg>
                                        <svg id="logo-troy" width="46" height="30" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg" style="opacity:.25;transition:opacity .2s,transform .2s;border-radius:4px;flex-shrink:0;">
                                            <rect width="200" height="120" rx="8" fill="#002d62"/>
                                            <rect x="0" y="75" width="200" height="45" fill="#e30613"/>
                                            <text x="100" y="60" font-family="Arial" font-size="44" font-weight="bold" fill="white" text-anchor="middle">TROY</text>
                                        </svg>
                                        <svg id="logo-amex" width="46" height="30" viewBox="0 0 200 120" xmlns="http://www.w3.org/2000/svg" style="opacity:.25;transition:opacity .2s,transform .2s;border-radius:4px;flex-shrink:0;">
                                            <rect width="200" height="120" rx="8" fill="#2E77BC"/>
                                            <text x="100" y="68" font-family="Arial" font-size="30" font-weight="bold" fill="white" text-anchor="middle">AMEX</text>
                                        </svg>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <label class="form-label fw-semibold small">Son Kullanma Tarihi</label>
                                        <input type="text" name="card_expiry" id="cardExpiry" class="form-control"
                                               placeholder="AA / YY" maxlength="7"
                                               autocomplete="cc-exp" inputmode="numeric">
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label fw-semibold small">CVV</label>
                                        <input type="text" name="card_cvv" class="form-control"
                                               placeholder="000" maxlength="4"
                                               autocomplete="cc-csc" inputmode="numeric">
                                    </div>
                                </div>
                                <p class="text-muted mt-2 mb-0" style="font-size:11px;">
                                    <i class="fas fa-lock me-1"></i> Kart bilgileriniz SSL ile şifrelenerek iletilir.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Sağ: Sipariş Özeti ── --}}
                <div class="col-lg-5">
                    <div style="position:sticky;top:100px;">

                        <h2 class="h4 fw-bold mb-4" style="color:#3b5d50;">
                            <i class="fas fa-receipt me-2"></i>Sipariş Özeti
                        </h2>

                        <div class="border bg-white p-4" style="border-radius:12px;">

                            {{-- Ürün listesi --}}
                            @foreach ($cart as $item)
                                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                         style="width:56px;height:56px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="fw-semibold small text-truncate">{{ $item['name'] }}</div>
                                        @if (!empty($item['variant_label']))
                                            <div class="text-muted" style="font-size:11px;">{{ $item['variant_label'] }}</div>
                                        @endif
                                        <div class="text-muted small">x {{ $item['quantity'] }}</div>
                                    </div>
                                    <div class="fw-bold small text-nowrap">
                                        {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }} ₺
                                    </div>
                                </div>
                            @endforeach

                            {{-- Ara toplam --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ara Toplam</span>
                                <span class="fw-semibold">{{ number_format($subtotal, 2, ',', '.') }} ₺</span>
                            </div>

                            {{-- İndirim --}}
                            @if ($coupon && $discountAmount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>İndirim <small class="text-muted">({{ $coupon['code'] }})</small></span>
                                    <span class="fw-semibold">&minus;{{ number_format($discountAmount, 2, ',', '.') }} ₺</span>
                                </div>
                            @endif

                            {{-- Kargo --}}
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">Kargo</span>
                                <span class="text-success fw-semibold">Ücretsiz</span>
                            </div>

                            {{-- Toplam --}}
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Toplam</span>
                                <span class="fw-bold fs-5" style="color:#3b5d50;">
                                    {{ number_format($total, 2, ',', '.') }} ₺
                                </span>
                            </div>

                            <button type="submit" class="btn btn-black btn-lg py-3 w-100"
                                    style="background:#3b5d50;border-color:#3b5d50;border-radius:8px;font-size:1rem;letter-spacing:.5px;">
                                <i class="fas fa-lock me-2"></i>Siparişi Tamamla
                            </button>

                            <p class="text-center text-muted mt-3 mb-0" style="font-size:12px;">
                                <i class="fas fa-shield-alt me-1"></i>
                                Bilgileriniz SSL ile şifrelenerek korunmaktadır.
                            </p>

                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('cart') }}" class="text-muted small">
                                <i class="fas fa-arrow-left me-1"></i> Sepete Geri Dön
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
const citiesData = @json($cities);
const oldDistrict = "{{ old('district') }}";

function loadDistricts(city, selected) {
    var select = document.getElementById('districtSelect');
    select.innerHTML = '<option value="">İlçe seçiniz...</option>';

    if (!city || !citiesData[city]) return;

    var districts = citiesData[city];
    districts.forEach(function(d) {
        var opt = document.createElement('option');
        opt.value = d;
        opt.textContent = d;
        if (d === (selected || oldDistrict)) opt.selected = true;
        select.appendChild(opt);
    });
}

function selectPayment(val) {
    document.getElementById('pay_' + val).checked = true;
    togglePaymentDetail();
}

function togglePaymentDetail() {
    var isCard = document.getElementById('pay_kart').checked;
    document.getElementById('cardDetail').style.display = isCard ? 'block' : 'none';
}

function detectCardType(number) {
    var n = number.replace(/\s/g, '');
    if (/^4/.test(n))                                      return 'visa';
    if (/^(5[1-5]|2[2-7])/.test(n))                       return 'mastercard';
    if (/^9792/.test(n))                                   return 'troy';
    if (/^3[47]/.test(n))                                  return 'amex';
    return null;
}

function highlightCard(type) {
    var all = ['visa', 'mastercard', 'troy', 'amex'];
    all.forEach(function(t) {
        var el = document.getElementById('logo-' + t);
        if (!el) return;
        el.style.opacity   = (!type || t === type) ? '1' : '0.15';
        el.style.transform = (t === type) ? 'scale(1.12)' : 'scale(1)';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var cardName = document.getElementById('cardName');
    if (cardName) {
        cardName.addEventListener('input', function() {
            var pos = this.selectionStart;
            this.value = this.value.toLocaleUpperCase('tr-TR');
            this.setSelectionRange(pos, pos);
        });
    }

    var cardNum = document.getElementById('cardNumber');
    if (cardNum) {
        cardNum.addEventListener('input', function() {
            var v = this.value.replace(/\D/g, '').substring(0, 16);
            this.value = v.replace(/(.{4})/g, '$1 ').trim();
            highlightCard(detectCardType(this.value));
        });
    }

    var cardExp = document.getElementById('cardExpiry');
    if (cardExp) {
        cardExp.addEventListener('input', function() {
            var v = this.value.replace(/\D/g, '').substring(0, 4);
            if (v.length >= 3) v = v.substring(0, 2) + ' / ' + v.substring(2);
            this.value = v;
        });
    }
});

// Sayfa yüklenince eski seçimi geri yükle (validation hatası sonrası)
document.addEventListener('DOMContentLoaded', function() {
    var city = document.getElementById('citySelect').value;
    if (city) loadDistricts(city, oldDistrict);
});
</script>
@endpush
