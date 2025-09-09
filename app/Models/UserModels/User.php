<?php

namespace App\Models\UserModels;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['username','email','password','phone','role'];
    protected $hidden = ['password','remember_token'];
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isCustomer(): bool{ return $this->role === 'customers'; }
}
