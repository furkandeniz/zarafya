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

        $blog->increment('visit_count');

        $comments = $blog->approvedComments()->get();

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        return view('app.pages.blog-detail', compact('blog', 'related', 'comments'));
    }
}
