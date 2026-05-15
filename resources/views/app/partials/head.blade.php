<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="Zarafya">
<meta name="robots" content="@yield('robots', 'index, follow')">

{{-- Title --}}
@php
    $seoTitle = trim(strip_tags(View::yieldContent('seo_title')));
    $seoTitle = $seoTitle ? $seoTitle . ' | Zarafya' : 'Zarafya — El Emeği & El Yapımı Ürünler';

    $seoDesc = trim(strip_tags(View::yieldContent('seo_description')));
    $seoDesc = $seoDesc ?: 'Zarafya\'da el yapımı deri ürünler, mumlar, çantalar, kıyafetler ve daha fazlası. Her ürün ustanın elinden, kalbinden geliyor.';

    $ogImage = trim(View::yieldContent('og_image'));
    $ogImage = $ogImage ?: asset('images/og-default.jpg');

    $ogType = trim(View::yieldContent('og_type')) ?: 'website';
@endphp

<title>{{ $seoTitle }}</title>

{{-- Canonical --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Meta --}}
<meta name="description" content="{{ $seoDesc }}">

{{-- Open Graph --}}
<meta property="og:type"        content="{{ $ogType }}">
<meta property="og:site_name"   content="Zarafya">
<meta property="og:title"       content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:locale"      content="tr_TR">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDesc }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

{{-- Structured data (JSON-LD) --}}
@stack('jsonld')

{{-- Favicon --}}
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

{{-- CSS --}}
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/tiny-slider.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="{{ asset('css/zarafya.css') }}" rel="stylesheet">
