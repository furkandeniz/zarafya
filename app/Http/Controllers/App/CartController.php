<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart   = session('cart', []);
        $coupon = session('coupon');

        $storeIds = collect($cart)->pluck('store_id')->filter()->unique()->values();
        $stores   = Store::whereIn('id', $storeIds)->get()->keyBy('id');

        // Sepet kalemlerini mağaza kampanyasına göre efektif fiyatla zenginleştir
        $enrichedCart = [];
        foreach ($cart as $key => $item) {
            $store        = $item['store_id'] ? $stores->get($item['store_id']) : null;
            $originalPrice = (float) $item['price'];
            $effectivePrice = $store ? $store->discountedPrice($originalPrice) : $originalPrice;

            $onPromo = $store && $store->isOnPromotion();
            $enrichedCart[$key] = array_merge($item, [
                'original_price'    => $originalPrice,
                'effective_price'   => $effectivePrice,
                'store_discount'    => $onPromo ? $store->promo_discount : 0,
                'store_discount_type' => $onPromo ? ($store->promo_discount_type ?? 'percent') : 'percent',
            ]);
        }

        $subtotal       = collect($enrichedCart)->sum(fn ($i) => $i['original_price'] * $i['quantity']);
        $promoSaving    = collect($enrichedCart)->sum(fn ($i) => ($i['original_price'] - $i['effective_price']) * $i['quantity']);
        $discountAmount = 0;

        if ($coupon) {
            $couponStoreId = (int) $coupon['store_id'];
            $storeSubtotal = collect($enrichedCart)
                ->filter(fn ($i) => (int) ($i['store_id'] ?? 0) === $couponStoreId)
                ->sum(fn ($i) => $i['effective_price'] * $i['quantity']);

            $discountAmount = $coupon['discount_type'] === 'percent'
                ? round($storeSubtotal * $coupon['discount_value'] / 100, 2)
                : min((float) $coupon['discount_value'], $storeSubtotal);
        }

        $shippingLines = [];
        $totalShipping = 0;

        foreach ($storeIds as $storeId) {
            $store = $stores->get($storeId);
            if (!$store) continue;

            $storeSubtotal = collect($enrichedCart)
                ->filter(fn ($i) => (int) ($i['store_id'] ?? 0) === (int) $storeId)
                ->sum(fn ($i) => $i['effective_price'] * $i['quantity']);

            $cost = $store->shippingCostFor($storeSubtotal);

            $shippingLines[] = [
                'store_name'     => $store->name,
                'shipping_type'  => $store->shipping_type,
                'cost'           => $cost,
                'threshold'      => (float) ($store->free_shipping_threshold ?? 0),
                'store_subtotal' => $storeSubtotal,
            ];

            if ($cost !== null) {
                $totalShipping += $cost;
            }
        }

        $total = max(0, $subtotal - $promoSaving - $discountAmount + $totalShipping);

        return view('app.pages.cart', compact(
            'cart', 'enrichedCart', 'subtotal', 'promoSaving', 'total', 'coupon', 'discountAmount',
            'shippingLines', 'totalShipping'
        ));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity'   => ['integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'integer'],
        ]);

        $qty = (int) $request->input('quantity', 1);

        $product->load('firstImage', 'variants', 'store');

        if ($product->store && !$product->store->is_active) {
            return back()->with('cart_flash', ['type' => 'error', 'msg' => 'Bu ürünün mağazası şu anda aktif değil.']);
        }

        $variant      = null;
        $cartKey      = (string) $product->id;
        $price        = (float) $product->price;
        $stock        = (int) ($product->stock ?? 0);
        $variantLabel = null;

        if ($request->filled('variant_id')) {
            $variant = $product->variants->firstWhere('id', (int) $request->variant_id);

            if (!$variant) {
                return back()->with('cart_flash', ['type' => 'error', 'msg' => 'Geçersiz varyant.']);
            }

            $cartKey      = $product->id . '_' . $variant->id;
            $price        = $variant->price !== null ? (float) $variant->price : $price;
            $stock        = (int) $variant->stock;
            $variantLabel = $variant->label;
        }

        $displayName = $product->name . ($variantLabel ? ' — ' . $variantLabel : '');

        if ($stock <= 0) {
            return back()->with('cart_flash', ['type' => 'error', 'msg' => '"' . $displayName . '" stokta yok.']);
        }

        $cart       = session('cart', []);
        $currentQty = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;

        if ($currentQty >= $stock) {
            return back()->with('cart_flash', ['type' => 'error', 'msg' => '"' . $displayName . '" için maksimum stok adedine ulaştınız (maks. ' . $stock . ' adet).']);
        }

        $allowedQty = min($qty, $stock - $currentQty);

        $image = $product->firstImage?->image
            ? asset('storage/' . $product->firstImage->image)
            : asset('images/product-1.png');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $allowedQty;
        } else {
            $cart[$cartKey] = [
                'cart_key'      => $cartKey,
                'id'            => $product->id,
                'variant_id'    => $variant?->id,
                'variant_label' => $variantLabel,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'price'         => $price,
                'quantity'      => $allowedQty,
                'image'         => $image,
                'store_id'      => $product->store_id,
            ];
        }

        session(['cart' => $cart]);
        $this->revalidateCoupon($cart);

        if ($allowedQty < $qty) {
            return back()->with('cart_flash', ['type' => 'warning', 'msg' => '"' . $displayName . '" sepete eklendi; ancak stok sınırı nedeniyle yalnızca ' . $allowedQty . ' adet eklenebildi (maks. ' . $stock . ' adet).']);
        }

        return back()->with('cart_flash', ['type' => 'success', 'msg' => '"' . $displayName . '" sepete eklendi.']);
    }

    public function update(Request $request, $cartKey)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        $cart = session('cart', []);
        $key  = (string) $cartKey;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
            $this->revalidateCoupon($cart);
        }

        return back()->with('cart_flash', ['type' => 'success', 'msg' => 'Sepet güncellendi.']);
    }

    public function remove($cartKey)
    {
        $cart = session('cart', []);
        unset($cart[(string) $cartKey]);
        session(['cart' => $cart]);
        $this->revalidateCoupon($cart);

        return back()->with('cart_flash', ['type' => 'success', 'msg' => 'Ürün sepetten çıkarıldı.']);
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');
        return back()->with('cart_flash', ['type' => 'success', 'msg' => 'Sepet temizlendi.']);
    }

    private function revalidateCoupon(array $cart): void
    {
        $coupon = session('coupon');
        if (!$coupon) return;

        $couponStoreId = (int) $coupon['store_id'];
        $storeSubtotal = collect($cart)
            ->filter(fn ($i) => (int) ($i['store_id'] ?? 0) === $couponStoreId)
            ->sum(fn ($i) => $i['price'] * $i['quantity']);

        if ($storeSubtotal == 0 || ($coupon['min_order_amount'] && $storeSubtotal < $coupon['min_order_amount'])) {
            session()->forget('coupon');
        }
    }
}
