<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chair extends Model
{
    use HasFactory;

    protected $fillable = ['theater_id','vertical_row','horizontal_row','seat_type','status'];

    public function theater()
    {
        return $this->belongsTo(MovieTheater::class, 'theater_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
