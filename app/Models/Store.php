<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'description',
        'email', 'phone', 'address', 'is_active',
        'bank_name', 'account_holder', 'iban', 'account_number', 'branch_name', 'branch_code',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
