@extends('app.layouts.main')

@section('seo_title', 'İletişim')
@section('seo_description', 'Zarafya ile iletişime geçin. Sorularınız, önerileriniz veya siparişleriniz için bize ulaşın; en kısa sürede dönüş yapalım.')

@section('content')
    @include('app.partials.contact-hero')
    @include('app.partials.contact-form')
@endsection
