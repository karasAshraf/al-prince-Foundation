<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActivityController extends Controller
{
    public function __construct(protected ActivityService $service) {}

    public function index(Request $request)
    {
        $activities = $this->service->list($request->only('is_active'));
        return view('dashboard.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('dashboard.activities.create', ['activityItem' => new Activity()]);
    }

    public function store(ActivityRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.activities.index')->with('success', 'تم إضافة النشاط بنجاح');
    }

    public function show(Activity $activity)
    {
        return view('dashboard.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $activity->load('seoMeta');
        return view('dashboard.activities.edit', compact('activity'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $this->service->update($activity, $request->validated());
        return redirect()->route('dashboard.activities.index')->with('success', 'تم تحديث النشاط بنجاح');
    }

    public function destroy(Activity $activity)
    {
        $this->service->delete($activity);
        return back()->with('success', 'تم حذف النشاط بنجاح');
    }

    public function toggleStatus(Request $request, Activity $activity)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$activity->is_active;
        $activity->is_active = $newStatus;
        $activity->save();

        Cache::forget('home.active_activities');

        return response()->json([
            'success'   => true,
            'is_active' => (bool) $activity->is_active,
            'message'   => 'تم تحديث الحالة بنجاح',
        ]);
    }
}