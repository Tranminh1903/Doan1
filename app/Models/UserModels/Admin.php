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
    
    protected $fillable = ['user_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['q'])) {
            $query->where(function($q) use ($filters) {
                $q->where('username', 'like', '%' . $filters['q'] . '%')
                ->orWhere('email', 'like', '%' . $filters['q'] . '%');
            });
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
