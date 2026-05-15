@extends('app.layouts.main')

@section('seo_title', 'Sepetim')
@section('robots', 'noindex, nofollow')

@section('content')
    @include('app.partials.cart-hero')
    @include('app.partials.cart-table', [
        'cart'           => $cart,
        'subtotal'       => $subtotal,
        'total'          => $total,
        'coupon'         => $coupon,
        'discountAmount' => $discountAmount,
        'shippingLines'  => $shippingLines,
        'totalShipping'  => $totalShipping,
    ])
@endsection
