@extends('app.layouts.main')

@section('content')
    @include('app.partials.page-hero', ['title' => 'Shop'])
    @include('app.partials.shop-products')
@endsection
