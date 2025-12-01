<?php

namespace App\Models\ProductModels;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    
    protected $fillable = [
        'title',
        'description',
        'image'
    ];
}