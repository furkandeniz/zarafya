<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content',
        'image', 'is_published', 'published_at', 'visit_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)
            ->where('is_approved', true)
            ->where('is_visible', true)
            ->latest();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function publish(): void
    {
        $this->update(['is_published' => true, 'published_at' => now()]);
    }

    public function unpublish(): void
    {
        $this->update(['is_published' => false, 'published_at' => null]);
    }
}
