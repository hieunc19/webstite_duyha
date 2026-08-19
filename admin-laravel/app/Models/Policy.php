<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'category',
        'agency',
        'issue_date',
        'status',
        'summary',
        'highlights',
        'download_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'highlights' => 'array',
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
