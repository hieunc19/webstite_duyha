<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'color',
        'description',
        'sort_order',
        'status',
    ];

    public function officials(): HasMany
    {
        return $this->hasMany(Official::class, 'department', 'code');
    }

    protected static function booted(): void
    {
        static::saving(function (Department $department) {
            if (empty($department->code) && !empty($department->name)) {
                $baseSlug = \Illuminate\Support\Str::slug($department->name, '_');
                $slug = $baseSlug ?: 'phong_ban';
                $count = 1;
                while (Department::where('code', $slug)->where('id', '!=', $department->id ?? 0)->exists()) {
                    $slug = "{$baseSlug}_{$count}";
                    $count++;
                }
                $department->code = $slug;
            }
        });

        static::saved(function (Department $department) {
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });

        static::deleted(function (Department $department) {
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });
    }
}
