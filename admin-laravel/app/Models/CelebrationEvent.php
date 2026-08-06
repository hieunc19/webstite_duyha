<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CelebrationEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'month',
        'day',
        'description',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'month' => 'integer',
        'day' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function meritoriousFamilies(): HasMany
    {
        return $this->hasMany(MeritoriousFamily::class);
    }
}
