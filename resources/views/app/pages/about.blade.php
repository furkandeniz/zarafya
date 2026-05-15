@extends('app.layouts.main')

@section('seo_title', 'Hakkımızda')
@section('seo_description', 'Zarafya hakkında bilgi edinin. El emeğiyle üretilen özgün ürünleri ustalardan doğrudan sizlere ulaştırma hikayemizi keşfedin.')

@section('content')
    @include('app.partials.about-hero')

    @include('app.partials.why-choose-us')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
