<?php

namespace App\Models\UserModels;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['username','email','password','phone','role','sex','birthday'];
    protected $hidden = ['password','token'];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isCustomers(): bool{ return $this->role === 'customers'; }
}