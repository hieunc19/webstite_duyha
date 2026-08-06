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
}
