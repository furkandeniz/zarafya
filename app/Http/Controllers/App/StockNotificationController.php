<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockNotification;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => ['required', 'exists:products,id'],
            'email'         => ['required', 'email', 'max:255'],
            'variant_label' => ['nullable', 'string', 'max:255'],
        ]);

        $product      = Product::findOrFail($request->product_id);
        $variantLabel = $request->filled('variant_label') ? $request->variant_label : null;

        // Stok zaten varsa kayıt açma
        if ($variantLabel) {
            $variant = $product->variants()->where('label', $variantLabel)->first();
            if ($variant && $variant->stock > 0) {
                return back()->with('notify_info', 'Bu ürün zaten stokta mevcut.');
            }
        } else {
            if (($product->stock ?? 0) > 0) {
                return back()->with('notify_info', 'Bu ürün zaten stokta mevcut.');
            }
        }

        StockNotification::firstOrCreate([
            'product_id'    => $product->id,
            'variant_label' => $variantLabel,
            'email'         => strtolower(trim($request->email)),
        ]);

        return back()->with('notify_success', 'Stok geldiğinde ' . $request->email . ' adresine haber vereceğiz.');
    }
}
