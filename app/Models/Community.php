<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'interest_category',
        'description',
        'activity_schedule',
        'base_location',
        'contact_person',
        'contact_phone',
        'social_media',
        'photo',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
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
