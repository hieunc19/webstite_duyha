<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'address',
        'lat',
        'lng',
        'description',
        'image',
        'administrative_unit_id',
        'status',
    ];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double',
    ];

    public function administrativeUnit(): BelongsTo
    {
        return $this->belongsTo(AdministrativeUnit::class, 'administrative_unit_id');
    }
}
