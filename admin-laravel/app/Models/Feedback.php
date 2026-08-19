<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'fullname',
        'phone',
        'title',
        'content',
        'status',
        'ip_address',
        'synced_to_sheets'
    ];
}
