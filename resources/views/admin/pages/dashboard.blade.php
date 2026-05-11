@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    {{-- KARŞILAMA BANNER --}}
    <div class="rounded-3 mb-4 px-4 py-4 d-flex align-items-center justify-content-between flex-wrap gap-3"
         style="background: linear-gradient(135deg, #3b5d50 0%, #314d43 100%); color:#fff;">
        <div>
            <h3 class="fw-bold mb-1" style="color:#fff;font-size:clamp(1.1rem,4vw,1.5rem);">
                Merhaba, {{ Str::words(auth()->user()->name, 1, '') }} 👋
            </h3>
            <p class="mb-0 opacity-75" style="font-size:13px;">
                {{ now()->locale('tr')->translatedFormat('l, d F Y') }} — Zarafya Yönetim Paneli
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm"
               style="background:#f9bf29;color:#2f2f2f;font-weight:600;border:none;">
                <i class="fas fa-plus me-1"></i> Ürün Ekle
            </a>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm"
               style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                <i class="fas fa-external-link-alt me-1"></i> Siteye Git
            </a>
        </div>
    </div>

    {{-- STAT KARTLARI --}}
    <div class="row g-2 g-md-3 mb-4">

        @if (auth()->user()->isAdmin())
        <div class="col-6 col-md-4 col-xl">
            <div class="card card-stats h-100" style="border-left: 4px solid #3b5d50;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#eff2f1;">
                            <i class="fas fa-users" style="color:#3b5d50;font-size:1.2rem;"></i>
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
            <div class="card card-stats h-100" style="border-left: 4px solid #f9bf29;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#fffbea;">
                            <i class="fas fa-store" style="color:#c9960a;font-size:1.2rem;"></i>
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
            <div class="card card-stats h-100" style="border-left: 4px solid #3b5d50;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#eff2f1;">
                            <i class="fas fa-box" style="color:#3b5d50;font-size:1.2rem;"></i>
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
            <div class="card card-stats h-100" style="border-left: 4px solid #0dcaf0;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#e8f8fd;">
                            <i class="fas fa-shopping-cart" style="color:#0dcaf0;font-size:1.2rem;"></i>
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
            <div class="card card-stats h-100" style="border-left: 4px solid #198754;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:#eaf4ee;">
                            <i class="fas fa-lira-sign" style="color:#198754;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <p class="card-category text-muted small mb-0">Toplam Gelir</p>
                            <h4 class="card-title mb-0 fw-bold" style="font-size:1rem;">
                                {{ number_format($stats['revenue'], 0, ',', '.') }} ₺
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- GRAFİKLER --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Aylık Sipariş & Gelir</h4>
                        <p class="card-category text-muted small mb-0">Son 6 ay</p>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Kargo Durumu</h4>
                    <p class="card-category text-muted small mb-0">Tüm siparişler</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLOLAR --}}
    <div class="row g-3">

        {{-- Aktif Mağazalar --}}
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Aktif Mağazalar</h4>
                        <p class="card-category text-muted small mb-0">{{ $stores->total() }} mağaza</p>
                    </div>
                    <a href="{{ route('admin.stores.index') }}"
                       class="btn btn-sm btn-round"
                       style="background:#3b5d50;color:#fff;font-size:12px;">Tümü</a>
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
                                                    <div style="width:34px;height:34px;border-radius:50%;background:#eff2f1;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-store" style="color:#3b5d50;font-size:13px;"></i>
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
                                            <span class="badge" style="background:#3b5d50;">{{ $store->products_count }}</span>
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
        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Son Siparişler</h4>
                        <p class="card-category text-muted small mb-0">{{ $orders->total() }} sipariş</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-sm btn-round"
                       style="background:#3b5d50;color:#fff;font-size:12px;">Tümü</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 d-none d-sm-table-cell">#</th>
                                    <th>Ürün</th>
                                    <th class="d-none d-md-table-cell">Mağaza</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    @php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                                    <tr>
                                        <td class="ps-3 text-muted small d-none d-sm-table-cell">#{{ $order->id }}</td>
                                        <td>
                                            @if ($order->firstItem)
                                                <div class="fw-semibold small">{{ Str::limit($order->firstItem->product_name, 22) }}</div>
                                                @if ($order->items_count > 1)
                                                    <div class="text-muted" style="font-size:11px;">+{{ $order->items_count - 1 }} ürün</div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="small d-none d-md-table-cell">{{ $order->store->name ?? '—' }}</td>
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
                                <span class="badge ms-1" style="background:#dc3545;font-size:11px;">{{ $unreadCount }} yeni</span>
                            @endif
                        </h4>
                        <p class="card-category text-muted small mb-0">{{ $messages->total() }} mesaj</p>
                    </div>
                    <a href="{{ route('admin.contacts.index') }}"
                       class="btn btn-sm btn-round"
                       style="background:#3b5d50;color:#fff;font-size:12px;">Tümü</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Gönderen</th>
                                    <th class="d-none d-sm-table-cell">E-posta</th>
                                    <th class="d-none d-md-table-cell">Mesaj</th>
                                    <th class="d-none d-sm-table-cell">Durum</th>
                                    <th class="d-none d-lg-table-cell">Tarih</th>
                                    <th class="text-end pe-3">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $msg)
                                    <tr class="{{ $msg->read_at ? '' : 'fw-semibold' }}">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if (!$msg->read_at)
                                                    <span style="width:8px;height:8px;border-radius:50%;background:#dc3545;flex-shrink:0;display:inline-block;"></span>
                                                @else
                                                    <span style="width:8px;flex-shrink:0;display:inline-block;"></span>
                                                @endif
                                                <div>
                                                    <span class="small">{{ $msg->name }}</span>
                                                    <div class="d-sm-none text-muted" style="font-size:11px;">{{ $msg->created_at->format('d.m.Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted d-none d-sm-table-cell">{{ $msg->email }}</td>
                                        <td class="small text-muted d-none d-md-table-cell" style="max-width:240px;">
                                            {{ Str::limit($msg->message, 55) }}
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @if ($msg->status === 'open')
                                                <span class="badge bg-success" style="font-size:10px;">Açık</span>
                                            @else
                                                <span class="badge bg-secondary" style="font-size:10px;">Kapalı</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted d-none d-lg-table-cell">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.contacts.show', $msg) }}"
                                               class="btn btn-sm btn-round"
                                               style="background:#3b5d50;color:#fff;font-size:11px;">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-sm-inline">Detay</span>
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
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Aylık Sipariş & Gelir grafiği (Chart.js v2)
    var ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    type: 'bar',
                    label: 'Sipariş Sayısı',
                    data: {!! json_encode($chartOrders) !!},
                    backgroundColor: 'rgba(59, 93, 80, 0.25)',
                    borderColor: 'rgba(59, 93, 80, 0.85)',
                    borderWidth: 2,
                    yAxisID: 'y-orders',
                },
                {
                    type: 'line',
                    label: 'Gelir (₺)',
                    data: {!! json_encode($chartRevenue) !!},
                    borderColor: 'rgba(249, 191, 41, 0.95)',
                    backgroundColor: 'rgba(249, 191, 41, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    lineTension: 0.4,
                    pointBackgroundColor: 'rgba(249, 191, 41, 1)',
                    yAxisID: 'y-revenue',
                }
            ]
        },
        options: {
            responsive: true,
            tooltips: { mode: 'index', intersect: false },
            legend: { position: 'top' },
            scales: {
                yAxes: [
                    {
                        id: 'y-orders',
                        type: 'linear',
                        position: 'left',
                        ticks: { beginAtZero: true, stepSize: 1 }
                    },
                    {
                        id: 'y-revenue',
                        type: 'linear',
                        position: 'right',
                        ticks: { beginAtZero: true },
                        gridLines: { drawOnChartArea: false }
                    }
                ]
            }
        }
    });

    // Kargo Durumu donut grafiği (Chart.js v2)
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Alındı', 'Hazırlanıyor', 'Kargoda', 'Teslim', 'İade', 'İptal'],
            datasets: [{
                data: {!! json_encode($chartStatuses) !!},
                backgroundColor: ['#6c757d','#0dcaf0','#3b5d50','#198754','#f9bf29','#dc3545'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, fontSize: 11 }
            }
        }
    });
</script>
@endpush
