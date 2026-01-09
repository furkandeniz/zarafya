@extends('app.layouts.main')

@section('content')
    @include('app.partials.hero')
    @include('app.partials.product-section')
    @include('app.partials.why-choose-us')
    @include('app.partials.we-help')
    @include('app.partials.popular-product')
    @include('app.partials.testimonials')
    @include('app.partials.blog-section')
@endsection
