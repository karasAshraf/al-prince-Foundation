<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndustryRequest;
use App\Models\Industry;
use App\Services\IndustryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndustryController extends Controller
{
    public function __construct(protected IndustryService $service) {}

    public function index(Request $request)
    {
        $industries = $this->service->list($request->only('is_active'));
        return view('dashboard.industries.index', compact('industries'));
    }

    public function create()
    {
        return view('dashboard.industries.create', ['industryItem' => new Industry()]);
    }

    public function store(IndustryRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.industries.index')->with('success', 'تم إضافة القطاع بنجاح');
    }

    public function show(Industry $industry)
    {
        return view('dashboard.industries.show', compact('industry'));
    }

    public function edit(Industry $industry)
    {
        $industry->load('seoMeta');
        return view('dashboard.industries.edit', compact('industry'));
    }

    public function update(IndustryRequest $request, Industry $industry)
    {
        $this->service->update($industry, $request->validated());
        return redirect()->route('dashboard.industries.index')->with('success', 'تم تحديث القطاع بنجاح');
    }

    public function destroy(Industry $industry)
    {
        $this->service->delete($industry);
        return back()->with('success', 'تم حذف القطاع بنجاح');
    }

    public function toggleStatus(Request $request, Industry $industry)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$industry->is_active;
        $industry->is_active = $newStatus;
        $industry->save();

        Cache::forget('home.active_industries');

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $industry->is_active,
            'message'   => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
