<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate(['coupon' => ['required', 'string', 'max:50']]);

        $code   = strtoupper(trim($request->coupon));
        $cart   = session('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['coupon' => 'Sepetiniz boş.']);
        }

        // store_id eksik olan item'ları DB'den tamamla
        $productIds = collect($cart)->filter(fn ($i) => empty($i['store_id']))->pluck('id');
        if ($productIds->isNotEmpty()) {
            $storeMap = Product::whereIn('id', $productIds)->pluck('store_id', 'id');
            $cart = collect($cart)->map(function ($item) use ($storeMap) {
                if (empty($item['store_id'])) {
                    $item['store_id'] = $storeMap->get($item['id']);
                }
                return $item;
            })->all();
            session(['cart' => $cart]);
        }

        // Kuponu bul
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->withErrors(['coupon' => 'Kupon kodu bulunamadı.']);
        }

        // Aktif mi?
        if (!$coupon->is_active) {
            return back()->withErrors(['coupon' => 'Bu kupon aktif değil.']);
        }

        // Süresi dolmuş mu?
        if ($coupon->isExpired()) {
            return back()->withErrors(['coupon' => 'Bu kuponun süresi dolmuş.']);
        }

        // Kullanım limiti dolmuş mu?
        if ($coupon->isExhausted()) {
            return back()->withErrors(['coupon' => 'Bu kupon kullanım limitine ulaşmış.']);
        }

        // Sepette bu mağazadan ürün var mı? (int cast ile type-safe karşılaştırma)
        $couponStoreId = (int) $coupon->store_id;
        $storeItems = collect($cart)->filter(fn ($i) => (int) ($i['store_id'] ?? 0) === $couponStoreId);

        if ($storeItems->isEmpty()) {
            $storeName = $coupon->store?->name ?? 'ilgili mağaza';
            return back()->withErrors([
                'coupon' => "Bu kupon yalnızca \"{$storeName}\" mağazası ürünlerine uygulanabilir. Sepetinizde bu mağazadan ürün bulunmuyor.",
            ]);
        }

        // Minimum sipariş tutarı kontrolü
        $storeSubtotal = $storeItems->sum(fn ($i) => $i['price'] * $i['quantity']);

        if ($coupon->min_order_amount && $storeSubtotal < $coupon->min_order_amount) {
            return back()->withErrors([
                'coupon' => 'Bu kuponu kullanmak için ' . number_format($coupon->min_order_amount, 2, ',', '.') . ' ₺ ve üzeri sipariş vermeniz gerekiyor. (Mağaza toplamınız: ' . number_format($storeSubtotal, 2, ',', '.') . ' ₺)',
            ]);
        }

        // Kuponu session'a kaydet
        session(['coupon' => [
            'id'             => $coupon->id,
            'code'           => $coupon->code,
            'store_id'       => $coupon->store_id,
            'store_name'     => $coupon->store?->name ?? '—',
            'discount_type'  => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'min_order_amount' => $coupon->min_order_amount,
        ]]);

        $label = $coupon->discount_type === 'percent'
            ? '%' . $coupon->discount_value . ' indirim'
            : number_format($coupon->discount_value, 2, ',', '.') . ' ₺ indirim';

        return back()->with('cart_success', "Kupon uygulandı: {$label}");
    }

    public function remove()
    {
        session()->forget('coupon');
        return back()->with('cart_success', 'Kupon kaldırıldı.');
    }
}
