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
        $user     = auth()->user();
        $storeId  = $user->isSeller() ? $user->store_id : null;

        $stats = $user->isSeller()
            ? [
                'users'    => null,
                'products' => Product::where('store_id', $storeId)->count(),
                'stores'   => 1,
                'orders'   => Order::where('store_id', $storeId)->count(),
                'revenue'  => Order::where('store_id', $storeId)->where('shipping_status', 'delivered')->sum('total_price'),
            ]
            : [
                'users'    => User::count(),
                'products' => Product::count(),
                'stores'   => Store::where('is_active', true)->count(),
                'orders'   => Order::count(),
                'revenue'  => Order::where('shipping_status', 'delivered')->sum('total_price'),
            ];

        $ordersQuery = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month');

        if ($storeId) {
            $ordersQuery->where('store_id', $storeId);
        }

        $monthlyData  = $ordersQuery->get();
        $chartLabels  = $monthlyData->pluck('month')
            ->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->locale('tr')->translatedFormat('M Y'))
            ->toArray();
        $chartOrders  = $monthlyData->pluck('order_count')->toArray();
        $chartRevenue = $monthlyData->pluck('revenue')->map(fn($v) => round((float)$v, 2))->toArray();

        $storesQuery = Store::where('is_active', true)->withCount('products')->latest();
        if ($storeId) {
            $storesQuery->where('id', $storeId);
        }
        $stores = $storesQuery->paginate(10, ['*'], 'stores_page');

        $ordersListQuery = Order::with('store', 'firstItem')->withCount('items')->latest();
        if ($storeId) {
            $ordersListQuery->where('store_id', $storeId);
        }
        $orders = $ordersListQuery->paginate(10, ['*'], 'orders_page');

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'returned', 'cancelled'];
        $chartStatuses = collect($statuses)->map(function ($s) use ($storeId) {
            $q = Order::where('shipping_status', $s);
            if ($storeId) {
                $q->where('store_id', $storeId);
            }
            return $q->count();
        })->values()->toArray();

        $messages    = $user->isSeller() ? collect() : ContactMessage::latest()->paginate(5, ['*'], 'messages_page');
        $unreadCount = $user->isSeller() ? 0 : ContactMessage::whereNull('read_at')->count();

        return view('admin.pages.dashboard', compact(
            'stats', 'chartLabels', 'chartOrders', 'chartRevenue',
            'chartStatuses', 'stores', 'orders', 'messages', 'unreadCount'
        ));
    }
}
