<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends User
{
    //
    protected $table = 'customers';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = 
    [
        'user_id',
        'customer_name',
        'customer_point',
        'tier',
        'total_order_amount',
        'total_promotions_unused'
    ];

    //Lấy kiểu dữ liệu đúng (ép kiểu)
    protected $casts = 
    [
        'total_order_amount' => 'integer',
        'customer_point' => 'integer',
        'total_promotions_unused' => 'integer',
    ];
    //
    public function user()
    {
    return $this->belongsTo(\App\Models\UserModels\User::class, 'user_id', 'id');
    }
}
