<?php

namespace App\Models\UserModels;

class Admin extends User
{
    // Chỉ lấy các bản ghi role = 'admin'
    protected static function booted()
    {
        static::addGlobalScope('only_admin', fn($q) => $q->where('role', 'admin'));
    }
}
