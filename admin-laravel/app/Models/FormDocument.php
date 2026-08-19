<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'category',
        'category_text',
        'agency',
        'fee',
        'file_path',
        'download_url',
        'steps',
        'docs',
        'notes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'steps' => 'array',
        'docs' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getStepsAttribute($value)
    {
        if (is_null($value)) return [];
        if (is_array($value)) return $value;
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) $decoded = json_decode($decoded, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function getDocsAttribute($value)
    {
        if (is_null($value)) return [];
        if (is_array($value)) return $value;
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) $decoded = json_decode($decoded, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!empty($model->category)) {
                try {
                    $cat = \App\Models\ProcedureCategory::where('slug', $model->category)->first();
                    if ($cat) {
                        $model->category_text = $cat->name;
                    } else {
                        $defaults = [
                            'ho_tich' => 'Hộ tịch & Tư pháp',
                            'dat_dai' => 'Địa chính & Đất đai',
                            'chinh_sach' => 'Lao động - TB & Xã hội',
                            'do_thi' => 'Giao thông - Đô thị',
                            'moi_truong' => 'Vệ sinh môi trường',
                            'chung_thuc' => 'Chứng thực & Sao y',
                            'cu_tru' => 'An ninh & Cư trú',
                            'civil' => 'Hộ tịch & Chứng thực',
                            'land' => 'Đất đai & Xây dựng',
                            'social' => 'An sinh xã hội & Người có công',
                            'residence' => 'Cư trú & Định danh điện tử',
                            'vneid' => 'Định danh VNeID',
                        ];
                        $model->category_text = $defaults[$model->category] ?? $model->category;
                    }
                } catch (\Throwable $e) {
                    if (empty($model->category_text)) {
                        $model->category_text = 'Thủ tục hành chính';
                    }
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
