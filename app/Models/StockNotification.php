<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockNotification extends Model
{
    protected $fillable = ['product_id', 'variant_label', 'email', 'notified_at'];

    protected $casts = ['notified_at' => 'datetime'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
