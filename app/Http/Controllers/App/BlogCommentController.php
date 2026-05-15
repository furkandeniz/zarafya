<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function store(Request $request, Blog $blog)
    {
        if (!$blog->is_published) {
            abort(404);
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:200'],
            'body'  => ['required', 'string', 'max:2000'],
        ], [
            'name.required'  => 'İsim alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email'    => 'Geçerli bir e-posta adresi girin.',
            'body.required'  => 'Yorum alanı zorunludur.',
            'body.max'       => 'Yorum en fazla 2000 karakter olabilir.',
        ]);

        $blog->comments()->create([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'body'  => $validated['body'],
        ]);

        return back()->with('comment_success', 'Yorumunuz alındı. Admin onayından sonra yayınlanacaktır.');
    }
}
