<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use App\Services\SurveyService;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function __construct(protected SurveyService $service) {}

    public function index(Request $request)
    {
        $surveys = $this->service->list($request->only('is_active'));
        return view('dashboard.surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('dashboard.surveys.create', ['survey' => new Survey()]);
    }

    public function store(SurveyRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('dashboard.surveys.index')->with('success', __('dashboard.surveys.success_created'));
    }

    public function show(Survey $survey)
    {
        $survey->loadCount('responses');
        return view('dashboard.surveys.show', compact('survey'));
    }

    public function edit(Survey $survey)
    {
        return view('dashboard.surveys.edit', compact('survey'));
    }

    public function update(SurveyRequest $request, Survey $survey)
    {
        $this->service->update($survey, $request->validated());
        return redirect()->route('dashboard.surveys.index')->with('success', __('dashboard.surveys.success_updated'));
    }

    public function destroy(Survey $survey)
    {
        $this->service->delete($survey);
        return back()->with('success', __('dashboard.surveys.success_deleted'));
    }

    public function responses(Survey $survey)
    {
        $responses = $survey->responses()->latest()->paginate(20);
        return view('dashboard.surveys.responses', compact('survey', 'responses'));
    }

    public function analysis(Survey $survey)
    {
        $survey->loadCount('responses');
        $responses = $survey->responses()->latest()->paginate(15);
        $questions = $survey->questions ?? [];
        $chartsData = [];

        $allResponses = $survey->responses;

        foreach ($questions as $index => $q) {
            $qType = $q['type'] ?? 'text';
            if ($qType === 'rating') {
                $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                foreach ($allResponses as $resp) {
                    $ans = $resp->answers[$index] ?? null;
                    if ($ans !== null && isset($distribution[(int)$ans])) {
                        $distribution[(int)$ans]++;
                    }
                }
                $chartsData[$index] = $distribution;
            } elseif ($qType === 'choice' || $qType === 'select' || $qType === 'checkbox') {
                $locale = app()->getLocale();
                $distribution = [];
                $opts = $q['options'] ?? [];
                
                // Initialize distribution with localized option labels
                foreach ($opts as $optIndex => $opt) {
                    $optLabel = is_array($opt) ? ($locale === 'ar' ? ($opt['ar'] ?? $opt['en'] ?? '') : ($opt['en'] ?? $opt['ar'] ?? '')) : $opt;
                    $distribution[$optLabel] = 0;
                }

                // Helper to resolve answer value (index or string) to localized label
                $resolveOptionLabel = function ($ansVal) use ($opts, $locale) {
                    if (is_numeric($ansVal) && isset($opts[$ansVal])) {
                        $optObj = $opts[$ansVal];
                        return is_array($optObj) ? ($locale === 'ar' ? ($optObj['ar'] ?? $optObj['en'] ?? '') : ($optObj['en'] ?? $optObj['ar'] ?? '')) : $optObj;
                    }
                    
                    // Match historic string answer to options
                    foreach ($opts as $optObj) {
                        if (is_array($optObj)) {
                            if (strcasecmp($ansVal, $optObj['ar'] ?? '') === 0 || strcasecmp($ansVal, $optObj['en'] ?? '') === 0) {
                                return $locale === 'ar' ? ($optObj['ar'] ?? $optObj['en'] ?? '') : ($optObj['en'] ?? $optObj['ar'] ?? '');
                            }
                        } else {
                            if (strcasecmp($ansVal, $optObj) === 0) {
                                return $optObj;
                            }
                        }
                    }
                    return $ansVal;
                };

                foreach ($allResponses as $resp) {
                    $ans = $resp->answers[$index] ?? null;
                    if ($ans !== null) {
                        if (is_array($ans)) {
                            foreach ($ans as $subAns) {
                                $resolvedLabel = $resolveOptionLabel($subAns);
                                if (isset($distribution[$resolvedLabel])) {
                                    $distribution[$resolvedLabel]++;
                                }
                            }
                        } else {
                            $resolvedLabel = $resolveOptionLabel($ans);
                            if (isset($distribution[$resolvedLabel])) {
                                $distribution[$resolvedLabel]++;
                            }
                        }
                    }
                }
                $chartsData[$index] = $distribution;
            }
        }

        return view('dashboard.surveys.analysis', compact('survey', 'responses', 'questions', 'chartsData'));
    }

    public function toggleStatus(Request $request, Survey $survey)
    {
        $newStatus = $request->has('is_active') ? $request->boolean('is_active') : !$survey->is_active;
        $survey->is_active = $newStatus;
        $survey->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$survey->is_active,
            'message' => __('dashboard.surveys.status_updated'),
        ]);
    }
}
