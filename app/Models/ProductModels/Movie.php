<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title','duration_min','genre','rating','release_date'];

    protected $casts = ['release_date' => 'date'];

    public function Showtime()
    {
        return $this->hasMany(Showtime::class);
    }
}
