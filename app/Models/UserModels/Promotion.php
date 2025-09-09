<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['promotion_no','description','condition','start_time','expired_time'];
    protected $casts = ['start_time' => 'datetime','expired_time' => 'datetime'];
    public function customerPromotions()
    {
        return $this->hasMany(CustomerPromotion::class);
    }
}
