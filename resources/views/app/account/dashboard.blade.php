@extends('app.account._layout')

@php $pageTitle = 'Hesabım'; @endphp

@section('account-content')

<div class="account-card mb-4">
    <div class="d-flex align-items-center gap-3 mb-1">
        <div style="font-size:28px;">👋</div>
        <div>
            <div style="font-size:20px;font-weight:800;color:#2f2f2f;letter-spacing:-0.4px;">
                Hoş geldiniz, {{ explode(' ', auth()->user()->name)[0] }}!
            </div>
            <div style="font-size:13px;color:#8a8a8a;margin-top:2px;">Hesap panelinizden tüm bilgilerinizi yönetebilirsiniz.</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="account-card text-center" style="padding:24px 16px;">
            <div style="font-size:28px;font-weight:800;color:#3b5d50;">{{ $totalOrders }}</div>
            <div style="font-size:13px;color:#6a6a6a;margin-top:4px;">Toplam Sipariş</div>
        </div>
    </div>
    <div class="col-sm-4">
        <a href="{{ route('app.account.profile') }}" class="account-card text-center d-block text-decoration-none" style="padding:24px 16px;">
            <div style="font-size:24px;color:#3b5d50;"><i class="fas fa-user"></i></div>
            <div style="font-size:13px;color:#6a6a6a;margin-top:8px;">Bilgilerimi Güncelle</div>
        </a>
    </div>
    <div class="col-sm-4">
        <a href="{{ route('app.account.password') }}" class="account-card text-center d-block text-decoration-none" style="padding:24px 16px;">
            <div style="font-size:24px;color:#3b5d50;"><i class="fas fa-lock"></i></div>
            <div style="font-size:13px;color:#6a6a6a;margin-top:8px;">Şifre Güncelle</div>
        </a>
    </div>
</div>

<div class="account-card" style="position:relative;z-index:2;">
    <div class="account-card-title">Son Siparişler</div>

    @if ($recentOrders->isEmpty())
        <div style="text-align:center;padding:40px 0;color:#8a8a8a;">
            <i class="fas fa-box-open" style="font-size:32px;margin-bottom:12px;display:block;opacity:.4;"></i>
            Henüz siparişiniz bulunmuyor.
            <div class="mt-3">
                <a href="{{ route('shop') }}" class="btn-account-primary" style="display:inline-block;text-decoration:none;padding:10px 22px;border-radius:8px;">Alışverişe Başla</a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                <thead style="background:#f8faf8;font-size:12px;color:#6a6a6a;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                    <tr>
                        <th class="ps-0">Sipariş No</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                    @php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp
                    <tr>
                        <td class="ps-0 fw-600">#{{ $order->id }}</td>
                        <td style="color:#6a6a6a;">{{ $order->created_at->format('d.m.Y') }}</td>
                        <td class="fw-600">{{ number_format($order->total_price, 2, ',', '.') }} ₺</td>
                        <td><span class="order-status-badge {{ $status['class'] }} text-white">{{ $status['label'] }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('app.account.order.detail', $order) }}" style="font-size:12px;color:#3b5d50;font-weight:600;text-decoration:none;">
                                Detay <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($totalOrders > 5)
        <div class="mt-3 text-center">
            <a href="{{ route('app.account.orders') }}" style="font-size:13px;color:#3b5d50;font-weight:600;text-decoration:none;">
                Tüm siparişleri gör <i class="fas fa-arrow-right" style="font-size:11px;"></i>
            </a>
        </div>
        @endif
    @endif
</div>

@endsection
