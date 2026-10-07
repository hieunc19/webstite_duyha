<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureSupportContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_type',
        'field_name',
        'name',
        'role',
        'phone',
        'avatar',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
