<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'description',
        'email', 'phone', 'address', 'is_active',
        'bank_name', 'account_holder', 'iban', 'account_number', 'branch_name', 'branch_code',
        'shipping_type', 'shipping_cost', 'free_shipping_threshold',
        'promo_discount', 'promo_discount_type', 'promo_title', 'promo_starts_at', 'promo_ends_at',
    ];

    protected $casts = [
        'is_active'                => 'boolean',
        'shipping_cost'            => 'float',
        'free_shipping_threshold'  => 'float',
        'promo_discount'           => 'float',
        'promo_starts_at'          => 'datetime',
        'promo_ends_at'            => 'datetime',
    ];

    public function isOnPromotion(): bool
    {
        if (!$this->promo_discount || $this->promo_discount <= 0) {
            return false;
        }
        $now = now();
        if ($this->promo_starts_at && $now->lt($this->promo_starts_at)) {
            return false;
        }
        if ($this->promo_ends_at && $now->gt($this->promo_ends_at)) {
            return false;
        }
        return true;
    }

    public function discountedPrice(float $price): float
    {
        if (!$this->isOnPromotion()) {
            return $price;
        }
        if ($this->promo_discount_type === 'amount') {
            return max(0, round($price - $this->promo_discount, 2));
        }
        return round($price * (1 - $this->promo_discount / 100), 2);
    }

    public function promoLabel(): string
    {
        if ($this->promo_discount_type === 'amount') {
            return number_format($this->promo_discount, 2, ',', '.') . ' ₺ İNDİRİM';
        }
        return '%' . rtrim(rtrim(number_format($this->promo_discount, 2, '.', ''), '0'), '.') . ' İNDİRİM';
    }

    /**
     * Verilen sepet tutarı için kargo ücretini döner.
     * null => ücretsiz, decimal => ücretli
     */
    public function shippingCostFor(float $cartTotal = 0): ?float
    {
        return match ($this->shipping_type) {
            'free'       => null,
            'paid'       => (float) ($this->shipping_cost ?? 0),
            'free_above' => $cartTotal >= (float) ($this->free_shipping_threshold ?? 0)
                                ? null
                                : (float) ($this->shipping_cost ?? 0),
            default      => null,
        };
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
