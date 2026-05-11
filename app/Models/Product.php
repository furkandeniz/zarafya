<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const COMMISSION_RATE = 0.15;

    protected $fillable = ['category_id', 'store_id', 'name', 'slug', 'description', 'price', 'cost_price', 'expected_price', 'stock'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('order');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }

    public function getTotalStockAttribute(): int
    {
        if ($this->stock !== null) {
            return (int) $this->stock;
        }
        // Eager loaded ise collection üzerinden hesapla, yoksa DB sorgusu yap
        if ($this->relationLoaded('variants')) {
            return (int) $this->variants->sum('stock');
        }
        return (int) $this->variants()->sum('stock');
    }
}
