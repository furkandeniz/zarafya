<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('store')->latest()->paginate(15);
        return view('admin.pages.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $stores = Store::orderBy('name')->get();
        return view('admin.pages.coupons.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id'          => 'nullable|exists:stores,id',
            'code'              => 'required|string|max:50|unique:coupons,code',
            'discount_type'     => 'required|in:percentage,fixed',
            'discount_value'    => 'required|numeric|min:0.01',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'max_uses'          => 'nullable|integer|min:1',
            'expires_at'        => 'nullable|date|after:today',
            'is_active'         => 'boolean',
        ]);

        $coupon = Coupon::create([
            'store_id'         => $request->store_id,
            'code'             => strtoupper($request->code),
            'discount_type'    => $request->discount_type,
            'discount_value'   => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'max_uses'         => $request->max_uses,
            'expires_at'       => $request->expires_at,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', '"' . $coupon->code . '" kuponu başarıyla oluşturuldu.');
    }

    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $stores = Store::orderBy('name')->get();
        return view('admin.pages.coupons.edit', compact('coupon', 'stores'));
    }

    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'store_id'         => 'nullable|exists:stores,id',
            'code'             => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type'    => 'required|in:percentage,fixed',
            'discount_value'   => 'required|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        $coupon->update([
            'store_id'         => $request->store_id,
            'code'             => strtoupper($request->code),
            'discount_type'    => $request->discount_type,
            'discount_value'   => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'max_uses'         => $request->max_uses,
            'expires_at'       => $request->expires_at ?: null,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', '"' . $coupon->code . '" kuponu başarıyla güncellendi.');
    }

    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $code   = $coupon->code;
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', '"' . $code . '" kuponu başarıyla silindi.');
    }
}
