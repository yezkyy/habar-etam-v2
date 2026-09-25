<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'organizer',
        'start_date',
        'end_date',
        'start_time',
        'location_name',
        'location_address',
        'latitude',
        'longitude',
        'description',
        'contact_phone',
        'poster_image',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'moderated_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
