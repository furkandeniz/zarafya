{{--
    Parametreler:
    $formId      - form element id (zorunlu, sayfa içinde birden fazla kullanılabilir)
    $variantLabel - string|null - otomatik doldurulur (opsiyonel)
--}}
@php $formId = $formId ?? 'stockNotifyForm'; @endphp

@if (session('notify_success'))
    <div class="alert alert-success py-2 small">
        <i class="fa fa-check-circle me-1"></i> {{ session('notify_success') }}
    </div>
@elseif (session('notify_info'))
    <div class="alert alert-info py-2 small">{{ session('notify_info') }}</div>
@endif

<div class="border rounded p-3 bg-light">
    <p class="mb-2 small fw-semibold text-muted">
        <i class="fa fa-bell me-1"></i> Stok gelince haber ver
    </p>
    <form id="{{ $formId }}" action="{{ route('stock.notify') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="variant_label" class="notify-variant-label" value="{{ $variantLabel ?? '' }}">
        <div class="d-flex gap-2">
            <input type="email" name="email"
                   class="form-control form-control-sm"
                   placeholder="E-posta adresiniz"
                   value="{{ auth()->user()?->email ?? old('email') }}"
                   required>
            <button type="submit" class="btn btn-sm btn-outline-secondary flex-shrink-0">
                Haber Ver
            </button>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </form>
</div>
