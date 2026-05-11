<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'combination', 'label', 'cost_price', 'expected_price', 'price', 'stock'];

    protected $casts = ['combination' => 'array'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price !== null ? (float) $this->price : (float) $this->product->price;
    }
}