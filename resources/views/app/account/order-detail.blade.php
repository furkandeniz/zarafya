@extends('app.account._layout')

@php $pageTitle = 'Sipariş #' . $order->id; @endphp

@section('account-content')

@php $status = \App\Models\Order::STATUSES[$order->shipping_status] ?? ['label' => $order->shipping_status, 'class' => 'bg-secondary']; @endphp

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('app.account.orders') }}" style="color:#3b5d50;font-size:13px;font-weight:600;text-decoration:none;">
        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Siparişlerime Dön
    </a>
</div>

<div class="account-card mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-4" style="border-bottom:1px solid #eef1ee;">
        <div>
            <div style="font-size:20px;font-weight:800;color:#2f2f2f;letter-spacing:-0.4px;">Sipariş #{{ $order->id }}</div>
            <div style="font-size:13px;color:#8a8a8a;margin-top:3px;">{{ $order->created_at->format('d F Y, H:i') }}</div>
        </div>
        <span class="order-status-badge {{ $status['class'] }} text-white" style="font-size:13px;padding:5px 14px;">
            {{ $status['label'] }}
        </span>
    </div>

    <div class="account-card-title" style="font-size:15px;">Ürünler</div>

    <div class="table-responsive mb-4">
        <table class="table align-middle mb-0" style="font-size:14px;">
            <thead style="background:#f8faf8;font-size:12px;color:#6a6a6a;font-weight:600;text-transform:uppercase;letter-spacing:.4px;">
                <tr>
                    <th class="ps-0">Ürün</th>
                    <th class="text-center">Adet</th>
                    <th class="text-end pe-0">Fiyat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td class="ps-0">
                        <div style="font-weight:600;color:#2f2f2f;">{{ $item->product_name }}</div>
                        @if ($item->product)
                            <a href="{{ route('shop.product', $item->product->slug) }}"
                               style="font-size:12px;color:#3b5d50;text-decoration:none;">Ürünü görüntüle</a>
                        @endif
                    </td>
                    <td class="text-center" style="color:#6a6a6a;">{{ $item->quantity }}</td>
                    <td class="text-end pe-0" style="font-weight:700;">{{ number_format($item->price * $item->quantity, 2, ',', '.') }} ₺</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="ps-0 text-end" style="font-size:13px;color:#6a6a6a;font-weight:600;">Toplam</td>
                    <td class="text-end pe-0" style="font-size:18px;font-weight:800;color:#3b5d50;">
                        {{ number_format($order->total_price, 2, ',', '.') }} ₺
                    </td>
                </tr>
                @if ($order->discount_amount > 0)
                <tr>
                    <td colspan="2" class="ps-0 text-end" style="font-size:12px;color:#6a6a6a;">
                        İndirim
                        @if ($order->coupon_code)
                            <span style="background:#f0faf4;color:#1e7145;padding:1px 7px;border-radius:10px;font-size:11px;font-weight:600;">{{ $order->coupon_code }}</span>
                        @endif
                    </td>
                    <td class="text-end pe-0" style="color:#1e7145;font-weight:700;">
                        -{{ number_format($order->discount_amount, 2, ',', '.') }} ₺
                    </td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    @if ($order->shipping_address)
    <div style="background:#f8faf8;border-radius:10px;padding:16px 18px;">
        <div style="font-size:12px;font-weight:700;color:#6a6a6a;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">
            <i class="fas fa-map-marker-alt" style="color:#3b5d50;margin-right:5px;"></i> Teslimat Adresi
        </div>
        <div style="font-size:14px;color:#2f2f2f;">{{ $order->shipping_address }}</div>
    </div>
    @endif

    @if ($order->notes)
    <div style="background:#f8faf8;border-radius:10px;padding:16px 18px;margin-top:12px;">
        <div style="font-size:12px;font-weight:700;color:#6a6a6a;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">
            <i class="fas fa-sticky-note" style="color:#3b5d50;margin-right:5px;"></i> Notlar
        </div>
        <div style="font-size:14px;color:#2f2f2f;">{{ $order->notes }}</div>
    </div>
    @endif
</div>


{{-- İade Talebi Bölümü --}}
@php
    $returnRequest = \Illuminate\Support\Facades\Schema::hasTable('return_requests')
        ? $order->returnRequest
        : null;
    $canReturn = $order->shipping_status === 'delivered'
        && (!$returnRequest || $returnRequest->status === 'rejected');
@endphp

@if ($returnRequest)
<div class="account-card mt-4">
    @php $rs = \App\Models\ReturnRequest::STATUSES[$returnRequest->status] ?? ['label' => $returnRequest->status, 'class' => 'bg-secondary']; @endphp

    <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom:1px solid #eef1ee;">
        <div class="account-card-title mb-0" style="font-size:15px;">İade Talebi</div>
        <span class="order-status-badge {{ $rs['class'] }} text-white" style="font-size:12px;padding:4px 12px;">
            {{ $rs['label'] }}
        </span>
    </div>

    <div style="font-size:13px;color:#6a6a6a;margin-bottom:6px;">
        <strong style="color:#2f2f2f;">Neden:</strong>
        {{ \App\Models\ReturnRequest::REASONS[$returnRequest->reason] ?? $returnRequest->reason }}
    </div>

    @if ($returnRequest->note)
    <div style="font-size:13px;color:#6a6a6a;margin-bottom:6px;">
        <strong style="color:#2f2f2f;">Açıklama:</strong> {{ $returnRequest->note }}
    </div>
    @endif

    <div style="font-size:12px;color:#aaa;margin-bottom:12px;">
        Talep Tarihi: {{ $returnRequest->created_at->format('d.m.Y H:i') }}
    </div>

    @if ($returnRequest->items->isNotEmpty())
    <div style="font-size:12px;font-weight:700;color:#6a6a6a;text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;">
        İade Edilecek Ürünler
    </div>
    <ul class="list-unstyled mb-3">
        @foreach ($returnRequest->items as $ri)
        <li style="font-size:13px;color:#2f2f2f;padding:5px 0;border-bottom:1px solid #f0f0f0;">
            {{ $ri->orderItem->product_name ?? '—' }}
            <span style="color:#8a8a8a;margin-left:6px;">× {{ $ri->quantity }}</span>
        </li>
        @endforeach
    </ul>
    @endif

    @if ($returnRequest->admin_note)
    <div style="background:#f8faf8;border-radius:8px;padding:12px 14px;font-size:13px;">
        <div style="font-weight:700;color:#2f2f2f;margin-bottom:4px;">
            <i class="fas fa-comment-dots" style="color:#3b5d50;margin-right:5px;"></i>Satıcı Yanıtı
        </div>
        {{ $returnRequest->admin_note }}
    </div>
    @endif

    @if ($canReturn)
    <div class="mt-3" style="position:relative;z-index:2;">
        <a href="{{ route('app.return-request.create', $order) }}"
           style="font-size:13px;color:#3b5d50;font-weight:600;text-decoration:none;">
            <i class="fas fa-redo" style="font-size:11px;margin-right:4px;"></i> Yeni Talep Oluştur
        </a>
    </div>
    @endif
</div>

@elseif ($canReturn)
<div class="account-card mt-4" style="border:1.5px dashed #dde3dd;position:relative;z-index:2;">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <div style="font-size:14px;font-weight:700;color:#2f2f2f;margin-bottom:3px;">İade Talebi</div>
            <div style="font-size:12px;color:#8a8a8a;">Ürünlerinizden memnun kalmadıysanız iade talebi oluşturabilirsiniz.</div>
        </div>
        <a href="{{ route('app.return-request.create', $order) }}" class="btn-account-primary"
           style="white-space:nowrap;flex-shrink:0;">
            <i class="fas fa-undo-alt me-1"></i> İade Talebi Oluştur
        </a>
    </div>
</div>
@endif

@if (session('success'))
<div class="alert-account-success mt-3">{{ session('success') }}</div>
@endif

@endsection
