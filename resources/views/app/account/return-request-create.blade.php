@extends('app.account._layout')

@php $pageTitle = 'İade Talebi — Sipariş #' . $order->id; @endphp

@section('account-content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('app.account.order.detail', $order) }}"
       style="color:#3b5d50;font-size:13px;font-weight:600;text-decoration:none;">
        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Siparişe Dön
    </a>
</div>

<div class="account-card">
    <div class="account-card-title">İade Talebi — Sipariş #{{ $order->id }}</div>

    @if ($errors->any())
        <div class="alert-account-danger mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('app.return-request.store', $order) }}" method="POST">
        @csrf

        {{-- Ürün Seçimi --}}
        <div class="mb-4">
            <label style="font-size:13px;font-weight:700;color:#2f2f2f;display:block;margin-bottom:12px;">
                İade Etmek İstediğiniz Ürünleri Seçin <span style="color:#c0392b;">*</span>
            </label>

            @foreach ($order->items as $i => $item)
            <div class="d-flex align-items-center gap-3 p-3 mb-2"
                 style="background:#f8faf8;border-radius:10px;border:1px solid #eef1ee;">
                <input type="checkbox"
                       name="items[{{ $i }}][selected]"
                       value="1"
                       id="item_{{ $item->id }}"
                       class="item-check"
                       data-index="{{ $i }}"
                       style="width:18px;height:18px;cursor:pointer;accent-color:#3b5d50;">
                <input type="hidden" name="items[{{ $i }}][order_item_id]" value="{{ $item->id }}">

                <label for="item_{{ $item->id }}" class="flex-grow-1 mb-0" style="cursor:pointer;">
                    <div style="font-weight:600;font-size:14px;color:#2f2f2f;">{{ $item->product_name }}</div>
                    <div style="font-size:12px;color:#8a8a8a;">Sipariş adedi: {{ $item->quantity }}</div>
                </label>

                <div style="min-width:90px;">
                    <label style="font-size:11px;color:#6a6a6a;font-weight:600;display:block;margin-bottom:3px;">Adet</label>
                    <input type="number"
                           name="items[{{ $i }}][quantity]"
                           min="1"
                           max="{{ $item->quantity }}"
                           value="{{ $item->quantity }}"
                           class="account-form"
                           style="width:80px;border:1.5px solid #dde3dd;border-radius:8px;padding:5px 8px;font-size:13px;text-align:center;">
                </div>
            </div>
            @endforeach
        </div>

        {{-- İade Nedeni --}}
        <div class="mb-4">
            <label class="account-form" style="font-size:13px;font-weight:700;color:#2f2f2f;display:block;margin-bottom:8px;">
                İade Nedeni <span style="color:#c0392b;">*</span>
            </label>
            <div class="d-flex flex-wrap gap-2">
                @foreach (\App\Models\ReturnRequest::REASONS as $key => $label)
                    <label style="cursor:pointer;">
                        <input type="radio" name="reason" value="{{ $key }}"
                               {{ old('reason') === $key ? 'checked' : '' }}
                               style="accent-color:#3b5d50;">
                        <span style="font-size:13px;margin-left:4px;">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Açıklama --}}
        <div class="mb-5">
            <label style="font-size:13px;font-weight:700;color:#2f2f2f;display:block;margin-bottom:8px;">
                Açıklama <span style="font-weight:400;color:#8a8a8a;">(isteğe bağlı)</span>
            </label>
            <textarea name="note" rows="4"
                      placeholder="İade nedeninizi kısaca açıklayın..."
                      style="width:100%;border:1.5px solid #dde3dd;border-radius:8px;padding:10px 13px;font-size:14px;color:#2f2f2f;resize:vertical;font-family:inherit;">{{ old('note') }}</textarea>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn-account-primary">
                <i class="fas fa-paper-plane me-1"></i> Talebi Gönder
            </button>
            <a href="{{ route('app.account.order.detail', $order) }}"
               style="padding:11px 20px;border-radius:8px;font-size:14px;font-weight:600;color:#6a6a6a;border:1.5px solid #dde3dd;text-decoration:none;display:inline-flex;align-items:center;">
                Vazgeç
            </a>
        </div>

    </form>
</div>

@endsection
