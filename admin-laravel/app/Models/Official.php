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
        foreach ($items as $item) {
            if (empty($item)) continue;
            if (!str_starts_with($item, 'TDP ') && !str_starts_with($item, 'Tổ dân phố ') && !in_array($item, ['Công an Phường Duy Hà', 'UBND Phường Duy Hà', 'Đảng ủy Phường Duy Hà', 'Trung tâm Phục vụ Hành chính công'])) {
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
        static::saved(function (Official $official) {
            $oldName = $official->getOriginal('name') ?: $official->name;
            
            // Parse assigned TDP names
            $rawNames = $official->neighborhood_name; // Returns array via accessor
            if (!is_array($rawNames)) {
                $rawNames = array_map('trim', explode(',', (string) $rawNames));
            }

            $targetNames = [];
            foreach ($rawNames as $item) {
                if (empty($item)) continue;
                $clean = trim(str_replace(['TDP ', 'Tổ dân phố '], '', $item));
                if (!empty($clean)) {
                    $targetNames[] = $clean;
                    $targetNames[] = "TDP " . $clean;
                }
            }
            $targetNames = array_unique(array_filter($targetNames));

            // 1. Clear leader for TDPs previously assigned to this officer but no longer selected
            Neighborhood::where('type', 'new')
                ->where(function ($q) use ($official, $oldName) {
                    $q->where('leader_name', $official->name)
                      ->orWhere('leader_name', $oldName);
                })
                ->whereNotIn('name', $targetNames)
                ->update([
                    'leader_name' => null,
                    'leader_phone' => null,
                ]);

            // 2. Assign leader_name and leader_phone to currently assigned TDPs
            if (!empty($targetNames) && ($official->department === 'cskv' || !empty($official->neighborhood_name))) {
                Neighborhood::where('type', 'new')
                    ->whereIn('name', $targetNames)
                    ->update([
                        'leader_name' => $official->name,
                        'leader_phone' => $official->phone,
                    ]);
            }

            // 3. Auto-dump JSON files for static fallback
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });

        static::deleted(function (Official $official) {
            Neighborhood::where('type', 'new')
                ->where('leader_name', $official->name)
                ->update([
                    'leader_name' => null,
                    'leader_phone' => null,
                ]);

            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });
    }
}
