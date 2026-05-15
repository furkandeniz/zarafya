@extends('app.layouts.main')

@section('seo_title', 'Ürünler')
@section('seo_description', 'El yapımı deri ürünler, mumlar, çantalar, kıyafetler, özel tasarım bardaklar, defterler ve daha fazlası. Zarafya\'nın el emeği koleksiyonunu keşfedin.')

@section('content')
    @include('app.partials.page-hero', ['title' => 'Ürünler', 'subtitle' => 'El emeğiyle üretilmiş özgün ürünleri keşfedin.', 'image' => 'urunler.png'])
    @include('app.partials.shop-products', ['products' => $products, 'categories' => $categories])
@endsection
