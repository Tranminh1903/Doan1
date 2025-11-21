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
    protected $dates = ['releaseDate'];
    protected $casts = ['is_banner' => 'boolean'];

    protected $fillable = [
        'title',
        'durationMin',
        'genre',
        'age_rating',
        'format',
        'releaseDate',
        'poster',
        'description',
        'is_banner',
        'status',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'movieID', 'movieID');
    }
    
    public function scopeBanner($q) { 
        return $q->where('is_banner', true); 
    }

    public function scopeVisible($q) { 
        return $q->where('status','active'); 
    }
}
