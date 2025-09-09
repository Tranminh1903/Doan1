<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','action','note','actor_user_id'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
