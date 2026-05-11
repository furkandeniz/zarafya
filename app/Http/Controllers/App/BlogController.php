<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::published()->with('author')->latest('published_at')->paginate(9);
        return view('app.pages.blog', compact('posts'));
    }

    public function show(Blog $blog)
    {
        if (!$blog->is_published) {
            abort(404);
        }
        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        return view('app.pages.blog-detail', compact('blog', 'related'));
    }
}
