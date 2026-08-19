<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'group_code',
        'leader_name',
        'leader_phone',
        'households',
        'people',
        'area_ha',
        'status',
        'bi_thu_name',
        'bi_thu_phone',
        'to_truong_name',
        'to_truong_phone',
        'cskv_name',
        'cskv_phone',
        'mat_tan_name',
        'mat_tan_phone',
        'nguoi_cao_tuoi',
        'nguoi_cao_tuoi_phone',
        'phu_nu',
        'phu_nu_phone',
        'nong_dan',
        'nong_dan_phone',
        'ccb',
        'ccb_phone',
        'doan_thanh_nien',
        'doan_thanh_nien_phone',
    ];

    protected $casts = [
        'households' => 'integer',
        'people' => 'integer',
        'area_ha' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function (Neighborhood $n) {
            if (!empty($n->cskv_name)) {
                $n->leader_name = $n->cskv_name;
                $n->leader_phone = $n->cskv_phone;
            } elseif (!empty($n->leader_name)) {
                $n->cskv_name = $n->leader_name;
                $n->cskv_phone = $n->leader_phone;
            }
        });

        static::saved(function () {
            @exec('php ' . base_path('dump_to_json.php') . ' > /dev/null 2>&1 &');
        });
        static::deleted(function () {
            @exec('php ' . base_path('dump_to_json.php') . ' > /dev/null 2>&1 &');
        });
    }
}
