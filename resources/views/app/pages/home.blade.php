@extends('app.layouts.main')

@section('seo_title', 'Ev Dekorasyon & Mobilya')
@section('seo_description', 'Zarafya ile yaşam alanlarınızı zarafetle donatın. Özel tasarım mobilya, dekorasyon ürünleri ve ev aksesuarlarında en seçkin koleksiyon.')

@section('content')
    @include('app.partials.hero')
    @include('app.partials.product-section')
    @include('app.partials.why-choose-us')
    @include('app.partials.we-help')
    @include('app.partials.testimonials')
@endsection
