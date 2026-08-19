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
        'file_path',
        'file_name',
        'file_size',
        'description',
        'period_date',
        'type',
        'neighborhood_id',
        'address',
        'representative_name',
        'phone',
        'benefit_details',
        'celebration_event_id',
        'status',
    ];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return url('storage/' . ltrim($this->file_path, '/'));
    }

    public function celebrationEvent(): BelongsTo
    {
        return $this->belongsTo(CelebrationEvent::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    protected static function booted(): void
    {
        static::saved(function () {
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });

        static::deleted(function () {
            try {
                $dumpScript = base_path('dump_to_json.php');
                if (file_exists($dumpScript)) {
                    exec("php " . escapeshellarg($dumpScript));
                }
            } catch (\Throwable $e) {}
        });
    }
}
