<?php

namespace App\Http\Controllers\App;

use App\Models\Product;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with('firstImage')
            ->latest()
            ->limit(3)
            ->get();

        return view('app.pages.home', compact('featured'));
    }
}
