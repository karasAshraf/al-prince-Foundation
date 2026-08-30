<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyResponseRequest;
use App\Models\Survey;
use App\Services\SurveyService;

class SurveyResponseController extends Controller
{
    public function __construct(protected SurveyService $service) {}

    public function create(Survey $survey)
    {
        abort_if(!$survey->is_active, 404);
        return view('frontend.surveys.show', compact('survey'));
    }

    public function store(SurveyResponseRequest $request)
    {
        $this->service->submitResponse($request->validated());
        return back()->with('success', 'شكرًا لمشاركتك، تم إرسال إجابتك بنجاح');
    }
}