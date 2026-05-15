<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:200',
            'phone'      => 'required|string|max:30',
            'address'    => 'required|string|max:500',
            'city'       => 'required|string|max:100',
            'district'   => 'required|string|max:100',
        ]);

        $coupon = session('coupon');

        $shippingAddress = implode(', ', array_filter([
            $request->first_name . ' ' . $request->last_name,
            $request->phone,
            $request->address,
            $request->district . ' / ' . $request->city,
            $request->postal_code ?: null,
        ]));

        $couponStoreId  = $coupon ? (int) $coupon['store_id'] : null;
        $couponType     = $coupon['discount_type']  ?? null;
        $couponValue    = $coupon ? (float) $coupon['discount_value'] : 0;
        $couponCode     = $coupon['code'] ?? null;

        $itemsByStore = collect($cart)->groupBy(fn ($i) => $i['store_id'] ?? 0);

        DB::transaction(function () use ($request, $itemsByStore, $couponStoreId, $couponType, $couponValue, $couponCode, $shippingAddress) {
            foreach ($itemsByStore as $storeId => $items) {
                $storeSubtotal = $items->sum(fn ($i) => $i['price'] * $i['quantity']);

                $discountAmount = 0;
                if ($couponStoreId && (int) $storeId === $couponStoreId) {
                    $discountAmount = $couponType === 'percent'
                        ? round($storeSubtotal * $couponValue / 100, 2)
                        : min($couponValue, $storeSubtotal);
                }

                $total = max(0, $storeSubtotal - $discountAmount);

                $order = Order::create([
                    'user_id'         => auth()->id(),
                    'store_id'        => $storeId ?: null,
                    'total_price'     => $total,
                    'coupon_code'     => $discountAmount > 0 ? $couponCode : null,
                    'discount_amount' => $discountAmount,
                    'shipping_status' => 'pending',
                    'shipping_address'=> $shippingAddress,
                    'notes'           => $request->order_note ?: null,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item['id'],
                        'product_name' => $item['name'] . (!empty($item['variant_label']) ? ' — ' . $item['variant_label'] : ''),
                        'quantity'     => $item['quantity'],
                        'price'        => $item['price'],
                    ]);

                    if (!empty($item['variant_id'])) {
                        ProductVariant::where('id', $item['variant_id'])
                            ->decrement('stock', $item['quantity']);
                    } else {
                        Product::where('id', $item['id'])
                            ->decrement('stock', $item['quantity']);
                    }
                }
            }
        });

        session()->forget(['cart', 'coupon', 'guest_checkout']);

        return redirect()->route('thankyou');
    }
}
