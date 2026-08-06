<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdministrativeUnit extends Model
{
    protected $fillable = ['code', 'name', 'type', 'latitude', 'longitude', 'link', 'boundary_data', 'province_code', 'district_name'];

    protected $casts = [
        'boundary_data' => 'array',
    ];

    public function charityCases()
    {
        return $this->hasMany(CharityCase::class, 'administrative_unit_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}
