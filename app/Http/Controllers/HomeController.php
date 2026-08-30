<?php

namespace App\Http\Controllers;

use App\Models\HomePageSection;
use App\Models\News;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(): View
    {
        $sections = Cache::remember('home.active_sections', 3600, function () {
            return HomePageSection::with('media')->active()->get();
        });

        $latestNews = Cache::remember('home.latest_news', 3600, function () {
            return News::published()->with('media')->latest('published_at')->take(3)->get();
        });

        $services = Cache::remember('home.active_services', 3600, function () {
            return Service::active()->with('media')->get();
        });

        $partners = Cache::remember('home.active_partners', 3600, function () {
            return \App\Models\Partner::active()->with('media')->get();
        });

        return view('frontend.home.index', compact('sections', 'latestNews', 'services', 'partners'));
    }
}

