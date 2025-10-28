<?php
namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;

class MovieRating extends Model
{
    protected $table = 'movie_ratings';
    protected $primaryKey = 'ratingID';
    public $timestamps = true;

    protected $fillable = ['movieID', 'userID', 'stars'];
     public function movie()
    {
        return $this->belongsTo(Movie::class, 'movieID');
    }
}

