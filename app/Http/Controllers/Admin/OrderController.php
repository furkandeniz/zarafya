<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'store', 'items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('shipping_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('items', fn ($i) => $i->where('product_name', 'like', '%' . $request->search . '%'));
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.pages.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'store', 'items.product');
        return view('admin.pages.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'shipping_status' => ['required', 'in:' . implode(',', array_keys(Order::STATUSES))],
        ]);

        $order->update(['shipping_status' => $request->shipping_status]);

        $label = Order::STATUSES[$request->shipping_status]['label'];

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Durum güncellendi.', 'label' => $label]);
        }

        $from = url()->previous();
        $showUrl = route('admin.orders.show', $order);

        return str_contains($from, '/admin/orders/' . $order->id)
            ? redirect($showUrl)->with('success', 'Sipariş durumu "' . $label . '" olarak güncellendi.')
            : redirect()->route('admin.orders.index')->with('success', '#' . $order->id . ' siparişi "' . $label . '" olarak güncellendi.');
    }
}
