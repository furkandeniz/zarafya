<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Request $request, Store $store)
    {
        if (! $store->is_active) {
            abort(404);
        }

        $query = $store->products()
            ->with('firstImage', 'category', 'variants')
            ->available()
            ->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::whereHas('products', fn ($q) => $q->where('store_id', $store->id))
            ->orderBy('name')
            ->get();

        return view('app.pages.store', compact('store', 'products', 'categories'));
    }
}
