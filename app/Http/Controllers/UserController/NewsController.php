<?php

namespace App\Http\Controllers\UserController;

use Illuminate\Http\Request;
use App\Models\ProductModels\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest('created_at')->get(); 
        
        return view('news.news', compact('news'));
    }
    public function show($id)
    {
        $news = News::findOrFail($id);
        return view('news.news_detail', compact('news'));
    }
}