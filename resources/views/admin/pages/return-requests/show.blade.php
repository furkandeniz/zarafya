@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">İade Talebi #{{ $returnRequest->id }}</h3>
            <h6 class="op-7 mb-2">
                <a href="{{ route('admin.return-requests.index') }}" class="text-muted">İade Talepleri</a>
                &rsaquo; #{{ $returnRequest->id }}
            </h6>
        </div>
        <a href="{{ route('admin.return-requests.index') }}" class="btn btn-outline-secondary btn-round">
            <i class="fas fa-arrow-left me-1"></i> Geri
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php $s = \App\Models\ReturnRequest::STATUSES[$returnRequest->status] ?? ['label' => $returnRequest->status, 'class' => 'bg-secondary']; @endphp

    <div class="row g-3">

        {{-- Sol: İade Detayları --}}
        <div class="col-lg-8">

            {{-- İade Edilecek Ürünler --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">İade Edilecek Ürünler</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Ürün</th>
                                <th class="text-center">İade Adedi</th>
                                <th class="text-center">Sipariş Adedi</th>
                                <th class="text-end pe-3">Birim Fiyat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returnRequest->items as $ri)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold small">{{ $ri->orderItem->product_name ?? '—' }}</div>
                                    @if ($ri->orderItem?->product)
                                        <a href="{{ route('admin.products.edit', $ri->orderItem->product) }}"
                                           class="text-muted" style="font-size:11px;">Ürüne git →</a>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $ri->quantity }}</td>
                                <td class="text-center text-muted small">{{ $ri->orderItem->quantity ?? '—' }}</td>
                                <td class="text-end pe-3 small">
                                    {{ $ri->orderItem ? number_format($ri->orderItem->price, 2, ',', '.') . ' ₺' : '—' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Müşteri Notu --}}
            @if ($returnRequest->note)
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Müşteri Açıklaması</h4>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $returnRequest->note }}</p>
                </div>
            </div>
            @endif

            {{-- Admin Yanıtı (çözümlendiyse) --}}
            @if ($returnRequest->status !== 'pending' && $returnRequest->admin_note)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Admin Yanıtı</h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $returnRequest->admin_note }}</p>
                    <div class="text-muted small mt-2">
                        {{ $returnRequest->resolved_at?->format('d.m.Y H:i') }}
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Sağ: Özet ve Karar --}}
        <div class="col-lg-4">

            {{-- Durum --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-semibold">Durum</span>
                        <span class="badge {{ $s['class'] }} fs-6 px-3">{{ $s['label'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Talep Tarihi</span>
                        <span>{{ $returnRequest->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if ($returnRequest->resolved_at)
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Çözüm Tarihi</span>
                        <span>{{ $returnRequest->resolved_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Müşteri --}}
            <div class="card mb-3">
                <div class="card-header"><h4 class="card-title mb-0">Müşteri</h4></div>
                <div class="card-body">
                    @if ($returnRequest->user)
                        <div class="fw-semibold small">{{ $returnRequest->user->name }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $returnRequest->user->email }}</div>
                    @else
                        <span class="text-muted small">Kullanıcı silinmiş</span>
                    @endif
                </div>
            </div>

            {{-- İlgili Sipariş --}}
            <div class="card mb-3">
                <div class="card-header"><h4 class="card-title mb-0">İlgili Sipariş</h4></div>
                <div class="card-body">
                    <a href="{{ route('admin.orders.show', $returnRequest->order_id) }}"
                       class="btn btn-sm btn-outline-secondary w-100">
                        Sipariş #{{ $returnRequest->order_id }} →
                    </a>
                </div>
            </div>

            {{-- İade Nedeni --}}
            <div class="card mb-3">
                <div class="card-header"><h4 class="card-title mb-0">İade Nedeni</h4></div>
                <div class="card-body">
                    <span class="small">
                        {{ \App\Models\ReturnRequest::REASONS[$returnRequest->reason] ?? $returnRequest->reason }}
                    </span>
                </div>
            </div>

            {{-- Karar Paneli --}}
            @if ($returnRequest->status === 'pending')
            <div class="card border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h4 class="card-title mb-0">Karar Ver</h4>
                </div>
                <div class="card-body">

                    {{-- Onayla --}}
                    <form action="{{ route('admin.return-requests.approve', $returnRequest) }}" method="POST" class="mb-3">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Onay Notu <span class="text-muted fw-normal">(isteğe bağlı)</span></label>
                            <textarea name="admin_note" rows="3" class="form-control form-control-sm"
                                      placeholder="Müşteriye iletilecek not..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100"
                                onclick="return confirm('İade talebini onaylamak istediğinize emin misiniz?')">
                            <i class="fas fa-check me-1"></i> Onayla
                        </button>
                    </form>

                    <hr>

                    {{-- Reddet --}}
                    <form action="{{ route('admin.return-requests.reject', $returnRequest) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Ret Nedeni <span class="text-danger">*</span></label>
                            <textarea name="admin_note" rows="3" class="form-control form-control-sm"
                                      placeholder="Neden reddedildiğini açıklayın..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('İade talebini reddetmek istediğinize emin misiniz?')">
                            <i class="fas fa-times me-1"></i> Reddet
                        </button>
                    </form>

                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
