@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Kuponlar</h3>
                <h6 class="op-7 mb-2">Tüm indirim kuponları</h6>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-round">
                <i class="fa fa-plus me-1"></i> Kupon Ekle
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Kupon Listesi</h4>
                            <span class="badge bg-primary ms-2">{{ $coupons->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kod</th>
                                        <th>Mağaza</th>
                                        <th>İndirim</th>
                                        <th>Min. Sipariş</th>
                                        <th>Kullanım</th>
                                        <th>Son Geçerlilik</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($coupons as $coupon)
                                        @php
                                            $status = $coupon->status;
                                            $statusMap = [
                                                'active'    => ['label' => 'Aktif',      'class' => 'bg-success'],
                                                'passive'   => ['label' => 'Pasif',      'class' => 'bg-secondary'],
                                                'expired'   => ['label' => 'Süresi Doldu', 'class' => 'bg-danger'],
                                                'exhausted' => ['label' => 'Limit Doldu', 'class' => 'bg-warning text-dark'],
                                            ];
                                        @endphp
                                        <tr>
                                            <td>{{ $coupon->id }}</td>
                                            <td><code class="fw-bold">{{ $coupon->code }}</code></td>
                                            <td>{{ $coupon->store->name ?? '—' }}</td>
                                            <td>
                                                @if ($coupon->discount_type === 'percentage')
                                                    <span class="badge bg-info text-dark">%{{ number_format($coupon->discount_value, 0) }}</span>
                                                @else
                                                    <span class="badge bg-info text-dark">{{ number_format($coupon->discount_value, 2, ',', '.') }} ₺</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $coupon->min_order_amount
                                                    ? number_format($coupon->min_order_amount, 2, ',', '.') . ' ₺'
                                                    : '—' }}
                                            </td>
                                            <td>
                                                {{ $coupon->used_count }}
                                                @if ($coupon->max_uses)
                                                    / {{ $coupon->max_uses }}
                                                    <div class="progress mt-1" style="height:4px;width:70px;">
                                                        <div class="progress-bar" style="width:{{ min(100, ($coupon->used_count / $coupon->max_uses) * 100) }}%"></div>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">/ sınırsız</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($coupon->expires_at)
                                                    <span class="{{ $coupon->isExpired() ? 'text-danger' : '' }}">
                                                        {{ $coupon->expires_at->format('d.m.Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $statusMap[$status]['class'] }}">
                                                    {{ $statusMap[$status]['label'] }}
                                                </span>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <div class="d-flex align-items-center justify-content-end gap-1">
                                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            data-coupon-id="{{ $coupon->id }}"
                                                            data-coupon-code="{{ $coupon->code }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Henüz kupon bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Silme Onay Modalı --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-triangle text-danger me-2"></i> Kuponu Sil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="mb-0">
                        <strong id="deleteCouponCode"></strong> kodlu kuponu silmek istediğinizden emin misiniz?
                        Bu işlem geri alınamaz.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-round" data-bs-dismiss="modal">İptal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-round">
                            <i class="fa fa-trash me-1"></i> Evet, Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
            const btn       = e.relatedTarget;
            document.getElementById('deleteCouponCode').textContent = btn.getAttribute('data-coupon-code');
            document.getElementById('deleteForm').action = '/admin/coupons/' + btn.getAttribute('data-coupon-id');
        });
    </script>
    @endpush

@endsection
