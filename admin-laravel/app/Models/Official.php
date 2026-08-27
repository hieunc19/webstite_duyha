<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'phone',
        'neighborhood_name',
        'avatar_color',
        'avatar',
        'department',
        'status',
    ];

    public function getNeighborhoodNameAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }
        
        $items = is_array($value) ? $value : array_map('trim', explode(',', (string) $value));
        $result = [];
        $departments = \App\Models\Department::pluck('name')->toArray();

        foreach ($items as $item) {
            if (empty($item)) continue;
            if (!str_starts_with($item, 'TDP ') && !str_starts_with($item, 'Tổ dân phố ') && !in_array($item, $departments)) {
                $result[] = "TDP {$item}";
            } else {
                $result[] = $item;
            }
        }
        return array_values(array_unique($result));
    }

    public function setNeighborhoodNameAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['neighborhood_name'] = implode(', ', array_filter($value));
        } else {
            $this->attributes['neighborhood_name'] = $value;
        }
    }

    protected static function booted(): void
    {
        static::saving(function (Official $official) {
            if (!empty($official->department)) {
                $dep = \App\Models\Department::where('code', $official->department)->first();
                if ($dep && empty($official->neighborhood_name)) {
                    $official->neighborhood_name = [$dep->name];
                }
            } elseif (!empty($official->neighborhood_name)) {
                $rawNames = $official->neighborhood_name;
                $deptNames = is_array($rawNames) ? $rawNames : array_map('trim', explode(',', (string) $rawNames));
                foreach ($deptNames as $name) {
                    $dep = \App\Models\Department::where('name', trim($name))->first();
                    if ($dep) {
                        $official->department = $dep->code;
                        break;
                    }
                }
            }
        });

        static::saved(function (Official $official) {
            // Auto-dump JSON files for static fallback
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });

        static::deleted(function (Official $official) {
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });
    }
}
