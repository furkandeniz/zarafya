@extends('app.layouts.main')

@section('content')
    @include('app.partials.about-hero')

    @include('app.partials.why-choose-us')

    @include('app.partials.about-team')

    <div class="testimonial-section before-footer-section">
        @include('app.partials.testimonials')
    </div>
@endsection
