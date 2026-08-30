<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) {
                    return null;
                }
                $decoded = json_decode($value, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
            },
            set: function ($value) {
                return json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        );
    }

    public static function group(string $group): array
    {
        return Cache::rememberForever("settings.{$group}", function () use ($group) {
            $settings = static::where('group', $group)->get();
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }
            return $result;
        });
    }

    public static function get(string $group, string $key, mixed $default = null): mixed
    {
        $settings = static::group($group); // reuses Cache::rememberForever result
        return $settings[$key] ?? $default;
    }

    public static function setMany(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget("settings.{$group}");
    }

    public static function set(string $group, string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("settings.{$group}");
    }
}