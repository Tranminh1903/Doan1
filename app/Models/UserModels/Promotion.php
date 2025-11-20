<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotion';

    protected $fillable = [
        'code',           
        'type',           
        'value',          
        'limit_count',    
        'used_count',     
        'min_order_value',  
        'min_ticket_quantity',
        'start_date',    
        'end_date',      
        'status',        
        'description',    
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isValid(): bool
    {
        return $this->status === 'active'
            && $this->start_date <= now()
            && $this->end_date >= now()
            && $this->used_count < $this->limit_count;
    }


    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percent') {
            $discount = $total * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        return min($discount, $total);
    }
     public function order()
    {
    return $this->hasOne(Order::class, 'promotion_code', 'code');
    }
}   