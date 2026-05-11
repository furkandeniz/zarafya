@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Kupon Düzenle</h3>
                <h6 class="op-7 mb-2">{{ $coupon->code }}</h6>
            </div>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-round">
                <i class="fa fa-arrow-left me-1"></i> Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-7">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Kullanım Özeti --}}
                <div class="card mb-3">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-4 border-end">
                                <div class="fw-bold fs-5">{{ $coupon->used_count }}</div>
                                <div class="text-muted small">Kullanım</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="fw-bold fs-5">{{ $coupon->max_uses ?? '∞' }}</div>
                                <div class="text-muted small">Limit</div>
                            </div>
                            <div class="col-4">
                                @php $status = $coupon->status; $statusMap = ['active'=>['Aktif','text-success'],'passive'=>['Pasif','text-secondary'],'expired'=>['Süresi Doldu','text-danger'],'exhausted'=>['Limit Doldu','text-warning']]; @endphp
                                <div class="fw-bold fs-5 {{ $statusMap[$status][1] }}">{{ $statusMap[$status][0] }}</div>
                                <div class="text-muted small">Durum</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kupon Bilgilerini Güncelle</h4>
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

                        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Mağaza --}}
                            <div class="form-group mb-3">
                                <label for="store_id">Mağaza</label>
                                <select id="store_id" name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                    <option value="">— Tüm Mağazalar (Genel) —</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ old('store_id', $coupon->store_id) == $store->id ? 'selected' : '' }}>
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
                                <input type="text" id="code" name="code"
                                       class="form-control text-uppercase @error('code') is-invalid @enderror"
                                       value="{{ old('code', $coupon->code) }}"
                                       style="text-transform:uppercase"
                                       required autofocus>
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- İndirim Tipi ve Değeri --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="discount_type">İndirim Tipi <span class="text-danger">*</span></label>
                                        <select id="discount_type" name="discount_type"
                                                class="form-control @error('discount_type') is-invalid @enderror" required>
                                            <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Yüzde (%)</option>
                                            <option value="fixed"      {{ old('discount_type', $coupon->discount_type) === 'fixed'      ? 'selected' : '' }}>Sabit Tutar (₺)</option>
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
                                               value="{{ old('discount_value', $coupon->discount_value) }}"
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
                                               value="{{ old('min_order_amount', $coupon->min_order_amount) }}"
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
                                               value="{{ old('max_uses', $coupon->max_uses) }}"
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
                                               value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
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
                                                   {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Aktif</label>
                                        </div>
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
        document.getElementById('code').addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    </script>
    @endpush

@endsection
