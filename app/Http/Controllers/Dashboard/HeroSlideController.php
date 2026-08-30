<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSlideRequest;
use App\Models\HeroSlide;
use App\Services\HeroSlideService;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    public function __construct(protected HeroSlideService $service) {}

    public function index(Request $request)
    {
        $slides = $this->service->list($request->only('placement'));
        return view('dashboard.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('dashboard.hero-slides.create', ['slide' => new HeroSlide()]);
    }

    public function store(HeroSlideRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.hero-slides.index')->with('success', __('dashboard.hero_slides.success_create'));
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('dashboard.hero-slides.edit', ['slide' => $heroSlide]);
    }

    public function update(HeroSlideRequest $request, HeroSlide $heroSlide)
    {
        $this->service->update($heroSlide, $request->validated());
        return redirect()->route('dashboard.hero-slides.index')->with('success', __('dashboard.hero_slides.success_update'));
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $this->service->delete($heroSlide);
        return back()->with('success', __('dashboard.hero_slides.success_delete'));
    }

    public function toggleStatus(Request $request, HeroSlide $heroSlide)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$heroSlide->is_active;
        $heroSlide->is_active = $newStatus;
        $heroSlide->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$heroSlide->is_active,
            'message' => __('dashboard.hero_slides.success_update'),
        ]);
    }
}
