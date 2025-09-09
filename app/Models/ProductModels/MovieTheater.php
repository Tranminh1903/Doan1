<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovieTheater extends Model
{
    use HasFactory;

    protected $fillable = ['name','capacity','type'];

    public function Showtime()
    {
        return $this->hasMany(Showtime::class, 'theater_id');
    }

    public function chairs()
    {
        return $this->hasMany(Chair::class, 'theater_id');
    }
}
