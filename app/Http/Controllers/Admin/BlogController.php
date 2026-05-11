<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('author')->latest()->paginate(15);
        return view('admin.pages.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.pages.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = $this->uniqueSlug($validated['title']);
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $isPublished = $request->boolean('is_published');

        Blog::create([
            'user_id'      => auth()->id(),
            'title'        => $validated['title'],
            'slug'         => $slug,
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'],
            'image'        => $imagePath,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog yazısı oluşturuldu.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.pages.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $imagePath = $blog->image;

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $isPublished = $request->boolean('is_published');
        $publishedAt = $blog->published_at;

        if ($isPublished && !$blog->is_published) {
            $publishedAt = now();
        } elseif (!$isPublished) {
            $publishedAt = null;
        }

        $blog->update([
            'title'        => $validated['title'],
            'excerpt'      => $validated['excerpt'] ?? null,
            'content'      => $validated['content'],
            'image'        => $imagePath,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog yazısı güncellendi.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog yazısı silindi.');
    }

    public function togglePublish(Blog $blog)
    {
        if ($blog->is_published) {
            $blog->unpublish();
            $msg = '"' . $blog->title . '" yayından kaldırıldı.';
        } else {
            $blog->publish();
            $msg = '"' . $blog->title . '" yayınlandı.';
        }
        return back()->with('success', $msg);
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title, '-');
        $base = $slug;
        $i = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
