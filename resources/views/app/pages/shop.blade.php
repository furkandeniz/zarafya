@extends('app.layouts.main')

@section('seo_title', 'Ürünler')
@section('seo_description', 'Zarafya\'nın seçkin mobilya ve dekorasyon koleksiyonunu keşfedin. Evinize yakışan ürünü bulun.')

@section('content')
    @include('app.partials.page-hero', ['title' => 'Ürünler', 'subtitle' => 'Zarafya\'nın seçkin koleksiyonunu keşfedin.', 'image' => 'urunler.png'])
    @include('app.partials.shop-products', ['products' => $products, 'categories' => $categories])
@endsection
