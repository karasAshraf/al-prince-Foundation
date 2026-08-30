<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsFrontController extends Controller
{
    public function index()
    {
        $news = News::published()->with('media')->latest()->paginate(12);
        return view('frontend.news.index', compact('news'));
    }

    public function show(News $news)
    {
        abort_if(!$news->is_active, 404);
        $news->loadMissing(['author', 'seoMeta']);
        return view('frontend.news.show', compact('news'));
    }
}
