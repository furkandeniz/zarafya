@extends('app.layouts.main')

@section('content')
    @include('app.partials.services-hero')
    @include('app.partials.services-features')
    @include('app.partials.services-products')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
