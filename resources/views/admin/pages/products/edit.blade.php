@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Ürün Düzenle</h3>
                <h6 class="op-7 mb-2">{{ $product->name }}</h6>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ürün Bilgilerini Güncelle</h4>
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

                        <form id="productUpdateForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="name">Ürün Adı <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}" required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="store_id">Mağaza</label>
                                        <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                            <option value="">— Seçiniz —</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}"
                                                    {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>
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
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="price">Baz Fiyat (₺) <span class="text-danger">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price) }}" required>
                                <div class="form-text text-muted">Varyant bazlı fiyat tanımlanmadığında bu fiyat kullanılır.</div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Varyant Toggle --}}
                            <div class="card border mb-3">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants"
                                                   value="1"
                                                   {{ ($existingVariants->count() > 0 || old('has_variants')) ? 'checked' : '' }}
                                                   onchange="toggleVariants(this.checked)">
                                            <label class="form-check-label fw-semibold" for="has_variants">
                                                Bu ürünün varyantları var (renk, beden, boyut vb.)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Basit stok --}}
                            <div id="simpleStockSection" class="form-group mb-4">
                                <label for="stock">Stok <span class="text-danger">*</span></label>
                                <input type="number" id="stock" name="stock" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', $product->stock) }}">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Varyant bölümü --}}
                            <div id="variantSection" style="display:none;" class="mb-4">

                                <input type="hidden" name="attributes_json" id="attributesJson">
                                <input type="hidden" name="variants_json"   id="variantsJson">

                                <div class="card border mb-3">
                                    <div class="card-header d-flex align-items-center justify-content-between py-2">
                                        <span class="fw-semibold">Özellikler</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAttributeRow()">
                                            <i class="fa fa-plus me-1"></i> Özellik Ekle
                                        </button>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="attributeRows"></div>
                                        <div class="form-text text-muted">
                                            Örnek: <strong>Renk</strong> → Kırmızı, Mavi, Yeşil &nbsp;|&nbsp;
                                            <strong>Beden</strong> → XS, S, M, L, XL
                                        </div>
                                    </div>
                                </div>

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
                                                        <th style="width:160px;">Fiyat (₺)</th>
                                                        <th style="width:130px;">Stok <span class="text-danger">*</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variantTableBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Mevcut fotoğraflar --}}
                            @if ($product->images->count())
                                <div class="form-group mb-3">
                                    <label>Mevcut Fotoğraflar</label>
                                    <div class="d-flex flex-wrap gap-3 p-3 border rounded">
                                        @foreach ($product->images as $i => $image)
                                            <div style="position:relative;display:inline-block;text-align:center;">
                                                <img src="{{ asset('storage/' . $image->image) }}"
                                                     style="width:90px;height:90px;object-fit:cover;border-radius:6px;border: {{ $i === 0 ? '2px solid #007bff' : '1px solid #dee2e6' }};">
                                                <span style="position:absolute;top:4px;left:4px;background:{{ $i === 0 ? '#007bff' : 'rgba(0,0,0,0.55)' }};color:#fff;font-size:11px;font-weight:600;border-radius:4px;padding:1px 5px;line-height:1.6;">
                                                    {{ $i === 0 ? 'Kapak' : ($i + 1) . '.' }}
                                                </span>
                                                {{-- Ana form içinde iç içe form olmaz; AJAX ile sil --}}
                                                <button type="button"
                                                        onclick="deleteImage('{{ route('admin.products.images.destroy', [$product->id, $image->id]) }}')"
                                                        style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;border-radius:50%;background:#dc3545;color:#fff;border:none;font-size:11px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;"
                                                        title="Fotoğrafı sil">
                                                    &times;
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text text-muted">Mavi çerçeveli fotoğraf ürün listesinde gösterilir.</div>
                                </div>
                            @endif

                            <div class="form-group mb-4">
                                <label>Yeni Fotoğraf Ekle</label>
                                <div class="border rounded p-3">
                                    <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mb-2"></div>
                                    <label for="images" class="btn btn-outline-secondary btn-sm mb-0">
                                        <i class="fa fa-plus me-1"></i> Fotoğraf Seç
                                        <input type="file" id="images" accept="image/*"
                                               multiple class="d-none" onchange="handleImages(this)">
                                    </label>
                                    <div class="form-text text-muted mt-1">JPG, JPEG, PNG veya WEBP. Maks. 2MB/fotoğraf.</div>
                                    @error('images.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-round w-100">
                                <i class="fa fa-save me-1"></i> Güncelle
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    // ─── Fotoğraf ─────────────────────────────────────────────────────────────
    let selectedFiles = [];

    function addHiddenInput(form, file) {
        const dt  = new DataTransfer();
        dt.items.add(file);
        const inp = document.createElement('input');
        inp.type  = 'file'; inp.name = 'images[]';
        inp.className = 'd-none js-photo-file';
        inp.files = dt.files;
        form.appendChild(inp);
    }

    function rebuildHiddenInputs() {
        const form = document.getElementById('productUpdateForm');
        form.querySelectorAll('.js-photo-file').forEach(el => el.remove());
        selectedFiles.forEach(f => addHiddenInput(form, f));
    }

    function handleImages(input) {
        const form = document.getElementById('productUpdateForm');
        Array.from(input.files).forEach(file => {
            selectedFiles.push(file);
            addHiddenInput(form, file);
        });
        renderPreviews();
        input.value = '';
    }

    function removePreview(index) {
        selectedFiles.splice(index, 1);
        rebuildHiddenInputs();
        renderPreviews();
    }

    function renderPreviews() {
        const existingCount = {{ $product->images->count() }};
        const container     = document.getElementById('imagePreviewContainer');
        container.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const num     = existingCount + index + 1;
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative;display:inline-block;text-align:center;';
                wrapper.innerHTML = `
                    <img src="${e.target.result}" style="width:90px;height:90px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                    <span style="position:absolute;top:4px;left:4px;background:rgba(0,0,0,0.55);color:#fff;font-size:11px;font-weight:600;border-radius:4px;padding:1px 5px;line-height:1.6;">${num}.</span>
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
    const existingAttributes = @json($existingAttributes);
    const existingVariants   = @json($existingVariants);

    const existingVariantMap = {};
    existingVariants.forEach(v => {
        existingVariantMap[v.label] = { stock: v.stock, price: v.price };
    });

    function toggleVariants(enabled) {
        const stockInput = document.getElementById('stock');
        document.getElementById('simpleStockSection').style.display = enabled ? 'none' : '';
        document.getElementById('variantSection').style.display     = enabled ? '' : 'none';
        // disabled=true: hem validasyondan muaf hem de form'a gönderilmez
        stockInput.disabled = enabled;
        stockInput.required = !enabled;
        if (enabled && document.querySelectorAll('.attr-row').length === 0) {
            if (existingAttributes.length > 0) {
                existingAttributes.forEach(a => addAttributeRow(a.name, a.values.join(', ')));
            } else {
                addAttributeRow();
            }
        }
    }

    function addAttributeRow(name = '', values = '') {
        const container = document.getElementById('attributeRows');
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
            const values = row.querySelector('.attr-values').value
                              .split(',').map(v => v.trim()).filter(v => v !== '');
            if (name && values.length > 0) rows.push({ name, values });
        });
        return rows;
    }

    function cartesian(arrays) {
        return arrays.reduce((acc, arr) =>
            acc.flatMap(existing => arr.map(item => [...existing, item])), [[]]);
    }

    let savedVariantData = {};

    function rebuildVariants() {
        document.querySelectorAll('#variantTableBody tr').forEach(tr => {
            savedVariantData[tr.dataset.label] = {
                stock: tr.querySelector('.v-stock')?.value ?? '',
                price: tr.querySelector('.v-price')?.value ?? '',
            };
        });

        const attrs = getAttributes();
        if (attrs.length === 0) {
            document.getElementById('variantTableCard').style.display = 'none';
            document.getElementById('variantCount').textContent = '0 varyant';
            return;
        }

        const combinations = cartesian(attrs.map(a => a.values));
        document.getElementById('variantTableCard').style.display = '';
        document.getElementById('variantCount').textContent = combinations.length + ' varyant';

        const tbody = document.getElementById('variantTableBody');
        tbody.innerHTML = '';

        combinations.forEach(combo => {
            const label = combo.join(' / ');
            const saved = savedVariantData[label]
                       ?? (existingVariantMap[label]
                            ? { stock: existingVariantMap[label].stock, price: existingVariantMap[label].price ?? '' }
                            : {});
            const tr          = document.createElement('tr');
            tr.dataset.label  = label;
            // name'siz inputlar: form'a gönderilmez, sadece JS tarafından okunur
            tr.innerHTML = `
                <td class="ps-3 align-middle">${label}</td>
                <td>
                    <input type="number" class="form-control form-control-sm v-price"
                           step="0.01" min="0" placeholder="Baz fiyat"
                           value="${saved.price != null ? saved.price : ''}">
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
        const attrs    = getAttributes();
        const variants = [];

        document.querySelectorAll('#variantTableBody tr').forEach(tr => {
            const label = tr.dataset.label;
            const parts = label.split(' / ');
            const combo = {};
            attrs.forEach((a, i) => { combo[a.name] = parts[i]; });
            const price = tr.querySelector('.v-price').value.trim();
            variants.push({
                combination: combo,
                label,
                stock: parseInt(tr.querySelector('.v-stock').value) || 0,
                price: price !== '' ? parseFloat(price) : null,
            });
        });

        document.getElementById('attributesJson').value = JSON.stringify(attrs);
        document.getElementById('variantsJson').value   = JSON.stringify(variants);
    }

    // ─── Form submit event (onclick yerine daha güvenilir) ────────────────────
    document.getElementById('productUpdateForm').addEventListener('submit', function (e) {
        if (document.getElementById('has_variants').checked) {
            prepareVariantData();
        }
    });

    // ─── AJAX resim silme (iç içe form olmadan) ───────────────────────────────
    function deleteImage(url) {
        if (!confirm('Bu fotoğrafı silmek istediğinizden emin misiniz?')) return;
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/x-www-form-urlencoded' },
            body: '_method=DELETE',
        }).then(r => {
            if (r.ok || r.redirected) location.reload();
            else alert('Fotoğraf silinemedi.');
        }).catch(() => alert('Bağlantı hatası.'));
    }

    // Sayfa yüklenince mevcut varyantları yükle
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('has_variants').checked) toggleVariants(true);
    });
    </script>
    @endpush

@endsection
