<?php

namespace App\Http\Controllers;

use App\Models\Solution;

class SolutionFrontController extends Controller
{
    public function index()
    {
        // Retrieve ONLY the 2 professional category cards:
        // 1. حلول المؤسسة التنموية (ID 2)
        // 2. الحلول الرقمية والفنية (عبر مركز الأثر) (ID 9)
        $solutions = Solution::whereIn('id', [2, 9])
            ->active()
            ->with('media')
            ->orderBy('order')
            ->get();

        return view('frontend.solutions.index', compact('solutions'));
    }

    public function developmental()
    {
        // Retrieve the 7 developmental solutions
        $solutions = Solution::whereIn('id', [3, 4, 5, 6, 7, 8, 13])
            ->active()
            ->with('media')
            ->orderBy('order')
            ->get();

        return view('frontend.solutions.developmental', compact('solutions'));
    }

    public function digitalTechnical()
    {
        // Retrieve the 3 digital/technical solutions
        $solutions = Solution::whereIn('id', [10, 11, 12])
            ->active()
            ->with('media')
            ->orderBy('order')
            ->get();

        return view('frontend.solutions.digital-technical', compact('solutions'));
    }

    public function show(Solution $solution)
    {
        abort_if(!$solution->is_active, 404);

        // Determine correct category back URL
        $backUrl = route('solutions.index');
        $developmentalIds = [3, 4, 5, 6, 7, 8, 13];
        $digitalTechnicalIds = [10, 11, 12];

        if (in_array($solution->id, $developmentalIds)) {
            $backUrl = route('solutions.developmental');
        } elseif (in_array($solution->id, $digitalTechnicalIds)) {
            $backUrl = route('solutions.digital-technical');
        }

        return view('frontend.solutions.show', compact('solution', 'backUrl'));
    }
}