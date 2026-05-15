@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    {{-- Başlık + Dönem Filtresi --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-1">Bilanço</h3>
            <p class="text-muted small mb-0">Sipariş ve gelir özeti</p>
        </div>
        <form method="GET" action="{{ route('admin.balance.index') }}" class="d-flex gap-2 align-items-center">
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                <option value="7"   {{ $period == '7'   ? 'selected' : '' }}>Son 7 gün</option>
             dev   <option value="30"  {{ $period == '30'  ? 'selected' : '' }}>Son 30 gün</option>
                <option value="90"  {{ $period == '90'  ? 'selected' : '' }}>Son 90 gün</option>
                <option value="180" {{ $period == '180' ? 'selected' : '' }}>Son 6 ay</option>
                <option value="365" {{ $period == '365' ? 'selected' : '' }}>Son 1 yıl</option>
                <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Tüm Zamanlar</option>
            </select>
        </form>
    </div>

    {{-- Özet Kartlar --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #C49A6B;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Toplam Ciro</p>
                    <h4 class="fw-bold mb-0" style="color:#C49A6B;">{{ number_format($totalRevenue, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">{{ $totalOrders }} sipariş</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #28a745;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Teslim Edilen Gelir</p>
                    <h4 class="fw-bold mb-0" style="color:#28a745;">{{ number_format($deliveredRev, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">Gerçekleşen tahsilat</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #DCC687;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Bekleyen / Yolda</p>
                    <h4 class="fw-bold mb-0" style="color:#c9960a;">{{ number_format($pendingRev, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">Henüz teslim edilmedi</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #dc3545;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">İptal / İade</p>
                    <h4 class="fw-bold mb-0" style="color:#dc3545;">{{ number_format($cancelledRev + $returnedRev, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">
                        İptal: {{ number_format($cancelledRev, 2, ',', '.') }} ₺ &nbsp;|&nbsp;
                        İade: {{ number_format($returnedRev, 2, ',', '.') }} ₺
                    </small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #6c757d;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Toplam İndirim</p>
                    <h4 class="fw-bold mb-0">{{ number_format($totalDiscount, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">{{ $couponOrders }} siparişte kupon kullanıldı</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #17a2b8;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Brüt Tutar</p>
                    <h4 class="fw-bold mb-0" style="color:#17a2b8;">{{ number_format($grossRevenue, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">İndirim öncesi toplam</small>
                </div>
            </div>
        </div>

        @if ($totalOrders > 0)
        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #6f42c1;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Ortalama Sepet</p>
                    <h4 class="fw-bold mb-0" style="color:#6f42c1;">{{ number_format($totalRevenue / $totalOrders, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">Sipariş başına ortalama</small>
                </div>
            </div>
        </div>
        @endif

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #e83e8c;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Toplam Komisyon (%{{ round($commissionRate * 100, 2) }})</p>
                    <h4 class="fw-bold mb-0" style="color:#e83e8c;">{{ number_format($totalCommission, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">Seçili dönem · {{ number_format($totalRevenue, 2, ',', '.') }} ₺ üzerinden</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card card-stats h-100" style="border-left:4px solid #fd7e14;">
                <div class="card-body p-3">
                    <p class="card-category text-muted small mb-1">Geçen Ay Komisyonu</p>
                    <h4 class="fw-bold mb-0" style="color:#fd7e14;">{{ number_format($lastMonthCommission, 2, ',', '.') }} ₺</h4>
                    <small class="text-muted">{{ now()->subMonth()->locale('tr')->translatedFormat('F Y') }} · {{ number_format($lastMonthRev, 2, ',', '.') }} ₺ ciro</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Grafik + Durum Dağılımı --}}
    <div class="row g-3 mb-4">

        {{-- Aylık Gelir + Komisyon Grafiği --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">Aylık Gelir & Komisyon (Son 12 Ay)</h4>
                    <span class="badge" style="background:#e83e8c;font-size:11px;">%{{ round($commissionRate * 100, 2) }} Komisyon</span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Sipariş Durum Dağılımı --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Sipariş Durumları</h4>
                </div>
                <div class="card-body">
                    @php
                        $statuses = \App\Models\Order::STATUSES;
                    @endphp
                    <div class="d-flex flex-column gap-2 mt-1">
                        @foreach ($statuses as $key => $info)
                            @php
                                $row = $statusCounts->get($key);
                                $cnt = $row?->cnt ?? 0;
                                $rev = $row?->rev ?? 0;
                                $pct = $totalOrders > 0 ? round($cnt / $totalOrders * 100) : 0;
                            @endphp
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-semibold">{{ $info['label'] }}</span>
                                    <span class="small text-muted">{{ $cnt }} sipariş &nbsp; {{ number_format($rev, 0, ',', '.') }} ₺</span>
                                </div>
                                <div class="progress" style="height:6px;border-radius:4px;">
                                    <div class="progress-bar {{ $info['class'] }}"
                                         style="width:{{ $pct }}%;border-radius:4px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Mağaza Bazlı Tablo (sadece admin) --}}
    @if ($storeBreakdown->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Mağaza Bazlı Gelir</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px;color:#6a6a6a;">
                        <tr>
                            <th class="ps-4">Mağaza</th>
                            <th class="text-center">Sipariş</th>
                            <th class="text-end">İndirim</th>
                            <th class="text-end">Net Ciro</th>
                            <th class="text-end pe-4">Pay</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        @foreach ($storeBreakdown as $row)
                        <tr>
                            <td class="ps-4 fw-semibold">
                                @if ($row->store)
                                    <a href="{{ route('store.show', $row->store->slug) }}" target="_blank"
                                       class="text-decoration-none text-dark">
                                        {{ $row->store->name }}
                                        <i class="fas fa-external-link-alt ms-1" style="font-size:10px;color:#aaa;"></i>
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $row->order_count }}</td>
                            <td class="text-end text-danger">-{{ number_format($row->discount, 2, ',', '.') }} ₺</td>
                            <td class="text-end fw-bold" style="color:#C49A6B;">{{ number_format($row->revenue, 2, ',', '.') }} ₺</td>
                            <td class="text-end pe-4 text-muted small">
                                {{ $totalRevenue > 0 ? round($row->revenue / $totalRevenue * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light" style="font-size:13px;">
                        <tr>
                            <td class="ps-4 fw-bold">Toplam</td>
                            <td class="text-center fw-bold">{{ $totalOrders }}</td>
                            <td class="text-end text-danger fw-bold">-{{ number_format($totalDiscount, 2, ',', '.') }} ₺</td>
                            <td class="text-end fw-bold" style="color:#C49A6B;">{{ number_format($totalRevenue, 2, ',', '.') }} ₺</td>
                            <td class="text-end pe-4 fw-bold">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Son Siparişler --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Son Siparişler</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Tümünü Gör</a>
        </div>
        <div class="card-body p-0">
            @if ($recentOrders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-box-open" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
                    Bu dönemde sipariş yok.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px;color:#6a6a6a;">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Ürün</th>
                            @if (auth()->user()->isAdmin())<th>Mağaza</th>@endif
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th class="text-end">Tutar</th>
                            <th class="text-end pe-4" style="color:#e83e8c;">Komisyon (%{{ round($commissionRate * 100, 2) }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                        @php $st = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                        <tr>
                            <td class="ps-4 fw-semibold">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">#{{ $order->id }}</a>
                            </td>
                            <td class="text-muted" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $order->firstItem?->product_name ?? '—' }}
                            </td>
                            @if (auth()->user()->isAdmin())
                            <td class="text-muted small">{{ $order->store?->name ?? '—' }}</td>
                            @endif
                            <td class="text-muted small">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td><span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span></td>
                            <td class="text-end fw-bold">{{ number_format($order->total_price, 2, ',', '.') }} ₺</td>
                            <td class="text-end pe-4 fw-bold" style="color:#e83e8c;">{{ number_format($order->commission, 2, ',', '.') }} ₺</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Gelir (₺)',
                    data: @json($chartRevenue),
                    backgroundColor: 'rgba(59,93,80,0.75)',
                    borderColor: '#C49A6B',
                    borderWidth: 2,
                    borderRadius: 5,
                    yAxisID: 'y',
                },
                {
                    label: 'Komisyon — %{{ round($commissionRate * 100, 2) }} (₺)',
                    data: @json($chartCommission),
                    backgroundColor: 'rgba(232,62,140,0.65)',
                    borderColor: '#e83e8c',
                    borderWidth: 2,
                    borderRadius: 5,
                    yAxisID: 'y',
                },
                {
                    label: 'Sipariş Adedi',
                    data: @json($chartOrders),
                    type: 'line',
                    borderColor: '#DCC687',
                    backgroundColor: 'rgba(249,191,41,0.15)',
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.yAxisID === 'y'
                            ? ' ' + Number(ctx.raw).toLocaleString('tr-TR', {minimumFractionDigits:2}) + ' ₺'
                            : ' ' + ctx.raw + ' sipariş'
                    }
                },
                legend: { position: 'top', labels: { font: { size: 12 } } }
            },
            scales: {
                y:  { beginAtZero: true, position: 'left',  ticks: { callback: v => v.toLocaleString('tr-TR') + ' ₺', font:{size:11} } },
                y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { font:{size:11} } }
            }
        }
    });
}
</script>
@endpush
