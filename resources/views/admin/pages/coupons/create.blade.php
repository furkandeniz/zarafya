@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Yeni Kupon Ekle</h3>
                <h6 class="op-7 mb-2">Yeni bir indirim kuponu oluştur</h6>
            </div>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kupon Bilgileri</h4>
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

                        <form action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf

                            {{-- Mağaza --}}
                            <div class="form-group mb-3">
                                <label for="store_id">Mağaza</label>
                                <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                    <option value="">— Tüm Mağazalar (Genel) —</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('store_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text text-muted">Boş bırakılırsa tüm mağazalarda geçerlidir.</div>
                            </div>

                            {{-- Kupon Kodu --}}
                            <div class="form-group mb-3">
                                <label for="code">Kupon Kodu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="code" name="code"
                                           class="form-control text-uppercase @error('code') is-invalid @enderror"
                                           value="{{ old('code') }}"
                                           placeholder="YAZA20"
                                           style="text-transform:uppercase"
                                           required autofocus>
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                        <i class="fa fa-random"></i> Oluştur
                                    </button>
                                </div>
                                @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                <div class="form-text text-muted">Büyük harfe otomatik dönüştürülür.</div>
                            </div>

                            {{-- İndirim Tipi ve Değeri --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="discount_type">İndirim Tipi <span class="text-danger">*</span></label>
                                        <select id="discount_type" name="discount_type"
                                                class="form-control @error('discount_type') is-invalid @enderror" required>
                                            <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Yüzde (%)</option>
                                            <option value="fixed"      {{ old('discount_type') === 'fixed'      ? 'selected' : '' }}>Sabit Tutar (₺)</option>
                                        </select>
                                        @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="discount_value">İndirim Değeri <span class="text-danger">*</span></label>
                                        <input type="number" id="discount_value" name="discount_value"
                                               step="0.01" min="0.01"
                                               class="form-control @error('discount_value') is-invalid @enderror"
                                               value="{{ old('discount_value') }}"
                                               placeholder="10"
                                               required>
                                        @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Min. Sipariş ve Max Kullanım --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="min_order_amount">Min. Sipariş Tutarı (₺)</label>
                                        <input type="number" id="min_order_amount" name="min_order_amount"
                                               step="0.01" min="0"
                                               class="form-control @error('min_order_amount') is-invalid @enderror"
                                               value="{{ old('min_order_amount') }}"
                                               placeholder="Boş = koşulsuz">
                                        @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="max_uses">Maks. Kullanım Sayısı</label>
                                        <input type="number" id="max_uses" name="max_uses"
                                               min="1"
                                               class="form-control @error('max_uses') is-invalid @enderror"
                                               value="{{ old('max_uses') }}"
                                               placeholder="Boş = sınırsız">
                                        @error('max_uses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Son Geçerlilik ve Durum --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="expires_at">Son Geçerlilik Tarihi</label>
                                        <input type="date" id="expires_at" name="expires_at"
                                               class="form-control @error('expires_at') is-invalid @enderror"
                                               value="{{ old('expires_at') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        @error('expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="form-text text-muted">Boş bırakılırsa süresiz geçerlidir.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Durum</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                   name="is_active" value="1"
                                                   {{ old('is_active', '1') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-round w-100">
                                <i class="fa fa-plus me-1"></i> Kuponu Oluştur
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.getElementById('code').addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });

        function generateCode() {
            const chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code     = '';
            for (let i = 0; i < 8; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('code').value = code;
        }
    </script>
    @endpush

@endsection
