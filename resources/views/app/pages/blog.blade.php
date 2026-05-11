@extends('app.layouts.main')

@section('seo_title', 'Blog')
@section('seo_description', 'Zarafya Blog — İç mekan tasarımı, dekorasyon tüyoları ve yaşam alanlarınız için ilham veren yazılar.')

@section('content')
    @include('app.partials.blog-hero')
    @include('app.partials.blog-posts', ['posts' => $posts])
    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
