@extends('app.layouts.main')

@section('seo_title', 'Hizmetlerimiz')
@section('seo_description', 'Zarafya\'nın sunduğu hizmetleri keşfedin. El yapımı ürün satışı, özel sipariş ve ustalarla doğrudan iletişim imkânı.')

@section('content')
    @include('app.partials.services-hero')
    @include('app.partials.services-features')
    @include('app.partials.services-products')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
