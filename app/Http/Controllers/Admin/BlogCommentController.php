<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    public function index()
    {
        $pending  = BlogComment::pending()->with('blog')->latest()->get();
        $approved = BlogComment::approved()->with('blog')->latest()->paginate(20);

        return view('admin.pages.blog-comments.index', compact('pending', 'approved'));
    }

    public function approve(BlogComment $comment)
    {
        $comment->approve();

        return back()->with('success', '"' . $comment->name . '" adlı kullanıcının yorumu onaylandı.');
    }

    public function toggleVisibility(BlogComment $comment)
    {
        $comment->toggleVisibility();

        $msg = $comment->is_visible ? 'Yorum görünür yapıldı.' : 'Yorum gizlendi.';

        return back()->with('success', $msg);
    }

    public function destroy(BlogComment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Yorum silindi.');
    }
}
