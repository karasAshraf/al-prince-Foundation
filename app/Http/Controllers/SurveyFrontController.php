<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyResponseRequest;
use App\Models\Survey;
use App\Services\SurveyService;

class SurveyFrontController extends Controller
{
    public function __construct(protected SurveyService $service) {}

    public function index()
    {
        $surveys = Survey::active()->latest()->paginate(12);
        return view('frontend.surveys.index', compact('surveys'));
    }

    public function show(Survey $survey)
    {
        abort_if(!$survey->isAvailable(), 404);
        return view('frontend.surveys.show', compact('survey'));
    }

    public function store(SurveyResponseRequest $request)
    {
        $data = $request->validated();
        $survey = Survey::findOrFail($data['survey_id']);

        if (!$survey->isAvailable()) {
            return back()->with('error', __('frontend.survey_expired_or_inactive'));
        }

        $this->service->submitResponse($data);
        return back()->with('success', __('frontend.survey_submitted_success'));
    }
}
