<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaLibraryRequest;
use App\Models\MediaLibrary;
use App\Services\MediaLibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MediaLibraryController extends Controller
{
    public function __construct(protected MediaLibraryService $service) {}

    public function index(Request $request)
    {
        $items = $this->service->list($request->only('is_active', 'category', 'search'));
        $categories = $this->service->categories();
        return view('dashboard.media-library.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = $this->service->categories();
        return view('dashboard.media-library.form', [
            'item' => new MediaLibrary(),
            'categories' => $categories,
        ]);
    }

    public function store(MediaLibraryRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.media-library.index')->with('success', 'تم الإضافة بنجاح');
    }

    public function edit(MediaLibrary $mediaLibrary)
    {
        $mediaLibrary->load('seoMeta');
        $categories = $this->service->categories();
        return view('dashboard.media-library.form', [
            'item' => $mediaLibrary,
            'categories' => $categories,
        ]);
    }

    public function update(MediaLibraryRequest $request, MediaLibrary $mediaLibrary)
    {
        $this->service->update($mediaLibrary, $request->validated());
        return redirect()->route('dashboard.media-library.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy(MediaLibrary $mediaLibrary)
    {
        $this->service->delete($mediaLibrary);
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function toggleStatus(Request $request, MediaLibrary $mediaLibrary)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$mediaLibrary->is_active;
        $mediaLibrary->is_active = $newStatus;
        $mediaLibrary->save();

        Cache::forget('media_library.active_items');

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $mediaLibrary->is_active,
            'message'   => 'تم تحديث الحالة بنجاح',
        ]);
    }
}
