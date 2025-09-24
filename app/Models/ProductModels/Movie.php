<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModels\Showtime;

class Movie extends Model
{
    protected $table = 'movies';
    protected $primaryKey = 'movieID';       
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'durationMin',
        'genre',
        'age_rating',
        'format',
        'releaseDate',
        'image',
        'description',
    ];

    protected $dates = ['releaseDate'];


    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'movieID', 'movieID');
    }
}
