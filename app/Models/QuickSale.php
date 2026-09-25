<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuickSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'price',
        'condition',
        'description',
        'location_name',
        'contact_phone',
        'contact_whatsapp',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'moderated_at' => 'datetime',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
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
        return $this->hasMany(QuickSaleMedia::class)->orderBy('sort_order');
    }

    public function primaryMedia(): ?QuickSaleMedia
    {
        return $this->media()->where('is_primary', true)->first() ?: $this->media()->first();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
