<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends User
{
    // Chỉ lấy các bản ghi role = 'user'
    protected static function booted()
    {
        static::addGlobalScope('only_user', fn($q) => $q->where('role', 'user'));
    }

    /** Customer has many Orders */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id'); // FK -> users.id
    }

    /** Customer has many CustomerPromotions */
    public function customerPromotions(): HasMany
    {
        return $this->hasMany(CustomerPromotion::class, 'customer_id'); // FK -> users.id
    }
}
