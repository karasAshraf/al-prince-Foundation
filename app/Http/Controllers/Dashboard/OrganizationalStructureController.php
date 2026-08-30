<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\OrganizationalStructureService;
use Illuminate\Http\Request;

class OrganizationalStructureController extends Controller
{
    public function __construct(
        protected OrganizationalStructureService $service
    ) {}

    public function edit()
    {
        $structure = $this->service->getFirstRecord();

        return view('dashboard.organizational-structure.edit', [
            'structure' => $structure,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image_ar' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'is_active' => 'nullable|boolean',
            'remove_image_ar' => 'nullable|boolean',
            'remove_image_en' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $this->service->update($validated);

        return back()->with('success', 'تم تحديث الهيكل التنظيمي بنجاح');
    }
}
