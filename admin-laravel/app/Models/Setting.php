<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'name',
        'value',
        'label',
        'group',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::saved(function ($setting) {
            $scriptPath = base_path('dump_to_json.php');
            if (file_exists($scriptPath)) {
                @exec("php {$scriptPath}");
            }
        });

        static::deleted(function ($setting) {
            $scriptPath = base_path('dump_to_json.php');
            if (file_exists($scriptPath)) {
                @exec("php {$scriptPath}");
            }
        });
    }

    public static function getValue(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function getLabel(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->label : $default;
    }
}
