<?php

namespace App\Http\Controllers\App;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with('firstImage')
            ->available()
            ->latest()
            ->limit(3)
            ->get();

        $activePromos = Store::where('is_active', true)
            ->whereNotNull('promo_discount')
            ->where('promo_discount', '>', 0)
            ->where(fn ($q) => $q->whereNull('promo_starts_at')->orWhere('promo_starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('promo_ends_at')->orWhere('promo_ends_at', '>=', now()))
            ->get();

        return view('app.pages.home', compact('featured', 'activePromos'));
    }
}
