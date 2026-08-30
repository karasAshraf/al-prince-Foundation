<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(protected NewsService $service) {}

    public function index(Request $request)
    {
        $news = $this->service->list($request->only('status', 'search'));
        return view('dashboard.news.index', compact('news'));
    }

    public function create()
    {
        return view('dashboard.news.create', ['news' => new News()]);
    }

    public function store(NewsRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.news.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(News $news)
    {
        return view('dashboard.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        $news->load('seoMeta');
        return view('dashboard.news.edit', compact('news'));
    }

    public function update(NewsRequest $request, News $news)
    {
        $this->service->update($news, $request->validated());
        return redirect()->route('dashboard.news.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(News $news)
    {
        $this->service->delete($news);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, News $news)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$news->is_active;
        $news->is_active = $newStatus;
        $news->save();

        \Illuminate\Support\Facades\Cache::forget('home.latest_news');
        \Illuminate\Support\Facades\Cache::forget('dashboard.news_count');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$news->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
