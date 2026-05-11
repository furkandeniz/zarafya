@extends('app.layouts.main')

@section('seo_title', 'Ödeme')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('app.partials.page-hero', ['title' => 'Ödeme', 'subtitle' => 'Sipariş bilgilerinizi tamamlayın.', 'image' => 'odeme.png'])
    @include('app.partials.checkout-form', [
        'cart'           => $cart,
        'subtotal'       => $subtotal,
        'total'          => $total,
        'coupon'         => $coupon,
        'discountAmount' => $discountAmount,
        'user'           => $user,
    ])
@endsection
