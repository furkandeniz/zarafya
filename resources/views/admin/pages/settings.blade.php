@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="pt-2 pb-4">
        <h3 class="fw-bold mb-1">Site Ayarları</h3>
        <p class="text-muted small mb-0">Genel platform ayarlarını buradan yönetebilirsiniz.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-percent me-2" style="color:#e83e8c;"></i>Komisyon Ayarı
                    </h4>
                </div>
                <div class="card-body">

                    <p class="text-muted small mb-4">
                        Platforma yapılan her siparişten kesilecek komisyon oranını belirleyin.
                        Bu oran bilanço sayfasındaki tüm komisyon hesaplamalarına yansıyacaktır.
                    </p>

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Komisyon Oranı (%)</label>
                            <div class="input-group" style="max-width:220px;">
                                <input type="number"
                                       name="commission_rate"
                                       class="form-control @error('commission_rate') is-invalid @enderror"
                                       value="{{ old('commission_rate', $commissionRate) }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="{{ \App\Models\Setting::DEFAULTS['commission_rate'] }}">
                                <span class="input-group-text">%</span>
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">0 ile 100 arasında bir değer girin. Ondalık desteklenir (ör: 12.5)</div>
                        </div>

                        {{-- Önizleme --}}
                        <div class="p-3 rounded mb-4" style="background:#f8f9fa;border:1px solid #e9ecef;">
                            <div class="small text-muted mb-2 fw-semibold">Örnek Hesaplama</div>
                            <div class="d-flex justify-content-between small">
                                <span>1.000 ₺ sipariş tutarı</span>
                                <span id="previewAmount" class="fw-bold" style="color:#e83e8c;">
                                    {{ number_format(1000 * $commissionRate / 100, 2, ',', '.') }} ₺ komisyon
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Kaydet
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.querySelector('input[name="commission_rate"]').addEventListener('input', function () {
    var rate = parseFloat(this.value) || 0;
    var amount = (1000 * rate / 100).toFixed(2).replace('.', ',');
    document.getElementById('previewAmount').textContent = amount + ' ₺ komisyon';
});
</script>
@endpush
