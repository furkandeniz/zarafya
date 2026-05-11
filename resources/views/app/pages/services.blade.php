@extends('app.layouts.main')

@section('seo_title', 'Hizmetlerimiz')
@section('seo_description', 'Zarafya\'nın sunduğu hizmetleri keşfedin. İç mekan tasarımı, mobilya seçimi ve dekorasyon danışmanlığında yanınızdayız.')

@section('content')
    @include('app.partials.services-hero')
    @include('app.partials.services-features')
    @include('app.partials.services-products')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
