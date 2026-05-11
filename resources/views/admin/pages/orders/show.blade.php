@extends('admin.layouts.main')

@section('content')
<div class="page-inner">

    <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Sipariş #{{ $order->id }}</h3>
            <h6 class="op-7 mb-2">
                <a href="{{ route('admin.orders.index') }}" class="text-muted">Siparişler</a>
                &rsaquo; #{{ $order->id }}
            </h6>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-round">
            <i class="fas fa-arrow-left me-1"></i> Geri
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php $status = App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp

    <div class="row g-3">

        {{-- Sol: Sipariş Kalemleri --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sipariş Kalemleri</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Ürün</th>
                                <th class="text-center">Adet</th>
                                <th class="text-end">Birim Fiyat</th>
                                <th class="text-end pe-3">Toplam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold small">{{ $item->product_name }}</div>
                                        @if ($item->product)
                                            <a href="{{ route('admin.products.edit', $item->product) }}"
                                               class="text-muted" style="font-size:11px;">Ürüne git →</a>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end small">{{ number_format($item->price, 2, ',', '.') }} ₺</td>
                                    <td class="text-end pe-3 fw-semibold small">
                                        {{ number_format($item->price * $item->quantity, 2, ',', '.') }} ₺
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            @if ($order->coupon_code)
                                <tr>
                                    <td colspan="3" class="text-end text-muted small ps-3">Ara Toplam</td>
                                    <td class="text-end pe-3 small">
                                        {{ number_format($order->total_price + $order->discount_amount, 2, ',', '.') }} ₺
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end pe-3 small">
                                        <span class="text-success">
                                            Kupon İndirimi
                                            <span class="badge bg-warning text-dark ms-1">{{ $order->coupon_code }}</span>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 small text-success fw-semibold">
                                        &minus;{{ number_format($order->discount_amount, 2, ',', '.') }} ₺
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-3">Genel Toplam</td>
                                <td class="text-end pe-3 fw-bold">
                                    {{ number_format($order->total_price, 2, ',', '.') }} ₺
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Teslimat Adresi --}}
            @if ($order->shipping_address)
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Teslimat Adresi</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            @endif

            {{-- Notlar --}}
            @if ($order->notes)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Sipariş Notu</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sağ: Özet & Durum --}}
        <div class="col-lg-4">

            {{-- Kullanıcı --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Müşteri</h4>
                </div>
                <div class="card-body">
                    @if ($order->user)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user text-muted small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $order->user->name }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $order->user->email }}</div>
                            </div>
                        </div>
                        <div class="small text-muted">
                            Rol: <span class="badge bg-light text-dark">{{ $order->user->role }}</span>
                        </div>
                    @else
                        <span class="text-muted small">Kullanıcı silinmiş</span>
                    @endif
                </div>
            </div>

            {{-- Mağaza --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">Mağaza</h4>
                </div>
                <div class="card-body">
                    @if ($order->store)
                        <div class="d-flex align-items-center gap-2">
                            @if ($order->store->logo)
                                <img src="{{ asset('storage/' . $order->store->logo) }}"
                                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            @endif
                            <span class="fw-semibold small">{{ $order->store->name }}</span>
                        </div>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </div>
            </div>

            {{-- Sipariş Tarihi --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Sipariş Tarihi</span>
                        <span>{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Son Güncelleme</span>
                        <span>{{ $order->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Durum Güncelle --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sipariş Durumu</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge {{ $status['class'] }} fs-6 px-3 py-2">{{ $status['label'] }}</span>
                    </div>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Durumu Güncelle</label>
                            <select name="shipping_status" class="form-select form-select-sm">
                                @foreach (App\Models\Order::STATUSES as $key => $s)
                                    <option value="{{ $key }}" {{ $order->shipping_status === $key ? 'selected' : '' }}>
                                        {{ $s['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-round w-100 btn-sm">
                            <i class="fas fa-save me-1"></i> Güncelle
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
