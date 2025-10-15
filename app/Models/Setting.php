<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key', 'value', 'group', 'type',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();
            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, $value, ?string $group = null, ?string $type = null): void
    {
        $setting = static::query()->firstOrNew(['key' => $key]);
        $setting->value = is_array($value) || is_object($value) ? json_encode($value) : $value;
        if ($group !== null) {
            $setting->group = $group;
        }
        if ($type !== null) {
            $setting->type = $type;
        }
        $setting->save();
        Cache::forget("setting:{$key}");
        Cache::forever("setting:{$key}", $setting->value);
    }

    public static function getJson(string $key, $default = [])
    {
        $raw = static::get($key);
        if (!$raw) return $default;
        try {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}