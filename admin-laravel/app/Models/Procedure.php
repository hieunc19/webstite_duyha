<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'category',
        'category_text',
        'desc',
        'fee',
        'agency',
        'docs',
        'attachment',
        'download_url',
        'sort_order',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'docs' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function getDocsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $nextId = (static::max('id') ?? 0) + 1;
                $model->code = 'TTHC-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });

        static::saving(function ($model) {
            if ($model->category) {
                $catName = \App\Models\ProcedureCategory::where('slug', $model->category)->value('name');
                if ($catName) {
                    $model->category_text = $catName;
                } else {
                    $categoryMap = [
                        'residence' => 'Cư trú & Định danh điện tử',
                        'vneid'     => 'Định danh VNeID',
                        'civil'     => 'Hộ tịch & Chứng thực',
                        'land'      => 'Đất đai & Xây dựng',
                        'social'    => 'An sinh xã hội & Người có công',
                        'other'     => 'Lĩnh vực khác',
                    ];
                    $model->category_text = $categoryMap[$model->category] ?? 'Thủ tục hành chính';
                }
            }
        });

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
