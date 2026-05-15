@php
    $shippingType = $store->shipping_type ?? 'paid';
    $shippingCost = (float) ($store->shipping_cost ?? 0);
    $threshold    = (float) ($store->free_shipping_threshold ?? 0);
@endphp

<div class="d-flex align-items-center gap-3 mb-2">
    <span class="text-muted small" style="min-width:80px;">Kargo</span>
    <div>
        @if ($shippingType === 'free')
            <span class="badge d-inline-flex align-items-center gap-1"
                  style="background:#e8f5e9;color:#2e7d32;font-size:0.78rem;padding:5px 10px;border-radius:20px;font-weight:600;">
                <i class="fas fa-shipping-fast" style="font-size:0.7rem;"></i>
                Ücretsiz Kargo
            </span>
        @elseif ($shippingType === 'free_above' && $threshold > 0)
            <span class="badge d-inline-flex align-items-center gap-1"
                  style="background:#fff8e1;color:#b08000;font-size:0.78rem;padding:5px 10px;border-radius:20px;font-weight:600;">
                <i class="fas fa-shipping-fast" style="font-size:0.7rem;"></i>
                {{ number_format($threshold, 0, ',', '.') }} ₺ üzeri ücretsiz kargo
            </span>
            @if ($shippingCost > 0)
                <span class="text-muted small ms-2">
                    (Aksi hâlde {{ number_format($shippingCost, 2, ',', '.') }} ₺)
                </span>
            @endif
        @else
            @if ($shippingCost > 0)
                <span class="badge d-inline-flex align-items-center gap-1"
                      style="background:#f5f0eb;color:#5a5a5a;font-size:0.78rem;padding:5px 10px;border-radius:20px;font-weight:600;">
                    <i class="fas fa-truck" style="font-size:0.7rem;"></i>
                    Kargo: {{ number_format($shippingCost, 2, ',', '.') }} ₺
                </span>
            @else
                <span class="text-muted small">Belirtilmemiş</span>
            @endif
        @endif
    </div>
</div>
