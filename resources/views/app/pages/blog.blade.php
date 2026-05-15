@extends('app.layouts.main')

@section('seo_title', 'Blog')
@section('seo_description', 'Zarafya Blog — El sanatları, el yapımı ürünler ve zanaatkârlık üzerine ilham veren yazılar, hikayeler ve ipuçları.')

@section('content')
    @include('app.partials.blog-hero')
    @include('app.partials.blog-posts', ['posts' => $posts])
    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
