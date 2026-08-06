<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeritoriousFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'neighborhood_id',
        'address',
        'representative_name',
        'phone',
        'benefit_details',
        'celebration_event_id',
        'status',
    ];

    public function celebrationEvent(): BelongsTo
    {
        return $this->belongsTo(CelebrationEvent::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }
}
