@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Siparişler</h3>
            <h6 class="op-7 mb-2">Tüm siparişlerin listesi</h6>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtreler --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Kullanıcı adı, e-posta veya ürün..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tüm Durumlar</option>
                        @foreach (App\Models\Order::STATUSES as $key => $s)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $s['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Filtrele</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Temizle</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Sipariş Listesi</h4>
            <span class="badge bg-secondary">{{ $orders->total() }} sipariş</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Kullanıcı</th>
                            <th>Ürünler</th>
                            <th>Mağaza</th>
                            <th>Kupon</th>
                            <th>Tutar</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th class="text-end pe-3">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php $status = App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                            <tr>
                                <td class="ps-3 text-muted small">#{{ $order->id }}</td>

                                {{-- Kullanıcı --}}
                                <td>
                                    @if ($order->user)
                                        <div class="fw-semibold small">{{ $order->user->name }}</div>
                                        <div class="text-muted" style="font-size:11px;">{{ $order->user->email }}</div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- Ürünler --}}
                                <td style="max-width:200px;">
                                    @foreach ($order->items->take(2) as $item)
                                        <div class="small">{{ Str::limit($item->product_name, 30) }}
                                            <span class="text-muted">x{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                    @if ($order->items->count() > 2)
                                        <div class="text-muted" style="font-size:11px;">+{{ $order->items->count() - 2 }} ürün daha</div>
                                    @endif
                                </td>

                                {{-- Mağaza --}}
                                <td class="small">{{ $order->store->name ?? '—' }}</td>

                                {{-- Kupon --}}
                                <td>
                                    @if ($order->coupon_code)
                                        <span class="badge bg-warning text-dark" style="font-size:10px;">
                                            {{ $order->coupon_code }}
                                        </span>
                                        <div class="text-success" style="font-size:11px;">
                                            &minus;{{ number_format($order->discount_amount, 2, ',', '.') }} ₺
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- Tutar --}}
                                <td class="fw-semibold small">
                                    {{ number_format($order->total_price, 2, ',', '.') }} ₺
                                </td>

                                {{-- Durum (inline güncelleme) --}}
                                <td>
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <select name="shipping_status"
                                                class="form-select form-select-sm"
                                                style="min-width:140px;"
                                                onchange="this.form.submit()">
                                            @foreach (App\Models\Order::STATUSES as $key => $s)
                                                <option value="{{ $key }}"
                                                    {{ $order->shipping_status === $key ? 'selected' : '' }}>
                                                    {{ $s['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                                {{-- Tarih --}}
                                <td class="text-muted small">{{ $order->created_at->format('d.m.Y H:i') }}</td>

                                {{-- İşlemler --}}
                                <td class="text-end pe-3" style="white-space:nowrap;">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Detay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Sipariş bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="d-flex justify-content-center py-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
