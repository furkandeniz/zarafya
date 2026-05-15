@extends('app.layouts.main')

@section('seo_title', 'El Yapımı Ürünler')
@section('seo_description', 'Zarafya\'da el emeğiyle üretilmiş deri ürünler, mumlar, çantalar, kıyafetler, özel tasarım bardaklar, defterler ve daha fazlasını keşfedin. Her ürün bir ustanın imzasını taşır.')

@section('content')
    @include('app.partials.hero')
    @include('app.partials.product-section')
    @include('app.partials.why-choose-us')
    @include('app.partials.we-help')
    @include('app.partials.testimonials')
@endsection
