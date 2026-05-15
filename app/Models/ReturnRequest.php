<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'reason', 'note', 'status', 'admin_note', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    const REASONS = [
        'hasarli'     => 'Hasarlı / Bozuk Geldi',
        'yanlis_urun' => 'Yanlış Ürün Gönderildi',
        'begenmedi'   => 'Beğenmedim',
        'diger'       => 'Diğer',
    ];

    const STATUSES = [
        'pending'  => ['label' => 'Beklemede',  'class' => 'bg-warning text-dark'],
        'approved' => ['label' => 'Onaylandı',  'class' => 'bg-success'],
        'rejected' => ['label' => 'Reddedildi', 'class' => 'bg-danger'],
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class);
    }
}
