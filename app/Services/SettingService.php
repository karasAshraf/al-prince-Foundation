<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function getGroup(string $group): array
    {
        return Setting::group($group);
    }

    public function saveGroup(string $group, array $data): void
    {
        Setting::setMany($group, $data);
    }
}