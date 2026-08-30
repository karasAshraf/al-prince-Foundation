<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\Program;
use App\Models\Project;
use App\Models\Survey;
use App\Models\User;

use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $newsCount = Cache::remember('dashboard.news_count', 3600, fn() => News::count());
        $programsCount = Cache::remember('dashboard.programs_count', 3600, fn() => Program::count());
        $projectsCount = Cache::remember('dashboard.projects_count', 3600, fn() => Project::count());
        $usersCount = Cache::remember('dashboard.users_count', 3600, fn() => User::count());

        $recentMessages = ContactMessage::latest()->take(5)->get(['id', 'name', 'message', 'is_read', 'created_at']);
        $latestSurveys  = Survey::latest()->take(5)->get(['id', 'title_ar', 'is_active']);

        return view('dashboard.home', compact(
            'newsCount',
            'programsCount',
            'projectsCount',
            'usersCount',
            'recentMessages',
            'latestSurveys'
        ));
    }

    public function analytics()
    {
        $newsCount = News::count();
        $programsCount = Program::count();
        $projectsCount = Project::count();
        $surveysCount = Survey::count();
        $responsesCount = \App\Models\SurveyResponse::count();
        $messagesCount = ContactMessage::count();
        $usersCount = User::count();

        // Database-agnostic collection grouping by month (Y-m)
        $news = News::all();
        $projects = Project::all();
        $programs = Program::all();
        $responses = \App\Models\SurveyResponse::all();

        $newsMonths = $news->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $projectsMonths = $projects->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $programsMonths = $programs->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');
        $responsesMonths = $responses->groupBy(fn($item) => $item->created_at ? $item->created_at->format('Y-m') : 'Unknown');

        $minDate = collect([
            News::min('created_at'),
            Project::min('created_at'),
            Program::min('created_at'),
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
            $nVal = isset($newsMonths[$month]) ? $newsMonths[$month]->count() : 0;
            $pVal = isset($projectsMonths[$month]) ? $projectsMonths[$month]->count() : 0;
            $prVal = isset($programsMonths[$month]) ? $programsMonths[$month]->count() : 0;
            
            $contentTrend[$month] = $nVal + $pVal + $prVal;
            $responsesTrend[$month] = isset($responsesMonths[$month]) ? $responsesMonths[$month]->count() : 0;
        }

        return view('dashboard.analytics', compact(
            'newsCount',
            'programsCount',
            'projectsCount',
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
