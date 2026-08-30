<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomePageSectionRequest;
use App\Models\HomePageSection;
use App\Services\HomePageSectionService;
use Illuminate\Http\Request;

class HomePageSectionController extends Controller
{
    public function __construct(protected HomePageSectionService $service) {}

    public function index()
    {
        $sections = $this->service->list();
        return view('dashboard.home-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('dashboard.home-sections.create', ['section' => new HomePageSection()]);
    }

    public function store(HomePageSectionRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.home-sections.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(HomePageSection $homeSection)
    {
        return view('dashboard.home-sections.show', ['section' => $homeSection]);
    }

    public function edit(HomePageSection $homeSection)
    {
        return view('dashboard.home-sections.edit', ['section' => $homeSection]);
    }

    public function update(HomePageSectionRequest $request, HomePageSection $homeSection)
    {
        $this->service->update($homeSection, $request->validated());
        return redirect()->route('dashboard.home-sections.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(HomePageSection $homeSection)
    {
        $this->service->delete($homeSection);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function reorder(Request $request)
    {
        $this->service->reorder($request->input('ordered_ids', []));
        return response()->json(['success' => true]);
    }

    public function toggleStatus(Request $request, HomePageSection $homeSection)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$homeSection->is_active;
        $homeSection->is_active = $newStatus;
        $homeSection->save();

        \Illuminate\Support\Facades\Cache::forget('home.active_sections');

        return response()->json([
            'success' => true,
            'is_active' => (bool)$homeSection->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
