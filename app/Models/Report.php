<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_number',
        'category',
        'title',
        'description',
        'address',
        'location_district',
        'latitude',
        'longitude',
        'status',
        'admin_notes',
        'editorial_summary',
        'is_featured_live',
        'moderated_by',
        'moderated_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_featured_live' => 'boolean',
            'moderated_at' => 'datetime',
            'resolved_at' => 'datetime',
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

    public function media(): HasMany
    {
        return $this->hasMany(ReportMedia::class);
    }

    /**
     * Map internal status to human-readable label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_verification' => 'Menunggu Verifikasi',
            'processing_editorial' => 'Diproses Redaksi',
            'live_agenda' => 'Masuk Agenda Live',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    /**
     * Step index for 4-step workflow tracker (1 to 4)
     */
    public function getWorkflowStepAttribute(): int
    {
        return match($this->status) {
            'pending_verification' => 1,
            'processing_editorial' => 2,
            'live_agenda' => 3,
            'resolved' => 4,
            'rejected' => 0,
            default => 1,
        };
    }
}
