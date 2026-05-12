@extends('app.account._layout')

@php $pageTitle = 'Siparişlerim'; @endphp

@section('account-content')

<div class="account-card">
    <div class="account-card-title">Siparişlerim</div>

    @if ($orders->isEmpty())
        <div style="text-align:center;padding:48px 0;color:#8a8a8a;">
            <i class="fas fa-box-open" style="font-size:36px;margin-bottom:14px;display:block;opacity:.35;"></i>
            <div style="font-size:15px;font-weight:600;color:#4a4a4a;margin-bottom:6px;">Henüz siparişiniz yok</div>
            <div style="font-size:13px;margin-bottom:20px;">Ürünlerimize göz atmak ister misiniz?</div>
            <a href="{{ route('shop') }}" class="btn-account-primary" style="display:inline-block;text-decoration:none;padding:11px 26px;border-radius:8px;">Alışverişe Başla</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                <thead style="background:#f8faf8;font-size:12px;color:#6a6a6a;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                    <tr>
                        <th class="ps-0">Sipariş No</th>
                        <th>Tarih</th>
                        <th>Ürünler</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    @php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                    <tr>
                        <td class="ps-0" style="font-weight:700;">#{{ $order->id }}</td>
                        <td style="color:#6a6a6a;">{{ $order->created_at->format('d.m.Y') }}</td>
                        <td style="color:#6a6a6a;max-width:180px;">
                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $order->items->pluck('product_name')->implode(', ') }}
                            </div>
                        </td>
                        <td style="font-weight:700;">{{ number_format($order->total_price, 2, ',', '.') }} ₺</td>
                        <td>
                            <span class="order-status-badge {{ $status['class'] }} text-white">{{ $status['label'] }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('app.account.order.detail', $order) }}"
                               style="font-size:12px;color:#3b5d50;font-weight:600;text-decoration:none;white-space:nowrap;">
                                Detay <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
