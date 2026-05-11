<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart   = session('cart', []);
        $coupon = session('coupon');

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

        $total = max(0, $subtotal - $discountAmount);

        return view('app.pages.cart', compact('cart', 'subtotal', 'total', 'coupon', 'discountAmount'));
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
