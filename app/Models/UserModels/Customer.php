<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
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
    return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    
}
