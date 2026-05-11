<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'store_id', 'code', 'discount_type', 'discount_value',
        'min_order_amount', 'max_uses', 'used_count',
        'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->max_uses && $this->used_count >= $this->max_uses;
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active)    return 'passive';
        if ($this->isExpired())   return 'expired';
        if ($this->isExhausted()) return 'exhausted';
        return 'active';
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
