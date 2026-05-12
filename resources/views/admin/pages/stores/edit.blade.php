@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Mağaza Düzenle</h3>
                <h6 class="op-7 mb-2">{{ $store->name }}</h6>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="btn btn-success btn-round">
                    <i class="fas fa-external-link-alt me-1"></i> Mağaza Sayfasını Gör
                </a>
                @if (! auth()->user()->isSeller())
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary btn-round">
                        <i class="fa fa-arrow-left me-1"></i> Geri Dön
                    </a>
                @endif
            </div>
        </div>

        {{-- Paylaşılabilir Link --}}
        <div class="alert alert-info d-flex align-items-center gap-3 mb-4" style="border-radius:10px;">
            <i class="fas fa-link fa-lg flex-shrink-0"></i>
            <div class="flex-grow-1 min-width-0">
                <div class="fw-semibold mb-1">Müşterilerinizle paylaşabileceğiniz mağaza linki</div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <code id="storePublicUrl" style="font-size:13px;word-break:break-all;">{{ route('store.show', $store->slug) }}</code>
                    <button type="button" onclick="copyStoreUrl()" class="btn btn-sm btn-outline-secondary" style="white-space:nowrap;">
                        <i class="fas fa-copy me-1"></i> Kopyala
                    </button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mağaza Bilgilerini Güncelle</h4>
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

                        <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Logo Upload --}}
                            <div class="form-group mb-4 text-center">
                                <div class="mb-2">
                                    <div id="logoPreviewWrapper" style="display:inline-block;position:relative;">
                                        <img id="logoPreview"
                                             src="{{ $store->logo ? asset('storage/' . $store->logo) : asset('images/user.svg') }}"
                                             style="width:110px;height:110px;object-fit:cover;border-radius:50%;border:3px solid #eee;background:#f8f8f8;">
                                        <label for="logo"
                                               style="position:absolute;bottom:4px;right:4px;width:30px;height:30px;background:#007bff;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                               title="Logo Değiştir">
                                            <i class="fa fa-camera text-white" style="font-size:13px;"></i>
                                            <input type="file" id="logo" name="logo" accept="image/*" class="d-none"
                                                   onchange="previewLogo(this)">
                                        </label>
                                    </div>
                                </div>
                                @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                <div class="form-text text-muted">Yeni fotoğraf seçerseniz mevcut logo değiştirilir.</div>
                            </div>

                            {{-- Ad --}}
                            <div class="form-group mb-3">
                                <label for="name">Mağaza Adı <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $store->name) }}" required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Açıklama --}}
                            <div class="form-group mb-3">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $store->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- E-posta & Telefon --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email">E-posta</label>
                                        <input type="email" id="email" name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $store->email) }}">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone">Telefon</label>
                                        <input type="text" id="phone" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', $store->phone) }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Adres --}}
                            <div class="form-group mb-3">
                                <label for="address">Adres</label>
                                <input type="text" id="address" name="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $store->address) }}">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Durum --}}
                            <div class="form-group mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active"
                                           name="is_active" value="1"
                                           {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Mağaza Aktif</label>
                                </div>
                            </div>

                            {{-- Banka & Ödeme Bilgileri --}}
                            <hr class="my-4">
                            <h5 class="fw-semibold mb-3" style="color:#3b5d50;">
                                <i class="fas fa-university me-2"></i>Banka & Ödeme Bilgileri
                            </h5>
                            <p class="text-muted small mb-3">Para transferi için gerekli banka hesap bilgileri.</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="bank_name">Banka Adı</label>
                                        <input type="text" id="bank_name" name="bank_name"
                                               class="form-control @error('bank_name') is-invalid @enderror"
                                               value="{{ old('bank_name', $store->bank_name) }}" placeholder="Ziraat Bankası, Garanti vb.">
                                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="account_holder">Hesap Sahibi Adı Soyadı</label>
                                        <input type="text" id="account_holder" name="account_holder"
                                               class="form-control @error('account_holder') is-invalid @enderror"
                                               value="{{ old('account_holder', $store->account_holder) }}" placeholder="Ad Soyad">
                                        @error('account_holder') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="iban">IBAN</label>
                                <input type="text" id="iban" name="iban"
                                       class="form-control @error('iban') is-invalid @enderror"
                                       value="{{ old('iban', $store->iban) }}" placeholder="TR000000000000000000000000"
                                       maxlength="26" style="letter-spacing:1px;font-family:monospace;">
                                <div class="form-text text-muted">TR ile başlayan 26 haneli IBAN (boşluk olmadan).</div>
                                @error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="account_number">Hesap Numarası</label>
                                        <input type="text" id="account_number" name="account_number"
                                               class="form-control @error('account_number') is-invalid @enderror"
                                               value="{{ old('account_number', $store->account_number) }}" placeholder="Opsiyonel">
                                        @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mb-3">
                                        <label for="branch_name">Şube Adı</label>
                                        <input type="text" id="branch_name" name="branch_name"
                                               class="form-control @error('branch_name') is-invalid @enderror"
                                               value="{{ old('branch_name', $store->branch_name) }}" placeholder="Opsiyonel">
                                        @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-4">
                                        <label for="branch_code">Şube Kodu</label>
                                        <input type="text" id="branch_code" name="branch_code"
                                               class="form-control @error('branch_code') is-invalid @enderror"
                                               value="{{ old('branch_code', $store->branch_code) }}" placeholder="Opsiyonel">
                                        @error('branch_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
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
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('logoPreview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
    function copyStoreUrl() {
        var url = document.getElementById('storePublicUrl').textContent.trim();
        navigator.clipboard.writeText(url).then(function() {
            var btn = event.currentTarget;
            btn.innerHTML = '<i class="fas fa-check me-1"></i> Kopyalandı!';
            btn.classList.replace('btn-outline-secondary', 'btn-success');
            setTimeout(function() {
                btn.innerHTML = '<i class="fas fa-copy me-1"></i> Kopyala';
                btn.classList.replace('btn-success', 'btn-outline-secondary');
            }, 2000);
        });
    }
    </script>
    @endpush

@endsection
