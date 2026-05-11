<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockNotification;
use Illuminate\Http\Request;

class StockNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = StockNotification::with('product')->latest();

        if ($user->isSeller()) {
            $query->whereHas('product', fn ($q) => $q->where('store_id', $user->store_id));
        }

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereNull('notified_at');
            } elseif ($request->status === 'notified') {
                $query->whereNotNull('notified_at');
            }
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $notifications = $query->paginate(20)->withQueryString();
        $products      = Product::orderBy('name')->get(['id', 'name']);
        $pendingCount  = StockNotification::whereNull('notified_at')->count();

        return view('admin.pages.stock-notifications.index',
            compact('notifications', 'products', 'pendingCount'));
    }

    public function destroy(StockNotification $stockNotification)
    {
        $user = auth()->user();
        if ($user->isSeller()) {
            $stockNotification->load('product');
            if (!$stockNotification->product || $stockNotification->product->store_id !== $user->store_id) {
                abort(403);
            }
        }

        $stockNotification->delete();
        return back()->with('success', 'Bildirim kaydı silindi.');
    }
}
