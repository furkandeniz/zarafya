@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Yeni Ürün Ekle</h3>
                <h6 class="op-7 mb-2">Yeni bir ürün oluştur</h6>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ürün Bilgileri</h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Ürün Adı <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Ürün adı"
                                       required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="store_id">Mağaza</label>
                                        <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                    {{ $store->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('store_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="category_id">Kategori</label>
                                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Ürün açıklaması (isteğe bağlı)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fiyatlar --}}
                            <div id="pricingSection" class="card border mb-3">
                                <div class="card-header py-2">
                                    <span class="fw-semibold">Fiyatlandırma</span>
                                </div>
                                <div class="card-body pb-2">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="cost_price" class="form-label">Maliyet Fiyatı (₺)</label>
                                            <input type="number" id="cost_price" name="cost_price" step="0.01" min="0"
                                                   class="form-control @error('cost_price') is-invalid @enderror"
                                                   value="{{ old('cost_price') }}" placeholder="0.00">
                                            @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="expected_price" class="form-label">Beklenen Satış Fiyatı (₺) <span class="text-danger">*</span></label>
                                            <input type="number" id="expected_price" name="expected_price" step="0.01" min="0"
                                                   class="form-control @error('expected_price') is-invalid @enderror"
                                                   value="{{ old('expected_price') }}" placeholder="0.00"
                                                   required oninput="calcCommission()">
                                            @error('expected_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Komisyon Dahil Fiyat (₺)
                                                <span class="badge bg-secondary ms-1" style="font-size:10px;">%{{ round(App\Models\Product::COMMISSION_RATE * 100) }} komisyon</span>
                                            </label>
                                            <input type="text" id="commission_price_display"
                                                   class="form-control bg-light fw-semibold"
                                                   placeholder="—" readonly>
                                            <div class="form-text text-muted">Müşteriye gösterilen fiyat. Otomatik hesaplanır.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Varyant Toggle --}}
                            <div class="card border mb-3">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants"
                                                   value="1" {{ old('has_variants') ? 'checked' : '' }}
                                                   onchange="toggleVariants(this.checked)">
                                            <label class="form-check-label fw-semibold" for="has_variants">
                                                Bu ürünün varyantları var (renk, beden, boyut vb.)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Basit stok (varyant yok) --}}
                            <div id="simpleStockSection" class="form-group mb-4">
                                <label for="stock">Stok <span class="text-danger">*</span></label>
                                <input type="number" id="stock" name="stock" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', 0) }}">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Varyant bölümü --}}
                            <div id="variantSection" style="display:none;" class="mb-4">

                                <input type="hidden" name="attributes_json" id="attributesJson">
                                <input type="hidden" name="variants_json" id="variantsJson">

                                {{-- Özellik tanımlama --}}
                                <div class="card border mb-3">
                                    <div class="card-header d-flex align-items-center justify-content-between py-2">
                                        <span class="fw-semibold">Özellikler</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAttributeRow()">
                                            <i class="fa fa-plus me-1"></i> Özellik Ekle
                                        </button>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="attributeRows">
                                            {{-- JS ile satır eklenir --}}
                                        </div>
                                        <div class="form-text text-muted">
                                            Örnek: <strong>Renk</strong> → Kırmızı, Mavi, Yeşil &nbsp;|&nbsp;
                                            <strong>Beden</strong> → XS, S, M, L, XL
                                        </div>
                                    </div>
                                </div>

                                {{-- Varyant tablosu --}}
                                <div class="card border" id="variantTableCard" style="display:none;">
                                    <div class="card-header d-flex align-items-center justify-content-between py-2">
                                        <span class="fw-semibold">Varyant Stok & Fiyat</span>
                                        <span class="badge bg-secondary" id="variantCount">0 varyant</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-3">Varyant</th>
                                                        <th style="width:130px;">Maliyet (₺)</th>
                                                        <th style="width:150px;">Beklenen Satış (₺) <span class="text-danger">*</span></th>
                                                        <th style="width:160px;">Komisyon Dahil (₺)</th>
                                                        <th style="width:110px;">Stok <span class="text-danger">*</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variantTableBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Fotoğraflar --}}
                            <div class="form-group mb-4">
                                <label>Ürün Fotoğrafları</label>
                                <div class="border rounded p-3">
                                    <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mb-2"></div>
                                    <label for="images" class="btn btn-outline-secondary btn-sm mb-0">
                                        <i class="fa fa-plus me-1"></i> Fotoğraf Seç
                                        <input type="file" id="images" name="images[]" accept="image/*"
                                               multiple class="d-none" onchange="handleImages(this)">
                                    </label>
                                    <div class="form-text text-muted mt-1">JPG, JPEG, PNG veya WEBP. Birden fazla fotoğraf seçebilirsiniz.</div>
                                    @error('images.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-round w-100">
                                <i class="fa fa-plus me-1"></i> Ürün Oluştur
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    // ─── Komisyon hesabı ──────────────────────────────────────────────────────
    const COMMISSION_RATE = {{ App\Models\Product::COMMISSION_RATE }};

    function calcCommission() {
        const val = parseFloat(document.getElementById('expected_price').value) || 0;
        const commission = Math.round(val * (1 + COMMISSION_RATE) * 100) / 100;
        document.getElementById('commission_price_display').value =
            val > 0 ? new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(commission) + ' ₺' : '';
    }

    document.addEventListener('DOMContentLoaded', calcCommission);

    // ─── Fotoğraf ─────────────────────────────────────────────────────────────
    let selectedFiles = [];

    function syncFilesToInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        document.getElementById('images').files = dt.files;
    }

    function handleImages(input) {
        Array.from(input.files).forEach(file => selectedFiles.push(file));
        syncFilesToInput();
        renderPreviews();
    }

    function removePreview(index) {
        selectedFiles.splice(index, 1);
        syncFilesToInput();
        renderPreviews();
    }

    function renderPreviews() {
        const container = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const isCover = index === 0;
                const label   = isCover ? 'Kapak' : (index + 1) + '.';
                const bgColor = isCover ? '#007bff' : 'rgba(0,0,0,0.55)';
                const border  = isCover ? '2px solid #007bff' : '1px solid #dee2e6';
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative;display:inline-block;text-align:center;';
                wrapper.innerHTML = `
                    <img src="${e.target.result}" style="width:90px;height:90px;object-fit:cover;border-radius:6px;border:${border};">
                    <span style="position:absolute;top:4px;left:4px;background:${bgColor};color:#fff;font-size:11px;font-weight:600;border-radius:4px;padding:1px 5px;line-height:1.6;">${label}</span>
                    <button type="button" onclick="removePreview(${index})"
                        style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#dc3545;color:#fff;border:none;font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;">
                        &times;
                    </button>`;
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    // ─── Varyant ──────────────────────────────────────────────────────────────
    function toggleVariants(enabled) {
        const stockInput    = document.getElementById('stock');
        const expectedInput = document.getElementById('expected_price');
        document.getElementById('simpleStockSection').style.display = enabled ? 'none' : '';
        document.getElementById('variantSection').style.display     = enabled ? '' : 'none';
        document.getElementById('pricingSection').style.display     = enabled ? 'none' : '';
        stockInput.disabled    = enabled;
        stockInput.required    = !enabled;
        expectedInput.required = !enabled;
        if (enabled && document.querySelectorAll('.attr-row').length === 0) {
            addAttributeRow();
        }
    }

    function addAttributeRow(name = '', values = '') {
        const container = document.getElementById('attributeRows');
        const idx       = container.children.length;
        const row       = document.createElement('div');
        row.className   = 'attr-row d-flex gap-2 mb-2 align-items-center';
        row.innerHTML   = `
            <input type="text" class="form-control attr-name" placeholder="Özellik adı (örn: Renk)"
                   value="${name}" style="max-width:200px;" oninput="rebuildVariants()">
            <input type="text" class="form-control attr-values" placeholder="Değerler (virgülle ayır: Kırmızı, Mavi)"
                   value="${values}" oninput="rebuildVariants()">
            <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="removeAttributeRow(this)">
                <i class="fa fa-trash"></i>
            </button>`;
        container.appendChild(row);
        rebuildVariants();
    }

    function removeAttributeRow(btn) {
        btn.closest('.attr-row').remove();
        rebuildVariants();
    }

    function getAttributes() {
        const rows = [];
        document.querySelectorAll('.attr-row').forEach(row => {
            const name   = row.querySelector('.attr-name').value.trim();
            const rawVal = row.querySelector('.attr-values').value;
            const values = rawVal.split(',').map(v => v.trim()).filter(v => v !== '');
            if (name && values.length > 0) {
                rows.push({ name, values });
            }
        });
        return rows;
    }

    function cartesian(arrays) {
        return arrays.reduce((acc, arr) => {
            return acc.flatMap(existing => arr.map(item => [...existing, item]));
        }, [[]]);
    }

    // Mevcut variant stok/fiyat değerlerini sakla (özellikler değiştirilince kaybetme)
    let savedVariantData = {};

    function fmtTR(n) {
        return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(n) + ' ₺';
    }

    function calcVariantCommission(input) {
        const row      = input.closest('tr');
        const expected = parseFloat(input.value) || 0;
        const commInput = row.querySelector('.v-commission');
        if (expected > 0) {
            const comm = Math.round(expected * (1 + COMMISSION_RATE) * 100) / 100;
            commInput.value            = fmtTR(comm);
            commInput.dataset.rawValue = comm;
        } else {
            commInput.value            = '';
            commInput.dataset.rawValue = '';
        }
    }

    function rebuildVariants() {
        const attrs = getAttributes();
        if (attrs.length === 0) {
            document.getElementById('variantTableCard').style.display = 'none';
            document.getElementById('variantCount').textContent = '0 varyant';
            return;
        }

        // Mevcut inputları kaydet
        document.querySelectorAll('#variantTableBody tr').forEach(tr => {
            const key = tr.dataset.key;
            savedVariantData[key] = {
                stock:          tr.querySelector('.v-stock')?.value ?? '',
                cost_price:     tr.querySelector('.v-cost')?.value ?? '',
                expected_price: tr.querySelector('.v-expected')?.value ?? '',
            };
        });

        const combinations = cartesian(attrs.map(a => a.values));
        document.getElementById('variantTableCard').style.display = '';
        document.getElementById('variantCount').textContent = combinations.length + ' varyant';

        const tbody = document.getElementById('variantTableBody');
        tbody.innerHTML = '';

        combinations.forEach(combo => {
            const label = combo.join(' / ');
            const key   = combo.join('|');
            const saved = savedVariantData[key] || {};

            const expectedVal = saved.expected_price ?? '';
            let commDisplay   = '';
            if (expectedVal !== '' && parseFloat(expectedVal) > 0) {
                const comm = Math.round(parseFloat(expectedVal) * (1 + COMMISSION_RATE) * 100) / 100;
                commDisplay = fmtTR(comm);
            }

            const tr    = document.createElement('tr');
            tr.dataset.key = key;
            tr.innerHTML = `
                <td class="ps-3 align-middle fw-semibold">${label}</td>
                <td>
                    <input type="number" class="form-control form-control-sm v-cost"
                           step="0.01" min="0" placeholder="0.00"
                           value="${saved.cost_price ?? ''}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm v-expected"
                           step="0.01" min="0" placeholder="0.00" required
                           value="${expectedVal}"
                           oninput="calcVariantCommission(this)">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm v-commission bg-light fw-semibold"
                           readonly value="${commDisplay}"
                           data-raw-value="${commDisplay !== '' ? Math.round(parseFloat(expectedVal) * (1 + COMMISSION_RATE) * 100) / 100 : ''}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm v-stock"
                           min="0" placeholder="0"
                           value="${saved.stock ?? 0}">
                </td>`;
            tbody.appendChild(tr);
        });
    }

    function prepareVariantData() {
        if (!document.getElementById('has_variants').checked) return;

        const attrs    = getAttributes();
        const variants = [];

        document.querySelectorAll('#variantTableBody tr').forEach(tr => {
            const key      = tr.dataset.key;
            const parts    = key.split('|');
            const combo    = {};
            attrs.forEach((a, i) => { combo[a.name] = parts[i]; });
            const costPrice     = tr.querySelector('.v-cost').value.trim();
            const expectedPrice = tr.querySelector('.v-expected').value.trim();
            variants.push({
                combination:    combo,
                label:          parts.join(' / '),
                stock:          parseInt(tr.querySelector('.v-stock').value) || 0,
                cost_price:     costPrice     !== '' ? parseFloat(costPrice)     : null,
                expected_price: expectedPrice !== '' ? parseFloat(expectedPrice) : null,
            });
        });

        document.getElementById('attributesJson').value = JSON.stringify(attrs);
        document.getElementById('variantsJson').value   = JSON.stringify(variants);
    }

    // Form submit event (onclick yerine daha güvenilir)
    document.getElementById('productForm').addEventListener('submit', function () {
        if (document.getElementById('has_variants').checked) {
            prepareVariantData();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('has_variants').checked) toggleVariants(true);
    });
    </script>
    @endpush

@endsection
