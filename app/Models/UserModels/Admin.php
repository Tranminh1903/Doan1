<?php

namespace App\Models\UserModels;

class Admin extends User
{
    //
    protected $table = 'admin';    
    protected $primaryKey = 'user_id';
    public $incrementing = false;   
    protected $keyType = 'int';
    //
    
    protected $fillable = ['user_id','role'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
