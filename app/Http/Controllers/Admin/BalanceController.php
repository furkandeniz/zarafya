<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $storeId = $user->isSeller() ? $user->store_id : null;

        // Tarih filtresi
        $period = $request->get('period', '30');
        $from   = match ($period) {
            '7'    => now()->subDays(7)->startOfDay(),
            '30'   => now()->subDays(30)->startOfDay(),
            '90'   => now()->subDays(90)->startOfDay(),
            '180'  => now()->subDays(180)->startOfDay(),
            '365'  => now()->subDays(365)->startOfDay(),
            'all'  => null,
            default => now()->subDays(30)->startOfDay(),
        };

        $base = Order::query();
        if ($storeId)  $base->where('store_id', $storeId);
        if ($from)     $base->where('created_at', '>=', $from);

        // ── Özet Kartlar ──────────────────────────────────────────
        $totalRevenue    = (clone $base)->sum('total_price');
        $totalDiscount   = (clone $base)->sum('discount_amount');
        $grossRevenue    = $totalRevenue + $totalDiscount;

        $deliveredRev    = (clone $base)->where('shipping_status', 'delivered')->sum('total_price');
        $pendingRev      = (clone $base)->whereIn('shipping_status', ['pending', 'processing', 'shipped'])->sum('total_price');
        $cancelledRev    = (clone $base)->where('shipping_status', 'cancelled')->sum('total_price');
        $returnedRev     = (clone $base)->where('shipping_status', 'returned')->sum('total_price');

        $totalOrders     = (clone $base)->count();
        $couponOrders    = (clone $base)->whereNotNull('coupon_code')->count();

        // ── Durum Dağılımı ─────────────────────────────────────────
        $statusCounts = (clone $base)
            ->select('shipping_status', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_price) as rev'))
            ->groupBy('shipping_status')
            ->get()
            ->keyBy('shipping_status');

        // ── Komisyon oranı (erken okunur, her hesaplamada kullanılır) ──
        $commissionRate = (float) Setting::get('commission_rate', 15) / 100;

        // ── Aylık Gelir (son 12 ay) ─────────────────────────────
        $monthlyQuery = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('SUM(discount_amount) as discount')
            )
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month');

        if ($storeId) $monthlyQuery->where('store_id', $storeId);

        $monthly      = $monthlyQuery->get();
        $chartLabels     = $monthly->pluck('month')
            ->map(fn($m) => Carbon::parse($m . '-01')->locale('tr')->translatedFormat('M Y'))
            ->toArray();
        $chartRevenue    = $monthly->pluck('revenue')->map(fn($v) => round((float) $v, 2))->toArray();
        $chartOrders     = $monthly->pluck('order_count')->toArray();
        $chartCommission = $monthly->pluck('revenue')->map(fn($v) => round((float) $v * $commissionRate, 2))->toArray();

        // ── Mağaza Bazlı (sadece admin) ─────────────────────────
        $storeBreakdown = collect();
        if (! $storeId) {
            $storeQuery = Order::select(
                    'store_id',
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_price) as revenue'),
                    DB::raw('SUM(discount_amount) as discount')
                )
                ->with('store:id,name,slug')
                ->groupBy('store_id')
                ->orderByDesc('revenue');

            if ($from) $storeQuery->where('created_at', '>=', $from);

            $storeBreakdown = $storeQuery->get();
        }

        // ── Komisyon ────────────────────────────────────────────
        $totalCommission = round($totalRevenue * $commissionRate, 2);

        $lastMonthStart  = now()->subMonth()->startOfMonth();
        $lastMonthEnd    = now()->subMonth()->endOfMonth();
        $lastMonthRev    = Order::query()
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_price');
        $lastMonthCommission = round($lastMonthRev * $commissionRate, 2);

        // Her sipariş için komisyon (son siparişler tablosunda kullanmak üzere)
        $recentOrders = (clone $base)
            ->with('store:id,name', 'firstItem')
            ->latest()
            ->limit(10)
            ->get()
            ->each(fn($o) => $o->commission = round($o->total_price * $commissionRate, 2));

        return view('admin.pages.balance', compact(
            'period', 'totalRevenue', 'totalDiscount', 'grossRevenue',
            'deliveredRev', 'pendingRev', 'cancelledRev', 'returnedRev',
            'totalOrders', 'couponOrders', 'statusCounts',
            'chartLabels', 'chartRevenue', 'chartOrders', 'chartCommission',
            'totalCommission', 'lastMonthCommission', 'lastMonthRev',
            'commissionRate', 'storeBreakdown', 'recentOrders'
        ));
    }
}
