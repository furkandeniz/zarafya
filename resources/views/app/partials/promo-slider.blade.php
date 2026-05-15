@php
    $activePromos = $activePromos ?? \App\Models\Store::where('is_active', true)
        ->whereNotNull('promo_discount')
        ->where('promo_discount', '>', 0)
        ->where(fn ($q) => $q->whereNull('promo_starts_at')->orWhere('promo_starts_at', '<=', now()))
        ->where(fn ($q) => $q->whereNull('promo_ends_at')->orWhere('promo_ends_at', '>=', now()))
        ->get();
@endphp

@if ($activePromos->isNotEmpty())
<div class="promo-slider-wrap">
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach ($activePromos as $index => $promo)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="promo-slide d-flex align-items-center justify-content-center gap-3 flex-wrap">

                        {{-- Logo --}}
                        @if ($promo->logo)
                            <img src="{{ asset('storage/' . $promo->logo) }}"
                                 alt="{{ $promo->name }}"
                                 class="promo-logo">
                        @endif

                        {{-- Metin --}}
                        <div class="promo-text text-center text-md-start">
                            <span class="promo-badge">{{ $promo->promoLabel() }}</span>
                            <span class="promo-name">{{ $promo->name }}</span>
                            @if ($promo->promo_title)
                                <span class="promo-title-text">{{ $promo->promo_title }}</span>
                            @endif
                        </div>

                        {{-- Bitiş sayacı --}}
                        @if ($promo->promo_ends_at)
                            <div class="promo-countdown text-center">
                                <span class="countdown-label">Son</span>
                                <span class="countdown-timer"
                                      data-ends="{{ $promo->promo_ends_at->toISOString() }}">
                                    —
                                </span>
                            </div>
                        @endif

                        {{-- CTA --}}
                        <a href="{{ route('store.show', $promo->slug) }}" class="promo-cta">
                            Hemen Al &rarr;
                        </a>

                    </div>
                </div>
            @endforeach
        </div>

        @if ($activePromos->count() > 1)
            <button class="carousel-control-prev promo-ctrl" type="button"
                    data-bs-target="#promoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next promo-ctrl" type="button"
                    data-bs-target="#promoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            <div class="carousel-indicators promo-indicators">
                @foreach ($activePromos as $index => $promo)
                    <button type="button" data-bs-target="#promoCarousel"
                            data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.promo-slider-wrap {
    background: linear-gradient(90deg, #C49A6B 0%, #DCC687 50%, #C49A6B 100%);
    border-bottom: 2px solid #b8862f;
}
.promo-slide {
    padding: 10px 60px;
    min-height: 56px;
    gap: 16px;
}
.promo-logo {
    width: 36px;
    height: 36px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.6);
    flex-shrink: 0;
}
.promo-text {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}
.promo-badge {
    background: #fff;
    color: #C49A6B;
    font-weight: 800;
    font-size: 13px;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    white-space: nowrap;
}
.promo-name {
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    white-space: nowrap;
}
.promo-title-text {
    color: rgba(255,255,255,0.85);
    font-size: 13px;
    font-style: italic;
    white-space: nowrap;
}
.promo-countdown {
    display: flex;
    flex-direction: column;
    align-items: center;
    border-left: 1px solid rgba(255,255,255,0.4);
    padding-left: 14px;
}
.countdown-label {
    color: rgba(255,255,255,0.75);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    line-height: 1;
}
.countdown-timer {
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    font-variant-numeric: tabular-nums;
}
.promo-cta {
    background: #fff;
    color: #C49A6B;
    font-weight: 700;
    font-size: 12px;
    padding: 6px 18px;
    border-radius: 20px;
    text-decoration: none;
    white-space: nowrap;
    transition: background .2s, color .2s;
    flex-shrink: 0;
}
.promo-cta:hover {
    background: #5A5A5A;
    color: #fff;
}
.promo-ctrl {
    width: 32px;
    opacity: .6;
}
.promo-ctrl:hover { opacity: 1; }
.promo-indicators {
    bottom: -18px;
}
.promo-indicators button {
    background-color: rgba(255,255,255,0.5);
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.promo-indicators button.active {
    background-color: #fff;
}
@media (max-width: 576px) {
    .promo-slide { padding: 10px 36px; gap: 8px; }
    .promo-title-text { display: none; }
    .promo-countdown { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.countdown-timer').forEach(function (el) {
        var endsAt = new Date(el.dataset.ends);

        function update() {
            var diff = Math.max(0, endsAt - Date.now());
            if (diff === 0) { el.textContent = 'Bitti'; return; }
            var d = Math.floor(diff / 86400000);
            var h = Math.floor((diff % 86400000) / 3600000);
            var m = Math.floor((diff % 3600000) / 60000);
            var s = Math.floor((diff % 60000) / 1000);
            el.textContent = d > 0
                ? d + 'g ' + String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0')
                : String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }
        update();
        setInterval(update, 1000);
    });
});
</script>
@endif
