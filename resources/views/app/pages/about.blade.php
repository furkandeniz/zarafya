@extends('app.layouts.main')

@section('seo_title', 'Hakkımızda')
@section('seo_description', 'Zarafya hakkında bilgi edinin. Ev dekorasyon ve mobilya dünyasında kalite ve zarafeti bir araya getiren hikayemizi keşfedin.')

@section('content')
    @include('app.partials.about-hero')

    @include('app.partials.why-choose-us')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
