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
    protected $fillable = ['user_id','customer_name','customer_point'];
    //
    public function user()
    {
    return $this->belongsTo(\App\Models\UserModels\User::class, 'user_id', 'id');
    }
}
