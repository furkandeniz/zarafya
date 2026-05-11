@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <h6 class="op-7 mb-2">Genel bakış</h6>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        @if (auth()->user()->isAdmin())
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-big text-center me-3">
                            <i class="fas fa-users text-warning" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Kullanıcılar</p>
                            <h4 class="card-title mb-0 fw-bold">{{ number_format($stats['users']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-big text-center me-3">
                            <i class="fas fa-store text-primary" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Aktif Mağazalar</p>
                            <h4 class="card-title mb-0 fw-bold">{{ number_format($stats['stores']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-big text-center me-3">
                            <i class="fas fa-box text-success" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Ürünler</p>
                            <h4 class="card-title mb-0 fw-bold">{{ number_format($stats['products']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-big text-center me-3">
                            <i class="fas fa-shopping-cart text-info" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Siparişler</p>
                            <h4 class="card-title mb-0 fw-bold">{{ number_format($stats['orders']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-big text-center me-3">
                            <i class="fas fa-lira-sign text-danger" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Toplam Gelir</p>
                            <h4 class="card-title mb-0 fw-bold">{{ number_format($stats['revenue'], 0, ',', '.') }} ₺</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Aylık Sipariş & Gelir</h4>
                    <p class="card-category text-muted small">Son 6 ay</p>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" height="110"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Kargo Durumu Dağılımı</h4>
                    <p class="card-category text-muted small">Tüm siparişler</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES --}}
    <div class="row g-3">

        {{-- Aktif Mağazalar --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Aktif Mağazalar</h4>
                        <p class="card-category text-muted small">{{ $stores->total() }} mağaza</p>
                    </div>
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-outline-primary btn-round">Tümü</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Mağaza</th>
                                    <th class="text-center">Ürün</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stores as $store)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($store->logo)
                                                    <img src="{{ asset('storage/' . $store->logo) }}"
                                                         style="width:34px;height:34px;object-fit:cover;border-radius:50%;border:1px solid #eee;">
                                                @else
                                                    <div style="width:34px;height:34px;border-radius:50%;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-store text-muted" style="font-size:13px;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold small">{{ $store->name }}</div>
                                                    @if($store->email)
                                                        <div class="text-muted" style="font-size:11px;">{{ $store->email }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $store->products_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted py-3">Henüz mağaza yok.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($stores->hasPages())
                        <div class="d-flex justify-content-center py-2">
                            {{ $stores->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Son Siparişler --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Son Siparişler</h4>
                        <p class="card-category text-muted small">{{ $orders->total() }} sipariş</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary btn-round">Tümü</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Ürün</th>
                                    <th>Mağaza</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    @php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                                    <tr>
                                        <td class="ps-3 text-muted small">#{{ $order->id }}</td>
                                        <td>
                                            @if ($order->firstItem)
                                                <div class="fw-semibold small">{{ Str::limit($order->firstItem->product_name, 28) }}</div>
                                                @if ($order->items_count > 1)
                                                    <div class="text-muted" style="font-size:11px;">+{{ $order->items_count - 1 }} ürün daha</div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ $order->store->name ?? '—' }}</td>
                                        <td class="small fw-semibold">{{ number_format($order->total_price, 2, ',', '.') }} ₺</td>
                                        <td><span class="badge {{ $status['class'] }}" style="font-size:10px;">{{ $status['label'] }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">Henüz sipariş yok.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($orders->hasPages())
                        <div class="d-flex justify-content-center py-2">
                            {{ $orders->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- MESAJLAR (sadece admin) --}}
    @if (auth()->user()->isAdmin())
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">
                            Son Mesajlar
                            @if ($unreadCount > 0)
                                <span class="badge bg-danger ms-1" style="font-size:11px;">{{ $unreadCount }} yeni</span>
                            @endif
                        </h4>
                        <p class="card-category text-muted small">{{ $messages->total() }} mesaj</p>
                    </div>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary btn-round">Tümü</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Gönderen</th>
                                    <th>E-posta</th>
                                    <th>Mesaj</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th class="text-end pe-3">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $msg)
                                    <tr class="{{ $msg->read_at ? '' : 'fw-semibold' }}">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if (!$msg->read_at)
                                                    <span class="badge bg-danger"
                                                          style="width:8px;height:8px;border-radius:50%;padding:0;flex-shrink:0;"></span>
                                                @else
                                                    <span style="width:8px;flex-shrink:0;"></span>
                                                @endif
                                                <span class="small">{{ $msg->name }}</span>
                                            </div>
                                        </td>
                                        <td class="small text-muted">{{ $msg->email }}</td>
                                        <td class="small text-muted" style="max-width:300px;">
                                            {{ Str::limit($msg->message, 70) }}
                                        </td>
                                        <td>
                                            @if ($msg->status === 'open')
                                                <span class="badge bg-success" style="font-size:10px;">Açık</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size:10px;">Kapalı</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.contacts.show', $msg) }}"
                                               class="btn btn-sm btn-outline-primary btn-round">
                                                <i class="fas fa-eye"></i> Detay
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Henüz mesaj yok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($messages->hasPages())
                        <div class="d-flex justify-content-center py-2">
                            {{ $messages->onEachSide(1)->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @endif {{-- end isAdmin --}}

</div>
@endsection

@push('scripts')
<script>
    // Sipariş & Gelir çizgi grafiği
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    type: 'bar',
                    label: 'Sipariş Sayısı',
                    data: {!! json_encode($chartOrders) !!},
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    borderColor: 'rgba(59,130,246,0.8)',
                    borderWidth: 2,
                    yAxisID: 'y',
                },
                {
                    type: 'line',
                    label: 'Gelir (₺)',
                    data: {!! json_encode($chartRevenue) !!},
                    borderColor: 'rgba(16,185,129,0.9)',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                y:  { type: 'linear', position: 'left',  beginAtZero: true, ticks: { stepSize: 1 } },
                y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } }
            }
        }
    });

    // Kargo durumu donut grafiği
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Alındı', 'Hazırlanıyor', 'Kargoda', 'Teslim', 'İade', 'İptal'],
            datasets: [{
                data: {!! json_encode(
                    collect(['pending','processing','shipped','delivered','returned','cancelled'])
                        ->map(function($s) {
                            $q = \App\Models\Order::where('shipping_status', $s);
                            if (auth()->user()->isSeller()) {
                                $q->where('store_id', auth()->user()->store_id);
                            }
                            return $q->count();
                        })
                        ->values()
                ) !!},
                backgroundColor: ['#6c757d','#0dcaf0','#0d6efd','#198754','#ffc107','#dc3545'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
</script>
@endpush
