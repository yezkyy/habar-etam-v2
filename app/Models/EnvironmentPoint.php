<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'info_type',
        'severity',
        'description',
        'location_name',
        'location_district',
        'latitude',
        'longitude',
        'source',
        'status_condition',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
