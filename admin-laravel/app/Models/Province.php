<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['code', 'name', 'full_name', 'code_name', 'latitude', 'longitude'];

    public function administrativeUnits()
    {
        return $this->hasMany(AdministrativeUnit::class, 'province_code', 'code');
    }
}
