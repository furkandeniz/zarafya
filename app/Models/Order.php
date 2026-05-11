<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'store_id', 'total_price', 'coupon_code', 'discount_amount',
        'shipping_status', 'shipping_address', 'notes',
    ];

    const STATUSES = [
        'pending'    => ['label' => 'Sipariş Alındı',    'class' => 'bg-secondary'],
        'processing' => ['label' => 'Hazırlanıyor',      'class' => 'bg-info text-dark'],
        'shipped'    => ['label' => 'Kargoya Verildi',   'class' => 'bg-primary'],
        'delivered'  => ['label' => 'Teslim Edildi',     'class' => 'bg-success'],
        'returned'   => ['label' => 'İade Edildi',       'class' => 'bg-warning text-dark'],
        'cancelled'  => ['label' => 'İptal Edildi',      'class' => 'bg-danger'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function firstItem()
    {
        return $this->hasOne(OrderItem::class);
    }
}
