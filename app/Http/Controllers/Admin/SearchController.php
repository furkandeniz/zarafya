<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return back()->with('search_error', 'En az 2 karakter girin.');
        }

        $user = auth()->user();

        $products = Product::with('store', 'category')
            ->where('name', 'like', "%{$q}%")
            ->when($user->isSeller(), fn ($query) => $query->where('store_id', $user->store?->id))
            ->limit(10)
            ->get();

        $orders = Order::with('user', 'store', 'firstItem')
            ->where(function ($query) use ($q) {
                $query->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
                      ->orWhereHas('items', fn ($i) => $i->where('product_name', 'like', "%{$q}%"))
                      ->orWhere('id', is_numeric($q) ? $q : 0);
            })
            ->when($user->isSeller(), fn ($query) => $query->where('store_id', $user->store?->id))
            ->limit(10)
            ->get();

        $users = $user->isAdmin()
            ? User::where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->limit(10)
                  ->get()
            : collect();

        $messages = $user->isAdmin()
            ? ContactMessage::where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('message', 'like', "%{$q}%")
                            ->limit(10)
                            ->get()
            : collect();

        return view('admin.pages.search', compact('q', 'products', 'orders', 'users', 'messages'));
    }
}
