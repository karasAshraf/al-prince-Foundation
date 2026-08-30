<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionRequest;
use App\Models\Solution;
use App\Services\SolutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SolutionController extends Controller
{
    public function __construct(protected SolutionService $service) {}

    public function index(Request $request)
    {
        $solutions = $this->service->list($request->only('is_active'));
        return view('dashboard.solutions.index', compact('solutions'));
    }

    public function create()
    {
        return view('dashboard.solutions.create', ['solutionItem' => new Solution()]);
    }

    public function store(SolutionRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.solutions.index')->with('success', 'تم إضافة الحل بنجاح');
    }

    public function show(Solution $solution)
    {
        return view('dashboard.solutions.show', compact('solution'));
    }

    public function edit(Solution $solution)
    {
        $solution->load('seoMeta');
        return view('dashboard.solutions.edit', compact('solution'));
    }

    public function update(SolutionRequest $request, Solution $solution)
    {
        $this->service->update($solution, $request->validated());
        return redirect()->route('dashboard.solutions.index')->with('success', 'تم تحديث الحل بنجاح');
    }

    public function destroy(Solution $solution)
    {
        $this->service->delete($solution);
        return back()->with('success', 'تم حذف الحل بنجاح');
    }

    public function toggleStatus(Request $request, Solution $solution)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$solution->is_active;
        $solution->is_active = $newStatus;
        $solution->save();

        Cache::forget('home.active_solutions');

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $solution->is_active,
            'message'   => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
