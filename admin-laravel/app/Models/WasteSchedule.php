<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tdp_name',
        'morning_shift',
        'evening_shift',
        'collection_days',
        'saturday_recycle',
        'main_routes',
        'collection_point',
        'responsible_unit',
        'contact_phone',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'collection_days' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            static::dumpToJson();
        });

        static::deleted(function ($model) {
            static::dumpToJson();
        });
    }

    public static function dumpToJson(): void
    {
        $scriptPath = base_path('dump_to_json.php');
        if (file_exists($scriptPath)) {
            @exec("php {$scriptPath}");
        }
    }
}
