@extends('app.layouts.main')

@section('content')
    @include('app.partials.blog-hero')

    @include('app.partials.blog-posts')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
