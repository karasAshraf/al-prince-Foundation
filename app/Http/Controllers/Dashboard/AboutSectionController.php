<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutSectionRequest;
use App\Models\AboutSection;
use App\Services\AboutSectionService;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function __construct(protected AboutSectionService $service) {}

    public function index(Request $request)
    {
        $sections = $this->service->list($request->only('status'));
        return view('dashboard.about-sections.index', compact('sections'));
    }

    public function create()
    {
        return view('dashboard.about-sections.create', ['aboutSection' => new AboutSection()]);
    }

    public function store(AboutSectionRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.about-sections.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function show(AboutSection $aboutSection)
    {
        return view('dashboard.about-sections.show', ['item' => $aboutSection]);
    }

    public function edit(AboutSection $aboutSection)
    {
        $aboutSection->load('seoMeta');
        return view('dashboard.about-sections.edit', compact('aboutSection'));
    }

    public function update(AboutSectionRequest $request, AboutSection $aboutSection)
    {
        $this->service->update($aboutSection, $request->validated());
        return redirect()->route('dashboard.about-sections.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(AboutSection $aboutSection)
    {
        $this->service->delete($aboutSection);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, AboutSection $aboutSection)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$aboutSection->is_active;
        $aboutSection->is_active = $newStatus;
        $aboutSection->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$aboutSection->is_active,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }
}