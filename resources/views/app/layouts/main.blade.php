<!doctype html>
<html lang="tr">
<head>
    @include('app.partials.head')
    @stack('styles')
</head>

<body>

@include('app.partials.navbar')

@if (!request()->routeIs('checkout*', 'thankyou'))
    @include('app.partials.promo-slider')
@endif

{{-- Flash Toast --}}
@php
    $flash = session('cart_flash');
    $toastMsg  = $flash['msg']  ?? null;
    $toastType = $flash['type'] ?? null;
    if ($toastType === 'success') {
        $toastBorder = '#28a745'; $toastColor = '#28a745'; $toastIcon = 'fa-check-circle';
    } elseif ($toastType === 'warning') {
        $toastBorder = '#ffc107'; $toastColor = '#e6a800'; $toastIcon = 'fa-exclamation-triangle';
    } else {
        $toastBorder = '#dc3545'; $toastColor = '#dc3545'; $toastIcon = 'fa-times-circle';
    }
@endphp
@if ($toastMsg)
<style>
    @@keyframes slideInToast {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
    }
</style>
<div id="cartToast" style="position:fixed;top:24px;right:24px;z-index:99999;max-width:380px;min-width:260px;background:#fff;border-radius:10px;box-shadow:0 4px 24px rgba(0,0,0,0.15);border-left:5px solid {{ $toastBorder }};padding:14px 18px;display:flex;align-items:flex-start;gap:12px;animation:slideInToast .3s ease;">
    <i class="fas {{ $toastIcon }}" style="font-size:18px;margin-top:2px;color:{{ $toastColor }};flex-shrink:0;"></i>
    <span style="font-size:14px;line-height:1.5;color:#333;flex:1;">{{ $toastMsg }}</span>
    <button onclick="document.getElementById('cartToast').remove()" style="background:none;border:none;font-size:18px;line-height:1;color:#aaa;cursor:pointer;padding:0;margin-left:4px;">&times;</button>
</div>
<script>
    setTimeout(function() {
        var t = document.getElementById('cartToast');
        if (t) { t.style.transition = 'opacity .4s'; t.style.opacity = '0'; setTimeout(function(){ t.remove(); }, 400); }
    }, 4000);
</script>
@endif

<main>
    @yield('content')
</main>

@include('app.partials.footer')
@include('app.partials.scripts')
@stack('scripts')

</body>
</html>
