<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\Service;
use App\Models\Industry;
use App\Models\Survey;
use App\Models\User;

use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $newsCount       = Cache::remember('dashboard.news_count', 3600, fn() => News::count());
        $servicesCount   = Cache::remember('dashboard.services_count', 3600, fn() => Service::count());
        $industriesCount = Cache::remember('dashboard.industries_count', 3600, fn() => Industry::count());
        $usersCount      = Cache::remember('dashboard.users_count', 3600, fn() => User::count());

        $recentMessages = ContactMessage::latest()->take(5)->get(['id', 'name', 'message', 'is_read', 'created_at']);
        $latestSurveys  = Survey::latest()->take(5)->get(['id', 'title_ar', 'is_active']);

        return view('dashboard.home', compact(
            'newsCount',
            'servicesCount',
            'industriesCount',
            'usersCount',
            'recentMessages',
            'latestSurveys'
        ));
    }

    public function analytics()
    {
        $newsCount       = News::count();
        $servicesCount   = Service::count();
        $industriesCount = Industry::count();
        $surveysCount    = Survey::count();
        $responsesCount  = \App\Models\SurveyResponse::count();
        $messagesCount   = ContactMessage::count();
        $usersCount      = User::count();

        // Database-agnostic collection grouping by month (Y-m)
        $news       = News::all();
        $industries = Industry::all();
        $services   = Service::all();
        $responses  = \App\Models\SurveyResponse::all();

        $newsMonths       = $news->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $industriesMonths = $industries->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $servicesMonths   = $services->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $responsesMonths  = $responses->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');

        $minDate = collect([
            News::min('created_at'),
            Industry::min('created_at'),
            Service::min('created_at'),
            \App\Models\SurveyResponse::min('created_at')
        ])->filter()->min();

        $start = $minDate ? \Carbon\Carbon::parse($minDate)->startOfMonth() : now()->subMonths(5)->startOfMonth();
        $end = now()->startOfMonth();

        if ($start->diffInMonths($end) < 5) {
            $start = now()->subMonths(5)->startOfMonth();
        }

        $allMonths = collect();
        while ($start <= $end) {
            $allMonths->push($start->format('Y-m'));
            $start->addMonth();
        }

        $contentTrend = [];
        $responsesTrend = [];

        foreach ($allMonths as $month) {
            $nVal  = isset($newsMonths[$month]) ? $newsMonths[$month]->count() : 0;
            $iVal  = isset($industriesMonths[$month]) ? $industriesMonths[$month]->count() : 0;
            $sVal  = isset($servicesMonths[$month]) ? $servicesMonths[$month]->count() : 0;
            
            $contentTrend[$month] = $nVal + $iVal + $sVal;
            $responsesTrend[$month] = isset($responsesMonths[$month]) ? $responsesMonths[$month]->count() : 0;
        }

        return view('dashboard.analytics', compact(
            'newsCount',
            'servicesCount',
            'industriesCount',
            'surveysCount',
            'responsesCount',
            'messagesCount',
            'usersCount',
            'allMonths',
            'contentTrend',
            'responsesTrend'
        ));
    }
}
