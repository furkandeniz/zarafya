<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function show(Product $product)
    {
        $product->load('images', 'category', 'store', 'attributes', 'variants');

        // Mağazası pasif olan ürün sayfasını 404 döndür
        if ($product->store && !$product->store->is_active) {
            abort(404);
        }

        $related = Product::with('firstImage')
            ->available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('app.pages.product', compact('product', 'related'));
    }

    public function index(Request $request)
    {
        $query = Product::with('firstImage', 'category', 'variants')
            ->available()
            ->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('app.pages.shop', compact('products', 'categories'));
    }
}
