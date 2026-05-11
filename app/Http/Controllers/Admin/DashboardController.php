<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'    => User::count(),
            'products' => Product::count(),
            'stores'   => Store::where('is_active', true)->count(),
            'orders'   => Order::count(),
            'revenue'  => Order::where('shipping_status', 'delivered')->sum('total_price'),
        ];

        // Son 6 aylık sipariş ve gelir
        $monthlyData = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chartLabels  = $monthlyData->pluck('month')
            ->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->locale('tr')->translatedFormat('M Y'))
            ->toArray();
        $chartOrders  = $monthlyData->pluck('order_count')->toArray();
        $chartRevenue = $monthlyData->pluck('revenue')->map(fn($v) => round((float)$v, 2))->toArray();

        // Aktif mağazalar (10'ar sayfalama)
        $stores = Store::where('is_active', true)
            ->withCount('products')
            ->latest()
            ->paginate(10, ['*'], 'stores_page');

        // Son siparişler (10'ar sayfalama)
        $orders = Order::with('store', 'firstItem')
            ->withCount('items')
            ->latest()
            ->paginate(10, ['*'], 'orders_page');

        // Son mesajlar (5'er sayfalama)
        $messages = ContactMessage::latest()->paginate(5, ['*'], 'messages_page');
        $unreadCount = ContactMessage::whereNull('read_at')->count();

        return view('admin.pages.dashboard', compact(
            'stats', 'chartLabels', 'chartOrders', 'chartRevenue',
            'stores', 'orders', 'messages', 'unreadCount'
        ));
    }
}
