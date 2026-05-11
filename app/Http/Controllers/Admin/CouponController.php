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
        $user  = auth()->user();
        $query = Coupon::with('store')->latest();
        if ($user->isSeller()) {
            $query->where('store_id', $user->store_id);
        }
        $coupons = $query->paginate(15);
        return view('admin.pages.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $user   = auth()->user();
        $stores = $user->isSeller()
            ? Store::where('id', $user->store_id)->get()
            : Store::orderBy('name')->get();
        return view('admin.pages.coupons.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
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
            'store_id'         => $user->isSeller() ? $user->store_id : $request->store_id,
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
        $user   = auth()->user();
        $coupon = Coupon::findOrFail($id);

        if ($user->isSeller() && $coupon->store_id !== $user->store_id) {
            abort(403);
        }

        $stores = $user->isSeller()
            ? Store::where('id', $user->store_id)->get()
            : Store::orderBy('name')->get();
        return view('admin.pages.coupons.edit', compact('coupon', 'stores'));
    }

    public function update(Request $request, string $id)
    {
        $user   = auth()->user();
        $coupon = Coupon::findOrFail($id);

        if ($user->isSeller() && $coupon->store_id !== $user->store_id) {
            abort(403);
        }

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
            'store_id'         => $user->isSeller() ? $user->store_id : $request->store_id,
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
        $user   = auth()->user();
        $coupon = Coupon::findOrFail($id);

        if ($user->isSeller() && $coupon->store_id !== $user->store_id) {
            abort(403);
        }
        $code   = $coupon->code;
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', '"' . $code . '" kuponu başarıyla silindi.');
    }
}
