<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::select('slug', 'updated_at')->get();
        $blogs    = Blog::published()->select('slug', 'published_at', 'updated_at')->get();

        $staticPages = [
            ['url' => route('home'),     'priority' => '1.0',  'changefreq' => 'weekly'],
            ['url' => route('shop'),     'priority' => '0.9',  'changefreq' => 'daily'],
            ['url' => route('blog'),     'priority' => '0.8',  'changefreq' => 'weekly'],
            ['url' => route('about'),    'priority' => '0.6',  'changefreq' => 'monthly'],
            ['url' => route('services'), 'priority' => '0.6',  'changefreq' => 'monthly'],
            ['url' => route('contact'),  'priority' => '0.5',  'changefreq' => 'yearly'],
        ];

        return response()
            ->view('sitemap', compact('staticPages', 'products', 'blogs'))
            ->header('Content-Type', 'application/xml');
    }
}
