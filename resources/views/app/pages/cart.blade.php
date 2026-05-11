@extends('app.layouts.main')

@section('content')
    @include('app.partials.cart-hero')
    @include('app.partials.cart-table', [
        'cart'           => $cart,
        'subtotal'       => $subtotal,
        'total'          => $total,
        'coupon'         => $coupon,
        'discountAmount' => $discountAmount,
    ])
@endsection
