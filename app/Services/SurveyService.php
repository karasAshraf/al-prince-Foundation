<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Carbon\Carbon;

class SurveyService extends BaseService
{
    /**
     * The foundation's operating timezone, used to interpret date-only admin
     * inputs as the start/end of that calendar day in local time before
     * converting to UTC for storage.
     */
    private string $tz;

    public function __construct()
    {
        $this->tz = config('foundation.local_timezone', 'Asia/Riyadh');
    }

    public function list(array $filters = [])
    {
        return Survey::query()
            ->withCount('responses')
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', fn($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): Survey
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['external_link'], $data['remove_media']);

        $this->normalizeDateFields($data);

        $survey = Survey::create($data);

        $imageFile = ($image instanceof \Illuminate\Http\UploadedFile) ? $image : null;

        $this->attachMedia(
            $survey,
            $imageFile,
            $externalLink,
            $removeMedia,
            'survey_images'
        );

        return $survey;
    }

    public function update(Survey $survey, array $data): Survey
    {
        $image = $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['image'], $data['external_link'], $data['remove_media']);

        $this->normalizeDateFields($data);

        $survey->update($data);

        $imageFile = ($image instanceof \Illuminate\Http\UploadedFile) ? $image : null;

        $this->attachMedia(
            $survey,
            $imageFile,
            $externalLink,
            $removeMedia,
            'survey_images'
        );

        return $survey;
    }

    public function delete(Survey $survey): bool
    {
        return $survey->delete();
    }

    public function submitResponse(array $data): SurveyResponse
    {
        return SurveyResponse::create($data);
    }

    public function responsesCount(Survey $survey): int
    {
        return $survey->responses()->count();
    }

    /**
     * Normalize date-only admin inputs to UTC-stored timestamps.
     *
     * The admin dashboard uses <input type="date"> (date-only, no time).
     * Without this, Laravel stores "2026-08-21" as 2026-08-21 00:00:00 UTC,
     * which is 03:00 AM Asia/Riyadh — meaning content scheduled to start
     * "today" is hidden for the first 3 hours of the local business day.
     *
     * Fix:
     *   starts_at → start of that calendar day in local tz → UTC
     *   ends_at   → end of that calendar day in local tz  → UTC
     *
     * This way the DB still stores UTC and all existing now() comparisons in
     * scopeActive()/isAvailable() continue to work correctly unchanged.
     *
     * @param  array<string, mixed>  $data  validated request data (by reference)
     */
    private function normalizeDateFields(array &$data): void
    {
        if (!empty($data['starts_at'])) {
            $data['starts_at'] = Carbon::parse($data['starts_at'], $this->tz)
                ->startOfDay()
                ->utc();
        }

        if (!empty($data['ends_at'])) {
            // endOfDay (23:59:59) so the survey remains visible all day on
            // the end date in local time, expiring at midnight going into
            // the following day. (Confirmed business rule.)
            $data['ends_at'] = Carbon::parse($data['ends_at'], $this->tz)
                ->endOfDay()
                ->utc();
        }
    }
}


