<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_code',
        'name',
        'custom_title',
        'custom_subtitle',
        'is_visible',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
        'settings'   => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function ($section) {
            $scriptPath = base_path('dump_to_json.php');
            if (file_exists($scriptPath)) {
                @exec("php {$scriptPath}");
            }
        });

        static::deleted(function ($section) {
            $scriptPath = base_path('dump_to_json.php');
            if (file_exists($scriptPath)) {
                @exec("php {$scriptPath}");
            }
        });
    }
}
