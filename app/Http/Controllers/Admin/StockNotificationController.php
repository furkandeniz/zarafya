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
        $query = StockNotification::with('product')
            ->latest();

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
        $stockNotification->delete();
        return back()->with('success', 'Bildirim kaydı silindi.');
    }
}
