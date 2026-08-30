<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class ActivityFrontController extends Controller
{
    public function index()
    {
        $activities = Activity::active()->with('media')->paginate(12);
        return view('frontend.activities.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        return redirect()->route('activities.index');
    }
}