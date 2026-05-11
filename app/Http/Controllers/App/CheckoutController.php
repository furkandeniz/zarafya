<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function gate()
    {
        if (auth()->check()) {
            return redirect()->route('checkout');
        }

        session()->put('url.intended', route('checkout'));

        return view('app.pages.checkout-gate');
    }

    public function continueAsGuest()
    {
        session(['guest_checkout' => true]);
        return redirect()->route('checkout');
    }

    public function show()
    {
        $cart   = session('cart', []);
        $coupon = session('coupon');

        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $subtotal       = collect($cart)->sum(fn ($i) => $i['price'] * $i['quantity']);
        $discountAmount = 0;

        if ($coupon) {
            $couponStoreId = (int) $coupon['store_id'];
            $storeSubtotal = collect($cart)
                ->filter(fn ($i) => (int) ($i['store_id'] ?? 0) === $couponStoreId)
                ->sum(fn ($i) => $i['price'] * $i['quantity']);

            $discountAmount = $coupon['discount_type'] === 'percent'
                ? round($storeSubtotal * $coupon['discount_value'] / 100, 2)
                : min((float) $coupon['discount_value'], $storeSubtotal);
        }

        $total  = max(0, $subtotal - $discountAmount);
        $user   = auth()->user();
        $cities = config('cities');

        return view('app.pages.checkout', compact('cart', 'subtotal', 'total', 'coupon', 'discountAmount', 'user', 'cities'));
    }

    public function store()
    {
        session()->forget('cart');
        session()->forget('coupon');
        session()->forget('guest_checkout');

        return redirect()->route('thankyou');
    }
}
