<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogComment extends Model
{
    protected $fillable = ['blog_id', 'name', 'email', 'body', 'is_approved', 'approved_at', 'is_visible'];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_visible'  => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function approve(): void
    {
        $this->update(['is_approved' => true, 'approved_at' => now()]);
    }

    public function toggleVisibility(): void
    {
        $this->update(['is_visible' => !$this->is_visible]);
    }

    /** "Furkan Deniz" → "F***** D****" */
    public function getMaskedNameAttribute(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->map(fn (string $word) => mb_substr($word, 0, 1) . str_repeat('*', max(0, mb_strlen($word) - 1)))
            ->implode(' ');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
